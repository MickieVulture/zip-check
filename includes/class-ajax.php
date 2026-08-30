<?php
/**
 * AJAX ZIP checker.
 *
 * @package DVLNT_Service_Area_Checker
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DVLNT_SAC_Ajax {
	/** @return void */
	public static function init() {
		add_action( 'wp_ajax_dvlnt_sac_check_zip', array( __CLASS__, 'check' ) );
		add_action( 'wp_ajax_nopriv_dvlnt_sac_check_zip', array( __CLASS__, 'check' ) );
	}

	/** @return void */
	public static function check() {
		check_ajax_referer( 'dvlnt_sac_check_zip', 'nonce' );

		$zip = isset( $_POST['zip'] ) ? sanitize_text_field( wp_unslash( $_POST['zip'] ) ) : '';
		$zip = trim( $zip );
		if ( ! preg_match( '/^\d{5}$/', $zip ) ) {
			wp_send_json_error( array( 'message' => __( 'Enter a valid 5-digit ZIP code.', 'dvlnt-service-area-checker' ) ), 400 );
		}

		$settings = DVLNT_SAC_Settings::get();
		$zip_list = preg_split( '/\R/', $settings['zip_codes'], -1, PREG_SPLIT_NO_EMPTY );
		wp_send_json_success(
			array(
				'serviced' => in_array( $zip, $zip_list, true ),
				'zip'      => $zip,
			)
		);
	}
}

