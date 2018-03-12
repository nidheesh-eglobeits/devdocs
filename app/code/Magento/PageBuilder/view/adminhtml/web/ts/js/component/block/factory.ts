/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

import loadModule from "Magento_PageBuilder/js/component/loader";
import {ConfigContentBlock} from "../config";
import Stage from "../stage";
import Structural from "../stage/structural/abstract";
import EditableArea from "../stage/structural/editable-area";
import Block from "./block";

/**
 * Retrieve the block instance from the config object
 *
 * @param config
 * @returns {any|string}
 */
function getBlockComponentPath(config: ConfigContentBlock): string {
    return config.component || "Magento_PageBuilder/js/component/block/block";
}

/**
 * Create a new instance of a block
 *
 * @param config
 * @param parent
 * @param stage
 * @param formData
 * @returns {Promise<BlockInterface>}
 */
export default function createBlock(
    config: ConfigContentBlock,
    parent: EditableArea,
    stage: Stage,
    formData?: object,
): Promise<BlockInterface> {
    stage = stage || parent.stage;
    formData = formData || {};
    return new Promise((resolve: (blockComponent: any) => void) => {
        loadModule([getBlockComponentPath(config)], (blockComponent: any) => {
            resolve(new blockComponent(parent, stage, config, formData));
        });
    });
}