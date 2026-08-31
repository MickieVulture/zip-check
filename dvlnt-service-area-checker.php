<?php
/**
 * Plugin Name: DVLNT Service Area Checker
 * Description: A lightweight, accessible ZIP-code service-area checker for local service businesses.
 * Version: 1.2.0
 * Author: DVLNT
 * Text Domain: dvlnt-service-area-checker
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DVLNT_SAC_VERSION', '1.2.0' );
define( 'DVLNT_SAC_FILE', __FILE__ );
define( 'DVLNT_SAC_PATH', plugin_dir_path( __FILE__ ) );
define( 'DVLNT_SAC_URL', plugin_dir_url( __FILE__ ) );

require_once DVLNT_SAC_PATH . 'includes/class-settings.php';
require_once DVLNT_SAC_PATH . 'includes/class-analytics.php';
require_once DVLNT_SAC_PATH . 'includes/class-admin.php';
require_once DVLNT_SAC_PATH . 'includes/class-shortcode.php';
require_once DVLNT_SAC_PATH . 'includes/class-ajax.php';

/**
 * Boot the plugin.
 *
 * @return void
 */
function dvlnt_sac_init() {
	DVLNT_SAC_Analytics::init();
	DVLNT_SAC_Settings::init();
	DVLNT_SAC_Shortcode::init();
	DVLNT_SAC_Ajax::init();

	if ( is_admin() ) {
		DVLNT_SAC_Admin::init();
	}
}
add_action( 'plugins_loaded', 'dvlnt_sac_init' );

register_activation_hook( __FILE__, array( 'DVLNT_SAC_Analytics', 'install' ) );
