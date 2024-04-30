export const useDeviceType = () => {
	const { useSelect } = wp.data;
	const { deviceType } = useSelect(
		select => {
			const { getDeviceType } = select("core/editor");
 			return {
				deviceType: getDeviceType() || 'Desktop',
			}
		})
	return deviceType;
}

export default useDeviceType;