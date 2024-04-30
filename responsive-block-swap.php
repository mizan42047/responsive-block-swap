<?php

/**
 * Plugin Name:       Responsive Block Visibility Swap
 * Description:       Easily hide and show Gutenberg blocks based on screen size for a responsive website layout.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           1.0.3
 * Author:            Mijanur Rahman
 * Author URI:        https://github.com/mizan42047
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       responsive-block-swap
 * Domain Path:       /languages
 */

final class Responsive_Block_Swap
{
	/**
	 * plugin version
	 * @var string
	 */
	const VERSION = '1.0.3';

	/**
	 * \Responsive_Block_Swap class constructor.
	 * private for singleton
	 * @return void
	 * @since 0.1.0
	 */
	private function __construct()
	{
		$this->responsive_block_swap_constant();

		add_action('plugins_loaded', [$this, 'responsive_block_swap_load_textdomain']);
		add_action('enqueue_block_editor_assets', [$this, 'responsive_block_swap_enqueue_block_editor_assets']);
		if (!is_admin()) add_action("enqueue_block_assets", [$this, 'responsive_block_swap_enqueue_block_assets']);
		add_action('init', [$this, 'init']);
		add_filter("render_block", [$this, 'responsive_block_swap_render_block'], 10, 2);

		// load after plugin activation
		register_activation_hook(__FILE__, [$this, 'responsive_block_swap_activation']);
	}

	public static function init()
	{
		static $instance = false;
		if (!$instance) {
			$instance = new self();
		}
		return $instance;
	}

	public function responsive_block_swap_load_textdomain()
	{
		load_plugin_textdomain('responsive-block-swap', false, RESPONSIVE_BLOCK_SWAP_PATH . '/languages');
	}

	public function responsive_block_swap_enqueue_block_editor_assets()
	{
		$assets = include(RESPONSIVE_BLOCK_SWAP_PATH . 'build/index.asset.php');
		wp_enqueue_script('responsive-block-swap-script', RESPONSIVE_BLOCK_SWAP_URL . 'build/index.js', $assets['dependencies'], $assets['version'], true);
		wp_enqueue_style('responsive-block-swap-editor-style', RESPONSIVE_BLOCK_SWAP_URL . 'build/index.css', [], $assets['version']);
		wp_set_script_translations('responsive-block-swap-script', 'responsive-block-swap', RESPONSIVE_BLOCK_SWAP_PATH . '/languages');
	}

	public function responsive_block_swap_enqueue_block_assets()
	{
		$assets = include(RESPONSIVE_BLOCK_SWAP_PATH . 'build/index.asset.php');
		wp_enqueue_style('responsive-block-swap-style', RESPONSIVE_BLOCK_SWAP_URL . 'build/style-index.css', [], $assets['version']);
	}

	function responsive_block_swap_genrate_class($block)
	{
		$classes = [];
		isset($block['attrs']['responsiveBlockSwapHideDesktop']) && $block['attrs']['responsiveBlockSwapHideDesktop'] ? $classes[] = 'responsive-block-swap-hide-desktop' : '';
		isset($block['attrs']['responsiveBlockSwapHideTablet']) && $block['attrs']['responsiveBlockSwapHideTablet'] ? $classes[] = 'responsive-block-swap-hide-tablet' : '';
		isset($block['attrs']['responsiveBlockSwapHideMobile']) && $block['attrs']['responsiveBlockSwapHideMobile'] ? $classes[] = 'responsive-block-swap-hide-mobile' : '';

		return !empty($classes) ? implode(' ', $classes) : '';
	}

	function responsive_block_swap_render_block($block_content, $block)
	{
		global $wp_version;

		if ($wp_version >= 6.2) {
			$content = new \WP_HTML_Tag_Processor($block_content);
			$content->next_tag();

			if( !empty($block['attrs']['responsiveBlockSwapHideDesktop']) ){
				$content->add_class('responsive-block-swap-hide-desktop');
			}

			if( !empty($block['attrs']['responsiveBlockSwapHideTablet']) ){
				$content->add_class('responsive-block-swap-hide-tablet');
			}

			if( !empty($block['attrs']['responsiveBlockSwapHideMobile']) ){
				$content->add_class('responsive-block-swap-hide-mobile');
			}

			$block_content = $content->get_updated_html();
		} else {
			$classes = $this->responsive_block_swap_genrate_class($block);
			$block_content = !empty($classes) ? preg_replace('/class="(.*?)/', 'class="' . $classes . ' ', $block_content, 1) : $block_content;
		}

		return $block_content;
	}

	public function responsive_block_swap_activation()
	{
		//Update vertion to the options table
		update_option("responsive_block_swap_version", RESPONSIVE_BLOCK_SWAP_VERSION);
		//added installed time after checking time exist or not
		if (!get_option("responsive_block_swap_installed_time")) {
			add_option("responsive_block_swap_installed_time", time());
		}
	}

	public function responsive_block_swap_constant()
	{
		define('RESPONSIVE_BLOCK_SWAP_VERSION', self::VERSION);
		define('RESPONSIVE_BLOCK_SWAP_URL', plugin_dir_url(__FILE__));
		define('RESPONSIVE_BLOCK_SWAP_PATH', plugin_dir_path(__FILE__));
	}
}

function responsive_block_swap()
{
	return Responsive_Block_Swap::init();
}


responsive_block_swap();
