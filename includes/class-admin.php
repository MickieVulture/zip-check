<?php
/**
 * Admin settings screen.
 *
 * @package DVLNT_Service_Area_Checker
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DVLNT_SAC_Admin {
	/** @return void */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'menu' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'assets' ) );
	}

	/** @return void */
	public static function menu() {
		add_options_page(
			__( 'Service Area Checker', 'dvlnt-service-area-checker' ),
			__( 'Service Area Checker', 'dvlnt-service-area-checker' ),
			'manage_options',
			'dvlnt-service-area-checker',
			array( __CLASS__, 'render' )
		);
	}

	/** @param string $hook Current admin hook. @return void */
	public static function assets( $hook ) {
		if ( 'settings_page_dvlnt-service-area-checker' !== $hook ) {
			return;
		}
		wp_enqueue_style( 'dvlnt-sac-admin', DVLNT_SAC_URL . 'assets/css/admin.css', array(), DVLNT_SAC_VERSION );
		wp_enqueue_script( 'dvlnt-sac-admin', DVLNT_SAC_URL . 'assets/js/admin.js', array(), DVLNT_SAC_VERSION, true );
	}

	/** @return void */
	public static function render() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$settings = DVLNT_SAC_Settings::get();
		$sections = array(
			'Contact' => array(
				'phone'       => array( 'Phone Number', 'text' ),
				'booking_url' => array( 'Booking URL', 'url' ),
			),
			'Initial State' => array(
				'initial_eyebrow'     => array( 'Eyebrow', 'text' ),
				'initial_heading'     => array( 'Heading', 'text' ),
				'initial_description' => array( 'Description', 'text' ),
				'zip_label'           => array( 'ZIP Label', 'text' ),
				'zip_placeholder'     => array( 'ZIP Placeholder', 'text' ),
				'check_label'         => array( 'Check Button Label', 'text' ),
			),
			'Success State' => array(
				'success_eyebrow'     => array( 'Eyebrow', 'text' ),
				'success_heading'     => array( 'Heading', 'text' ),
				'success_description' => array( 'Description (supports [ZIP])', 'text' ),
				'success_reassurance' => array( 'Reassurance Text', 'text' ),
				'booking_label'       => array( 'Booking Button Label', 'text' ),
				'call_label'          => array( 'Call Button Label', 'text' ),
			),
			'Unavailable State' => array(
				'unavailable_eyebrow'     => array( 'Eyebrow', 'text' ),
				'unavailable_heading'     => array( 'Heading', 'text' ),
				'unavailable_description' => array( 'Description (supports [ZIP])', 'text' ),
				'retry_label'             => array( 'Retry Button Label', 'text' ),
			),
			'Appearance' => array(
				'accent_color'  => array( 'Primary Accent Color', 'color' ),
				'border_radius' => array( 'Modal Border Radius (px)', 'number' ),
			),
		);
		?>
		<div class="wrap dvlnt-sac-admin">
			<h1><?php echo esc_html__( 'DVLNT Service Area Checker', 'dvlnt-service-area-checker' ); ?></h1>
			<p><?php echo esc_html__( 'Add [service_area_checker] to the page, then add the CSS class service-area-trigger to any Brizy button or element.', 'dvlnt-service-area-checker' ); ?></p>
			<form action="options.php" method="post">
				<?php settings_fields( 'dvlnt_sac_group' ); ?>
				<section class="dvlnt-sac-card">
					<h2><?php echo esc_html__( 'ZIP Codes', 'dvlnt-service-area-checker' ); ?></h2>
					<label for="dvlnt-sac-zip-codes"><?php echo esc_html__( 'Serviced ZIP Codes', 'dvlnt-service-area-checker' ); ?></label>
					<textarea id="dvlnt-sac-zip-codes" name="<?php echo esc_attr( DVLNT_SAC_Settings::OPTION_NAME ); ?>[zip_codes]" rows="12" class="large-text code"><?php echo esc_textarea( $settings['zip_codes'] ); ?></textarea>
					<p class="description"><?php echo esc_html__( 'Enter one per line, comma-separated, or mixed. Invalid entries are removed and duplicates are merged when saved.', 'dvlnt-service-area-checker' ); ?> <span id="dvlnt-sac-zip-count"></span></p>
				</section>
				<?php foreach ( $sections as $title => $fields ) : ?>
					<section class="dvlnt-sac-card">
						<h2><?php echo esc_html( $title ); ?></h2>
						<table class="form-table" role="presentation"><tbody>
						<?php foreach ( $fields as $key => $field ) : ?>
							<tr>
								<th scope="row"><label for="dvlnt-sac-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field[0] ); ?></label></th>
								<td><input id="dvlnt-sac-<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( DVLNT_SAC_Settings::OPTION_NAME ); ?>[<?php echo esc_attr( $key ); ?>]" type="<?php echo esc_attr( $field[1] ); ?>" value="<?php echo esc_attr( $settings[ $key ] ); ?>" class="regular-text"<?php echo 'number' === $field[1] ? ' min="0" max="48"' : ''; ?>></td>
							</tr>
						<?php endforeach; ?>
						</tbody></table>
					</section>
				<?php endforeach; ?>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}

