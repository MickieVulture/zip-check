<?php
/** Lightweight, aggregate ZIP analytics. @package DVLNT_Service_Area_Checker */
if ( ! defined( 'ABSPATH' ) ) { exit; }

class DVLNT_SAC_Analytics {
	const DB_VERSION = '1.0';
	const DB_OPTION = 'dvlnt_sac_analytics_db_version';
	public static function init() { add_action( 'wp_ajax_dvlnt_sac_track_action', array( __CLASS__, 'track_action' ) ); add_action( 'wp_ajax_nopriv_dvlnt_sac_track_action', array( __CLASS__, 'track_action' ) ); if ( get_option( self::DB_OPTION ) !== self::DB_VERSION ) { self::install(); } }
	public static function table() { global $wpdb; return $wpdb->prefix . 'dvlnt_sac_zip_analytics'; }
	public static function install() {
		global $wpdb; require_once ABSPATH . 'wp-admin/includes/upgrade.php'; $table = self::table(); $charset = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table (
			zip char(5) NOT NULL,
			checks bigint(20) unsigned NOT NULL DEFAULT 0,
			supported_checks bigint(20) unsigned NOT NULL DEFAULT 0,
			unsupported_checks bigint(20) unsigned NOT NULL DEFAULT 0,
			book_clicks bigint(20) unsigned NOT NULL DEFAULT 0,
			call_clicks bigint(20) unsigned NOT NULL DEFAULT 0,
			last_supported tinyint(1) unsigned NOT NULL DEFAULT 0,
			first_checked datetime NOT NULL,
			last_checked datetime NOT NULL,
			last_book_clicked datetime NULL,
			last_call_clicked datetime NULL,
			PRIMARY KEY  (zip),
			KEY unsupported_demand (last_supported,unsupported_checks),
			KEY checks (checks),
			KEY last_checked (last_checked)
		) $charset;";
		dbDelta( $sql ); update_option( self::DB_OPTION, self::DB_VERSION, false );
	}
	public static function record_check( $zip, $supported ) {
		if ( ! preg_match( '/^\d{5}$/', (string) $zip ) ) { return; }
		global $wpdb; $table = self::table(); $now = current_time( 'mysql' ); $yes = $supported ? 1 : 0; $no = $supported ? 0 : 1;
		$sql = $wpdb->prepare( "INSERT INTO $table (zip,checks,supported_checks,unsupported_checks,last_supported,first_checked,last_checked) VALUES (%s,1,%d,%d,%d,%s,%s) ON DUPLICATE KEY UPDATE checks=checks+1,supported_checks=supported_checks+VALUES(supported_checks),unsupported_checks=unsupported_checks+VALUES(unsupported_checks),last_supported=VALUES(last_supported),last_checked=VALUES(last_checked)", $zip, $yes, $no, $yes, $now, $now );
		$wpdb->query( $sql ); // Analytics errors are intentionally non-fatal.
	}
	public static function track_action() {
		check_ajax_referer( 'dvlnt_sac_check_zip', 'nonce' );
		$zip = isset( $_POST['zip'] ) ? sanitize_text_field( wp_unslash( $_POST['zip'] ) ) : ''; $event = isset( $_POST['event'] ) ? sanitize_key( wp_unslash( $_POST['event'] ) ) : '';
		if ( ! preg_match( '/^\d{5}$/', $zip ) || ! in_array( $event, array( 'book', 'call' ), true ) ) { wp_send_json_error( null, 400 ); }
		global $wpdb; $table = self::table(); $column = 'book' === $event ? 'book_clicks' : 'call_clicks'; $time_column = 'book' === $event ? 'last_book_clicked' : 'last_call_clicked'; $now = current_time( 'mysql' ); $wpdb->query( $wpdb->prepare( "UPDATE $table SET $column=$column+1,$time_column=%s WHERE zip=%s AND last_supported=1", $now, $zip ) ); wp_send_json_success();
	}
}
