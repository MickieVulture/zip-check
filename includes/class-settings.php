<?php
/**
 * Settings storage and sanitization.
 *
 * @package DVLNT_Service_Area_Checker
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DVLNT_SAC_Settings {
	const OPTION_NAME = 'dvlnt_sac_settings';

	/** @return void */
	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'register' ) );
	}

	/** @return array<string,string> */
	public static function defaults() {
		return array(
			'zip_codes'              => '',
			'phone'                  => '',
			'booking_url'            => '',
			'initial_eyebrow'        => 'SERVICE AVAILABILITY',
			'initial_heading'        => 'Do we service your area?',
			'initial_description'    => 'Enter your ZIP code to check service availability.',
			'zip_label'              => 'ZIP Code',
			'zip_placeholder'        => '91301',
			'check_label'            => 'Check Availability',
			'success_eyebrow'        => 'SERVICE AVAILABLE',
			'success_heading'        => 'Yes, we service your area!',
			'success_description'    => 'Great news—we provide service in [ZIP].',
			'success_reassurance'    => 'Friendly, dependable service is just a click away.',
			'booking_label'          => 'Book Service',
			'call_label'             => 'Call Now',
			'unavailable_eyebrow'    => 'SERVICE AREA UPDATE',
			'unavailable_heading'    => 'We’re not in your area yet.',
			'unavailable_description'=> 'We do not currently provide service in [ZIP], but our service area is always growing.',
			'retry_label'            => 'Check Another ZIP Code',
			'accent_color'           => '#0EA5E9',
			'border_radius'          => '22',
		);
	}

	/** @return array<string,string> */
	public static function get() {
		$saved = get_option( self::OPTION_NAME, array() );
		return wp_parse_args( is_array( $saved ) ? $saved : array(), self::defaults() );
	}

	/** @return void */
	public static function register() {
		register_setting(
			'dvlnt_sac_group',
			self::OPTION_NAME,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( __CLASS__, 'sanitize' ),
				'default'           => self::defaults(),
			)
		);
	}

	/**
	 * Normalize a mixed ZIP-code list.
	 *
	 * @param string $value Raw ZIP list.
	 * @return string
	 */
	public static function normalize_zip_codes( $value ) {
		$parts = preg_split( '/[\s,]+/', (string) $value, -1, PREG_SPLIT_NO_EMPTY );
		$valid = array();
		foreach ( $parts as $part ) {
			$zip = trim( $part );
			if ( preg_match( '/^\d{5}$/', $zip ) ) {
				$valid[ $zip ] = $zip;
			}
		}
		return implode( "\n", array_values( $valid ) );
	}

	/**
	 * Sanitize all settings.
	 *
	 * @param mixed $input Submitted settings.
	 * @return array<string,string>
	 */
	public static function sanitize( $input ) {
		$input    = is_array( $input ) ? $input : array();
		$defaults = self::defaults();
		$output   = array();

		$output['zip_codes']   = self::normalize_zip_codes( $input['zip_codes'] ?? '' );
		$output['phone']       = sanitize_text_field( $input['phone'] ?? '' );
		$output['booking_url'] = esc_url_raw( $input['booking_url'] ?? '' );

		$text_fields = array_diff(
			array_keys( $defaults ),
			array( 'zip_codes', 'phone', 'booking_url', 'accent_color', 'border_radius' )
		);
		foreach ( $text_fields as $key ) {
			$output[ $key ] = sanitize_text_field( $input[ $key ] ?? $defaults[ $key ] );
		}

		$color                  = sanitize_hex_color( $input['accent_color'] ?? '' );
		$output['accent_color'] = $color ? $color : $defaults['accent_color'];
		$radius                 = absint( $input['border_radius'] ?? $defaults['border_radius'] );
		$output['border_radius']= (string) min( 48, max( 0, $radius ) );

		return $output;
	}
}

