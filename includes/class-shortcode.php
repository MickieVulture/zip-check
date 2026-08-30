<?php
/**
 * Frontend shortcodes and assets.
 *
 * @package DVLNT_Service_Area_Checker
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DVLNT_SAC_Shortcode {
	private static $rendered = false;

	/** @return void */
	public static function init() {
		add_shortcode( 'service_area_checker', array( __CLASS__, 'checker' ) );
		add_shortcode( 'service_area_button', array( __CLASS__, 'button' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'assets' ) );
	}

	/** @return void */
	public static function assets() {
		$css_file    = DVLNT_SAC_PATH . 'assets/css/frontend.css';
		$script_file = DVLNT_SAC_PATH . 'assets/js/frontend.js';
		$css_version = file_exists( $css_file ) ? (string) filemtime( $css_file ) : DVLNT_SAC_VERSION;
		$js_version  = file_exists( $script_file ) ? (string) filemtime( $script_file ) : DVLNT_SAC_VERSION;

		wp_enqueue_style( 'dvlnt-sac-frontend', DVLNT_SAC_URL . 'assets/css/frontend.css', array(), $css_version );
		wp_enqueue_script( 'dvlnt-sac-frontend', DVLNT_SAC_URL . 'assets/js/frontend.js', array(), $js_version, true );
		wp_localize_script(
			'dvlnt-sac-frontend',
			'dvlntSAC',
			array(
				'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
				'nonce'        => wp_create_nonce( 'dvlnt_sac_check_zip' ),
				'invalidText'  => __( 'Enter a valid 5-digit ZIP code.', 'dvlnt-service-area-checker' ),
				'errorText'    => __( 'We could not check that ZIP right now. Please try again.', 'dvlnt-service-area-checker' ),
				'checkingText' => __( 'Checking…', 'dvlnt-service-area-checker' ),
			)
		);
	}

	/** @return string */
	public static function checker() {
		if ( self::$rendered ) {
			return '';
		}
		self::$rendered = true;
		$settings = DVLNT_SAC_Settings::get();
		ob_start();
		include DVLNT_SAC_PATH . 'templates/modal.php';
		return (string) ob_get_clean();
	}

	/** @param array<string,string> $atts Shortcode attributes. @return string */
	public static function button( $atts ) {
		$atts = shortcode_atts( array( 'text' => 'Check Your Area' ), $atts, 'service_area_button' );
		$modal = self::checker();
		return '<button type="button" class="service-area-trigger dvlnt-sac-trigger-button">' . esc_html( $atts['text'] ) . '</button>' . $modal;
	}
}
