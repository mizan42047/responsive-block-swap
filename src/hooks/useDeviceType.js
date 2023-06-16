import { useSelect } from '@wordpress/data'

export const useDeviceType = () => {
	const siteEditor = responsiveBlockSwap.screen === "site-editor.php" ? true : false;

	const { deviceType } = useSelect(
		select => {
			return {
				deviceType: select(
					siteEditor ? 'core/edit-site' : 'core/edit-post',
				)?.__experimentalGetPreviewDeviceType() || 'Desktop',
			}
		},
		[]
	)
	
	return deviceType || ''
}

export default useDeviceType;