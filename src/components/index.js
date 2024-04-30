import { __ } from '@wordpress/i18n';
const { createHigherOrderComponent } = wp.compose;
const { addFilter } = wp.hooks;
const ResponsiveVisibilityControls = createHigherOrderComponent((BlockEdit) => {
    const { InspectorAdvancedControls } = wp.blockEditor;
    const {
        ToggleControl,
        __experimentalHeading: Heading,
        __experimentalText: Text,
        __experimentalDivider: Divider,
        __experimentalSpacer: Spacer,
    } = wp.components;
    
    return (props) => {
        return (
            <>
                <BlockEdit {...props} />
                <InspectorAdvancedControls>
                    <Divider />
                    <Heading>{__('Responsive Visibility', 'responsive-block-swap')}</Heading>
                    <Spacer marginBottom={5}>
                        <Text type="xSmall" style={{ fontSize: '12px', color: `rgb(117, 117, 117)` }}>{__('These controls will only be active on the frontend. In the editor, you will see an overlay indicating that the block are disabled on specific devices.', 'responsive-block-swap')}</Text>
                    </Spacer>
                    <ToggleControl
                        label={__('Hide on Desktop', 'responsive-block-swap')}
                        checked={props.attributes.responsiveBlockSwapHideDesktop}
                        onChange={() => {
                            props.setAttributes({ responsiveBlockSwapHideDesktop: !props.attributes.responsiveBlockSwapHideDesktop })
                        }}
                    />
                    <ToggleControl
                        label={__('Hide on Tablet', 'responsive-block-swap')}
                        checked={props.attributes.responsiveBlockSwapHideTablet}
                        onChange={() => {
                            props.setAttributes({ responsiveBlockSwapHideTablet: !props.attributes.responsiveBlockSwapHideTablet })
                        }}
                    />
                    <ToggleControl
                        label={__('Hide on Mobile', 'responsive-block-swap')}
                        checked={props.attributes.responsiveBlockSwapHideMobile}
                        onChange={() => {
                            props.setAttributes({ responsiveBlockSwapHideMobile: !props.attributes.responsiveBlockSwapHideMobile })
                        }}
                    />
                </InspectorAdvancedControls>
            </>
        );
    };
}, 'ResponsiveVisibilityControls');

addFilter(
    'editor.BlockEdit',
    'noobs/add-responsive-visibility-controls',
    ResponsiveVisibilityControls
);

