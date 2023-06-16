import { addFilter } from '@wordpress/hooks';
const ResponsiveVisibilityAttributes = (settings, name) => {
    if ("attributes" in settings) {
        settings.attributes = {
            ...settings.attributes,
            "responsiveBlockSwapHideDesktop": {
                type: 'boolean',
                default: false,
            },
            "responsiveBlockSwapHideTablet": {
                type: 'boolean',
                default: false,
            },
            "responsiveBlockSwapHideMobile": {
                type: 'boolean',
                default: false,
            },
        }
    }

    return settings;
}

addFilter(
    'blocks.registerBlockType',
    'noobs/add-responsive-visibility-attribute',
    ResponsiveVisibilityAttributes
)