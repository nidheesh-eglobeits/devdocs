<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\PageBuilder\Model\Stage;

class Config
{
    const DEFAULT_COMPONENT = 'Magento_PageBuilder/js/component/block/block';
    const DEFAULT_PREVIEW_COMPONENT = 'Magento_PageBuilder/js/component/block/preview/block';

    /**
     * @var \Magento\PageBuilder\Model\Config\ConfigInterface
     */
    private $config;

    /**
     * @var Config\UiComponentConfig
     */
    private $uiComponentConfig;

    /**
     * @var array
     */
    private $data;

    /**
     * Constructor
     *
     * @param \Magento\PageBuilder\Model\Config\ConfigInterface $config
     * @param Config\UiComponentConfig $uiComponentConfig
     * @param array $data
     */
    public function __construct(
        \Magento\PageBuilder\Model\Config\ConfigInterface $config,
        Config\UiComponentConfig $uiComponentConfig,
        array $data = []
    ) {
        $this->config = $config;
        $this->uiComponentConfig = $uiComponentConfig;
        $this->data = $data;
    }

    /**
     * Return the config for the page builder instance
     *
     * @return array
     */
    public function getConfig()
    {
        return [
            'groups' => $this->getGroups(),
            'content_types' => $this->getContentTypes(),
            'stage_config' => $this->data
        ];
    }

    /**
     * Retrieve the content block groups
     *
     * @return array
     */
    private function getGroups()
    {
        return $this->config->getGroups();
    }

    /**
     * Build up the content block data
     *
     * @return array
     */
    private function getContentTypes()
    {
        $contentTypes = $this->config->getContentTypes();

        $contentBlockData = [];
        foreach ($contentTypes as $name => $contentType) {
            $contentBlockData[$name] = $this->flattenContentTypeData(
                $name,
                $contentType
            );
        }

        return $contentBlockData;
    }

    /**
     * Flatten the content block data
     *
     * @param $name
     * @param $contentType
     *
     * @return array
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function flattenContentTypeData($name, $contentType)
    {
        return [
            'name' => $name,
            'label' => __($contentType['label']),
            'icon' => $contentType['icon'],
            'form' => $contentType['form'],
            'contentType' => '',
            'group' => (isset($contentType['group'])
                ? $contentType['group'] : 'general'),
            'fields' => $this->uiComponentConfig->getFields($contentType['form']),
            'preview_template' => (isset($contentType['preview_template'])
                ? $contentType['preview_template'] : ''),
            'render_template' => (isset($contentType['render_template'])
                ? $contentType['render_template'] : ''),
            'preview_component' => (isset($contentType['preview_component'])
                ? $contentType['preview_component']
                : self::DEFAULT_PREVIEW_COMPONENT),
            'component' => (isset($contentType['component'])
                ? $contentType['component'] : self::DEFAULT_COMPONENT),
            'allowed_parents' => isset($contentType['allowed_parents'])
                ? explode(',', $contentType['allowed_parents']) : [],
            'readers' => isset($contentType['readers']) ? $contentType['readers'] : [],
            'appearances' => isset($contentType['appearances']) ? $contentType['appearances'] : [],
            'is_visible' => isset($contentType['is_visible']) && $contentType['is_visible'] === 'false' ? false : true
        ];
    }
}