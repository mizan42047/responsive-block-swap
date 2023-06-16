import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import classNames from 'classnames';
import useDeviceType from '../hooks/useDeviceType';

const ResponsiveBlockSwapVisibilityClass = createHigherOrderComponent(
    (BlockListBlock) => {
        return (props) => {
            const device = useDeviceType();
            const { 
                responsiveBlockSwapHideDesktop,
                responsiveBlockSwapHideTablet,
                responsiveBlockSwapHideMobile
            } = props.attributes;

            const className = classNames(
				{
					'responsive-block-swap-hide-desktop': responsiveBlockSwapHideDesktop && device === 'Desktop',
					'responsive-block-swap-hide-tablet': responsiveBlockSwapHideTablet && device === 'Tablet',
					'responsive-block-swap-hide-mobile': responsiveBlockSwapHideMobile && device === 'Mobile'
				}
			)
			return (
                <>
                    <BlockListBlock {...props} className={className} />
                </>
            )
        };
    },
    'ResponsiveBlockSwapVisibilityClass'
);


addFilter(
    'editor.BlockListBlock',
    'noobs/responsive-block-swap-visibility-class',
    ResponsiveBlockSwapVisibilityClass
);