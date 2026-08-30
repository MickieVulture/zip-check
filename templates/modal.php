<?php
/** @var array<string,string> $settings */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$phone_href = preg_replace( '/[^0-9+]/', '', $settings['phone'] );
$style       = '--dvlnt-sac-accent:' . $settings['accent_color'] . ';--dvlnt-sac-radius:' . absint( $settings['border_radius'] ) . 'px;';
?>
<div class="dvlnt-sac" data-dvlnt-sac hidden style="<?php echo esc_attr( $style ); ?>">
	<div class="dvlnt-sac__overlay" data-sac-close></div>
	<div class="dvlnt-sac__dialog" role="dialog" aria-modal="true" aria-labelledby="dvlnt-sac-title" aria-describedby="dvlnt-sac-description" tabindex="-1">
		<button type="button" class="dvlnt-sac__close" data-sac-close aria-label="<?php echo esc_attr__( 'Close service area checker', 'dvlnt-service-area-checker' ); ?>">
			<span aria-hidden="true">&times;</span>
		</button>

		<div class="dvlnt-sac__state" data-sac-state="initial" data-title-id="dvlnt-sac-title" data-description-id="dvlnt-sac-description">
			<p class="dvlnt-sac__eyebrow"><?php echo esc_html( $settings['initial_eyebrow'] ); ?></p>
			<h2 class="dvlnt-sac__title" id="dvlnt-sac-title"><?php echo esc_html( $settings['initial_heading'] ); ?></h2>
			<p class="dvlnt-sac__description" id="dvlnt-sac-description"><?php echo esc_html( $settings['initial_description'] ); ?></p>
			<form class="dvlnt-sac__form" novalidate>
				<label for="dvlnt-sac-zip"><?php echo esc_html( $settings['zip_label'] ); ?></label>
				<input id="dvlnt-sac-zip" name="zip" type="text" inputmode="numeric" autocomplete="postal-code" maxlength="5" pattern="[0-9]{5}" placeholder="<?php echo esc_attr( $settings['zip_placeholder'] ); ?>" aria-describedby="dvlnt-sac-error" required>
				<p class="dvlnt-sac__error" id="dvlnt-sac-error" role="alert" aria-live="polite"></p>
				<button type="submit" class="dvlnt-sac__button dvlnt-sac__button--primary" data-default-text="<?php echo esc_attr( $settings['check_label'] ); ?>"><?php echo esc_html( $settings['check_label'] ); ?></button>
			</form>
		</div>

		<div class="dvlnt-sac__state" data-sac-state="success" data-title-id="dvlnt-sac-success-title" data-description-id="dvlnt-sac-success-description" hidden>
			<p class="dvlnt-sac__eyebrow dvlnt-sac__eyebrow--success"><?php echo esc_html( $settings['success_eyebrow'] ); ?></p>
			<div class="dvlnt-sac__result-heading">
				<div class="dvlnt-sac__icon dvlnt-sac__icon--success" aria-hidden="true">&#10003;</div>
				<h2 class="dvlnt-sac__title" id="dvlnt-sac-success-title"><?php echo esc_html( $settings['success_heading'] ); ?></h2>
			</div>
			<p class="dvlnt-sac__description" id="dvlnt-sac-success-description" data-zip-template="<?php echo esc_attr( $settings['success_description'] ); ?>"></p>
			<div class="dvlnt-sac__actions">
				<?php if ( $settings['booking_url'] ) : ?><a class="dvlnt-sac__button dvlnt-sac__button--primary" href="<?php echo esc_url( $settings['booking_url'] ); ?>"><svg class="dvlnt-sac__button-icon" aria-hidden="true" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M16 3v4M8 3v4M3 10h18M12 14v4M10 16h4"></path></svg><?php echo esc_html( $settings['booking_label'] ); ?></a><?php endif; ?>
				<?php if ( $phone_href ) : ?><a class="dvlnt-sac__button dvlnt-sac__button--secondary" href="tel:<?php echo esc_attr( $phone_href ); ?>"><svg class="dvlnt-sac__button-icon" aria-hidden="true" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.69 2.8a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.33 1.84.56 2.8.69A2 2 0 0 1 22 16.92z"></path></svg><?php echo esc_html( $settings['call_label'] ); ?><?php echo $settings['phone'] ? ' ' . esc_html( $settings['phone'] ) : ''; ?></a><?php endif; ?>
			</div>
			<?php if ( $settings['success_reassurance'] ) : ?><p class="dvlnt-sac__reassurance"><svg aria-hidden="true" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="m9 12 2 2 4-4"></path></svg><?php echo esc_html( $settings['success_reassurance'] ); ?></p><?php endif; ?>
		</div>

		<div class="dvlnt-sac__state" data-sac-state="unavailable" data-title-id="dvlnt-sac-unavailable-title" data-description-id="dvlnt-sac-unavailable-description" hidden>
			<p class="dvlnt-sac__eyebrow dvlnt-sac__eyebrow--unavailable"><?php echo esc_html( $settings['unavailable_eyebrow'] ); ?></p>
			<div class="dvlnt-sac__result-heading">
				<div class="dvlnt-sac__icon dvlnt-sac__icon--unavailable" aria-hidden="true">&times;</div>
				<h2 class="dvlnt-sac__title" id="dvlnt-sac-unavailable-title"><?php echo esc_html( $settings['unavailable_heading'] ); ?></h2>
			</div>
			<p class="dvlnt-sac__description" id="dvlnt-sac-unavailable-description" data-zip-template="<?php echo esc_attr( $settings['unavailable_description'] ); ?>"></p>
			<button type="button" class="dvlnt-sac__button dvlnt-sac__button--secondary dvlnt-sac__retry" data-sac-retry><svg class="dvlnt-sac__button-icon" aria-hidden="true" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-4-4"></path></svg><?php echo esc_html( $settings['retry_label'] ); ?></button>
		</div>
	</div>
</div>
