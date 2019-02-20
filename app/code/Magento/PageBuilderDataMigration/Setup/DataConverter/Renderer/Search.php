<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\PageBuilderDataMigration\Setup\DataConverter\Renderer;

use Magento\PageBuilderDataMigration\Setup\DataConverter\RendererInterface;
use Magento\PageBuilderDataMigration\Setup\DataConverter\EavAttributeLoaderInterface;
use Magento\PageBuilderDataMigration\Setup\DataConverter\StyleExtractorInterface;

/**
 * Render search to PageBuilder format
 */
class Search implements RendererInterface
{
    /**
     * @var StyleExtractorInterface
     */
    private $styleExtractor;

    /**
     * @var EavAttributeLoaderInterface
     */
    private $eavAttributeLoader;

    /**
     * @param StyleExtractorInterface $styleExtractor
     * @param EavAttributeLoaderInterface $eavAttributeLoader
     */
    public function __construct(
        StyleExtractorInterface $styleExtractor,
        EavAttributeLoaderInterface $eavAttributeLoader
    ) {
        $this->styleExtractor = $styleExtractor;
        $this->eavAttributeLoader = $eavAttributeLoader;
    }

    /**
     * @inheritdoc
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function render(array $itemData, array $additionalData = []) : string
    {
        if (!isset($itemData['entityId'])) {
            throw new \InvalidArgumentException('entityId is missing.');
        }
        $eavData = $this->eavAttributeLoader->load($itemData['entityId']);

        $searchStyles = '';
        if (isset($itemData['formData'])) {
            $style = $this->styleExtractor->extractStyle($itemData['formData']);
            if ($style) {
                $searchStyles = $style;
            }
        }

        $searchClasses = $eavData['css_classes'] ?? '';
        $searchPlaceHolder = $eavData['placeholder'] ?? '';

        $searchHtml = "{{block class='Magento\Framework\View\Element\Template' " .
            "template='Magento_PageBuilder::content_type/search.phtml' " .
            "placeholder=\"$searchPlaceHolder\" classes=\"$searchClasses\" styles=\"$searchStyles\"}}";
        $rootElementHtml = '<div data-element="main" data-content-type="html" data-appearance="default">';

        $rootElementHtml .= $searchHtml . '</div>';

        return $rootElementHtml;
    }
}
