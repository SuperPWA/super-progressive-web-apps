<?php
/**
 * BFCM 2026 admin banner.
 *
 * Shown on SuperPWA admin screens until 31 December 2026, 23:59:59 UTC.
 * Dismiss hides it for the current user for 7 days, then it returns.
 *
 * @since 2.2.50
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Unix timestamp when the offer banner must stop rendering.
 *
 * @return int
 */
function superpwa_bfcm_banner_end_timestamp() {
	return (int) strtotime( '2026-12-31 23:59:59 UTC' );
}

/**
 * Whether the current user should see the banner.
 *
 * @return bool
 */
function superpwa_bfcm_banner_is_visible() {
	$end = superpwa_bfcm_banner_end_timestamp();
	if ( $end < 1 || time() > $end ) {
		return false;
	}

	$user_id = get_current_user_id();
	if ( ! $user_id ) {
		return false;
	}

	$dismissed_until = (int) get_user_meta( $user_id, 'superpwa_bfcm_dismissed_until', true );
	if ( $dismissed_until > time() ) {
		return false;
	}

	return true;
}

/**
 * Print the BFCM banner when it is still active for this user.
 */
function superpwa_bfcm_banner() {
	if ( ! is_admin() || ! current_user_can( superpwa_current_user_can() ) ) {
		return;
	}

	if ( ! superpwa_bfcm_banner_is_visible() ) {
		return;
	}

	$end   = superpwa_bfcm_banner_end_timestamp();
	$ajax  = admin_url( 'admin-ajax.php' );
	$nonce = wp_create_nonce( 'superpwa_ajax_check_nonce' );
	$offer = 'https://superpwa.com/bfcm-2026/';
	?>
	<style>
		.superpwa-bfcm {
			position: relative;
			container-type: inline-size;
			box-sizing: border-box;
			margin: 16px 0 18px;
			padding: 12px 40px 12px 16px;
			border-radius: 16px;
			color: #fff;
			background:
				radial-gradient(90% 160% at 96% 40%, rgba(148, 48, 210, 0.55), transparent 46%),
				linear-gradient(90deg, #100612 0%, #1a0b22 48%, #2c123c 100%);
			box-shadow: inset 0 -2px 0 #ff2f86, 0 8px 22px rgba(40, 0, 30, 0.18);
			font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
		}
		.superpwa-bfcm *,
		.superpwa-bfcm *::before,
		.superpwa-bfcm *::after {
			box-sizing: border-box;
		}
		.superpwa-bfcm-inner {
			display: flex;
			align-items: center;
			flex-wrap: wrap;
			gap: 18px;
		}
		.superpwa-bfcm-lead {
			display: flex;
			flex-direction: column;
			align-items: flex-start;
			gap: 8px;
			min-width: 0;
			flex: 1 1 340px;
		}
		.superpwa-bfcm-pill {
			display: inline-flex;
			align-items: center;
			gap: 6px;
			margin: 0;
			padding: 4px 10px;
			border: 1px solid #ff4d93;
			border-radius: 999px;
			background: rgba(255, 45, 130, 0.12);
			color: #ffd0e4;
			font-size: 10px;
			font-weight: 700;
			letter-spacing: 0.06em;
			line-height: 1.2;
			text-transform: uppercase;
			white-space: nowrap;
		}
		.superpwa-bfcm-pill svg {
			display: block;
			flex: 0 0 auto;
		}
		.superpwa-bfcm-offer {
			display: flex;
			align-items: center;
			gap: 12px;
			min-width: 0;
		}
		.superpwa-bfcm-off {
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			flex: 0 0 auto;
			width: 74px;
			height: 54px;
			border-radius: 12px;
			background: linear-gradient(180deg, #ff4f98 0%, #ff2d78 100%);
			box-shadow: 0 6px 16px rgba(255, 45, 120, 0.35);
			line-height: 1;
		}
		.superpwa-bfcm-off strong {
			color: #fff;
			font-size: 20px;
			font-weight: 800;
			letter-spacing: -0.03em;
		}
		.superpwa-bfcm-off span {
			margin-top: 2px;
			color: #fff;
			font-size: 11px;
			font-weight: 800;
			letter-spacing: 0.08em;
		}
		.superpwa-bfcm-text {
			min-width: 0;
		}
		.superpwa-bfcm-text strong {
			display: block;
			color: #fff;
			font-size: 15px;
			font-weight: 800;
			letter-spacing: 0.01em;
			line-height: 1.25;
			text-transform: uppercase;
		}
		.superpwa-bfcm-text span {
			display: block;
			margin-top: 3px;
			color: #f3e9f8;
			font-size: 12px;
			font-weight: 500;
			line-height: 1.35;
		}
		.superpwa-bfcm-code {
			display: inline-flex;
			align-items: center;
			gap: 8px;
			flex: 0 0 auto;
			padding: 8px 10px 8px 12px;
			border: 1.5px dashed #e6b325;
			border-radius: 8px;
			background: rgba(0, 0, 0, 0.28);
			color: #f6d56a;
			font-size: 13px;
			font-weight: 800;
			letter-spacing: 0.04em;
			line-height: 1;
			white-space: nowrap;
		}
		.wp-core-ui .superpwa-bfcm button.superpwa-bfcm-copy,
		.wp-core-ui .superpwa-bfcm button.superpwa-bfcm-dismiss {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			width: 22px;
			height: 22px;
			min-height: 0;
			margin: 0;
			padding: 0;
			border: 0;
			border-radius: 4px;
			background: transparent;
			box-shadow: none;
			color: #f6d56a;
			line-height: 1;
			cursor: pointer;
			vertical-align: middle;
		}
		.superpwa-bfcm-copy:hover,
		.superpwa-bfcm-copy:focus {
			background: rgba(246, 213, 106, 0.16);
		}
		.superpwa-bfcm-copy .superpwa-bfcm-check {
			display: none;
		}
		.superpwa-bfcm-copy.is-copied .superpwa-bfcm-clip {
			display: none;
		}
		.superpwa-bfcm-copy.is-copied .superpwa-bfcm-check {
			display: block;
		}
		.superpwa-bfcm-timer {
			flex: 0 0 auto;
			text-align: center;
		}
		.superpwa-bfcm-timer-label {
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 4px;
			margin-bottom: 6px;
			color: #f0c14b;
			font-size: 10px;
			font-weight: 800;
			letter-spacing: 0.08em;
			line-height: 1;
		}
		.superpwa-bfcm-clock {
			display: flex;
			align-items: flex-start;
			gap: 6px;
		}
		.superpwa-bfcm-unit {
			min-width: 42px;
		}
		.superpwa-bfcm-unit b {
			display: block;
			color: #fff;
			font-size: 22px;
			font-weight: 800;
			letter-spacing: 0.02em;
			line-height: 1;
			font-variant-numeric: tabular-nums;
		}
		.superpwa-bfcm-unit span {
			display: block;
			margin-top: 3px;
			color: #d9c7b2;
			font-size: 9px;
			font-weight: 700;
			letter-spacing: 0.08em;
		}
		.superpwa-bfcm-sep {
			padding-top: 2px;
			color: rgba(255, 255, 255, 0.55);
			font-size: 18px;
			font-weight: 700;
			line-height: 1;
		}
		.superpwa-bfcm-cta {
			display: flex;
			flex-direction: column;
			align-items: center;
			gap: 6px;
			flex: 0 0 auto;
		}
		.superpwa-bfcm-claim {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			gap: 6px;
			min-height: 42px;
			padding: 10px 18px;
			border-radius: 999px;
			background: linear-gradient(90deg, #ff8a1f 0%, #ff5a62 55%, #ff3d88 100%);
			color: #fff !important;
			font-size: 14px;
			font-weight: 800;
			line-height: 1;
			text-decoration: none !important;
			white-space: nowrap;
			box-shadow: 0 8px 18px rgba(255, 80, 70, 0.35);
		}
		.superpwa-bfcm-claim:hover,
		.superpwa-bfcm-claim:focus {
			color: #fff !important;
			filter: brightness(1.06);
		}
		.superpwa-bfcm-guarantee {
			display: inline-flex;
			align-items: center;
			gap: 4px;
			color: #f7f1fb;
			font-size: 11px;
			font-weight: 600;
			line-height: 1.2;
			white-space: nowrap;
		}
		.wp-core-ui .superpwa-bfcm button.superpwa-bfcm-dismiss {
			position: absolute;
			top: 8px;
			right: 8px;
			border-radius: 999px;
			background: rgba(255, 255, 255, 0.08);
			color: #fff;
		}
		.superpwa-bfcm-dismiss:hover,
		.superpwa-bfcm-dismiss:focus {
			background: rgba(255, 255, 255, 0.2);
		}
		.superpwa-bfcm button:focus,
		.superpwa-bfcm a:focus {
			outline: 2px solid #fff;
			outline-offset: 2px;
		}
		@container (max-width: 1120px) {
			.superpwa-bfcm-lead {
				flex-basis: 100%;
			}
		}
		@media (max-width: 782px) {
			.superpwa-bfcm {
				padding-right: 36px;
			}
			.superpwa-bfcm-offer,
			.superpwa-bfcm-clock {
				flex-wrap: wrap;
			}
			.superpwa-bfcm-text strong {
				font-size: 13px;
			}
		}
	</style>
	<div
		id="superpwa-bfcm-banner"
		class="superpwa-bfcm"
		data-end="<?php echo esc_attr( $end ); ?>"
		data-ajax="<?php echo esc_url( $ajax ); ?>"
		data-nonce="<?php echo esc_attr( $nonce ); ?>"
		data-copied="<?php echo esc_attr__( 'Copied', 'super-progressive-web-apps' ); ?>"
		data-copy="<?php echo esc_attr__( 'Copy coupon code', 'super-progressive-web-apps' ); ?>"
	>
		<button type="button" class="superpwa-bfcm-dismiss" aria-label="<?php echo esc_attr__( 'Dismiss this banner for 7 days', 'super-progressive-web-apps' ); ?>">
			<svg width="12" height="12" viewBox="0 0 12 12" aria-hidden="true" focusable="false">
				<path d="M2 2l8 8M10 2L2 10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
			</svg>
		</button>
		<div class="superpwa-bfcm-inner">
			<div class="superpwa-bfcm-lead">
				<p class="superpwa-bfcm-pill">
					<svg width="12" height="12" viewBox="0 0 12 12" aria-hidden="true" focusable="false">
						<path d="M6.8 1L2.2 6.7h3.1L4.6 11 9.8 4.8H6.5L6.8 1z" fill="#ffe14a"/>
					</svg>
					<?php esc_html_e( 'Black Friday / Cyber Monday / Christmas Sale', 'super-progressive-web-apps' ); ?>
				</p>
				<div class="superpwa-bfcm-offer">
					<div class="superpwa-bfcm-off">
						<strong>60%</strong>
						<span><?php esc_html_e( 'OFF', 'super-progressive-web-apps' ); ?></span>
					</div>
					<div class="superpwa-bfcm-text">
						<strong><?php esc_html_e( 'Biggest sale in our history — never before seen!', 'super-progressive-web-apps' ); ?></strong>
						<span><?php esc_html_e( 'Sale is here! 60% off on Everything for a very limited time!!!', 'super-progressive-web-apps' ); ?></span>
					</div>
				</div>
			</div>
			<div class="superpwa-bfcm-code">
				<span><?php esc_html_e( 'CODE:', 'super-progressive-web-apps' ); ?> BFCM2026</span>
				<button type="button" class="superpwa-bfcm-copy" aria-label="<?php echo esc_attr__( 'Copy coupon code', 'super-progressive-web-apps' ); ?>">
					<svg class="superpwa-bfcm-clip" width="14" height="14" viewBox="0 0 14 14" aria-hidden="true" focusable="false">
						<rect x="4.2" y="3.2" width="7.2" height="8.6" rx="1.2" fill="none" stroke="currentColor" stroke-width="1.3"/>
						<rect x="2.4" y="1.8" width="7.2" height="8.6" rx="1.2" fill="#1a0b22" stroke="currentColor" stroke-width="1.3"/>
					</svg>
					<svg class="superpwa-bfcm-check" width="14" height="14" viewBox="0 0 14 14" aria-hidden="true" focusable="false">
						<path d="M3 7.2l2.6 2.6L11 4.2" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</button>
			</div>
			<div class="superpwa-bfcm-timer">
				<div class="superpwa-bfcm-timer-label">
					<svg width="11" height="11" viewBox="0 0 12 12" aria-hidden="true" focusable="false">
						<path d="M6.8 1L2.2 6.7h3.1L4.6 11 9.8 4.8H6.5L6.8 1z" fill="#f0c14b"/>
					</svg>
					<?php esc_html_e( 'Offer expires in:', 'super-progressive-web-apps' ); ?>
				</div>
				<div class="superpwa-bfcm-clock" aria-live="polite">
					<div class="superpwa-bfcm-unit">
						<b data-unit="days">00</b>
						<span><?php esc_html_e( 'Days', 'super-progressive-web-apps' ); ?></span>
					</div>
					<div class="superpwa-bfcm-sep" aria-hidden="true">&middot;</div>
					<div class="superpwa-bfcm-unit">
						<b data-unit="hours">00</b>
						<span><?php esc_html_e( 'Hours', 'super-progressive-web-apps' ); ?></span>
					</div>
					<div class="superpwa-bfcm-sep" aria-hidden="true">&middot;</div>
					<div class="superpwa-bfcm-unit">
						<b data-unit="mins">00</b>
						<span><?php esc_html_e( 'Mins', 'super-progressive-web-apps' ); ?></span>
					</div>
					<div class="superpwa-bfcm-sep" aria-hidden="true">&middot;</div>
					<div class="superpwa-bfcm-unit">
						<b data-unit="secs">00</b>
						<span><?php esc_html_e( 'Secs', 'super-progressive-web-apps' ); ?></span>
					</div>
				</div>
			</div>
			<div class="superpwa-bfcm-cta">
				<a class="superpwa-bfcm-claim" href="<?php echo esc_url( $offer ); ?>" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e( 'Claim The Offer Now', 'super-progressive-web-apps' ); ?>
					<svg width="14" height="14" viewBox="0 0 12 12" aria-hidden="true" focusable="false">
						<path d="M6.8 1L2.2 6.7h3.1L4.6 11 9.8 4.8H6.5L6.8 1z" fill="#fff"/>
					</svg>
				</a>
				<span class="superpwa-bfcm-guarantee">
					<svg width="12" height="12" viewBox="0 0 12 12" aria-hidden="true" focusable="false">
						<rect x="2.2" y="5.2" width="7.6" height="5.4" rx="1" fill="none" stroke="currentColor" stroke-width="1.2"/>
						<path d="M4 5.2V3.7a2 2 0 0 1 4 0v1.5" fill="none" stroke="currentColor" stroke-width="1.2"/>
					</svg>
					<?php esc_html_e( '30-Day Money Back Guarantee', 'super-progressive-web-apps' ); ?>
				</span>
			</div>
		</div>
	</div>
	<script>
	(function () {
		var root = document.getElementById('superpwa-bfcm-banner');
		if (!root) {
			return;
		}
		var end = parseInt(root.getAttribute('data-end'), 10) * 1000;
		var units = {
			days: root.querySelector('[data-unit="days"]'),
			hours: root.querySelector('[data-unit="hours"]'),
			mins: root.querySelector('[data-unit="mins"]'),
			secs: root.querySelector('[data-unit="secs"]')
		};
		function pad(value) {
			return (value < 10 ? '0' : '') + value;
		}
		function tick() {
			var remaining = Math.max(0, end - Date.now());
			var total = Math.floor(remaining / 1000);
			units.days.textContent = pad(Math.floor(total / 86400));
			units.hours.textContent = pad(Math.floor((total % 86400) / 3600));
			units.mins.textContent = pad(Math.floor((total % 3600) / 60));
			units.secs.textContent = pad(total % 60);
			if (remaining <= 0) {
				root.style.display = 'none';
				window.clearInterval(timer);
			}
		}
		tick();
		var timer = window.setInterval(tick, 1000);

		var copyButton = root.querySelector('.superpwa-bfcm-copy');
		if (copyButton) {
			copyButton.addEventListener('click', function () {
				var code = 'BFCM2026';
				var copiedLabel = root.getAttribute('data-copied') || 'Copied';
				var copyLabel = root.getAttribute('data-copy') || 'Copy coupon code';
				function markCopied() {
					copyButton.classList.add('is-copied');
					copyButton.setAttribute('aria-label', copiedLabel);
					window.setTimeout(function () {
						copyButton.classList.remove('is-copied');
						copyButton.setAttribute('aria-label', copyLabel);
					}, 1600);
				}
				if (navigator.clipboard && navigator.clipboard.writeText) {
					navigator.clipboard.writeText(code).then(markCopied).catch(function () {
						fallbackCopy(code, markCopied);
					});
				} else {
					fallbackCopy(code, markCopied);
				}
			});
		}
		function fallbackCopy(code, done) {
			var field = document.createElement('textarea');
			field.value = code;
			field.setAttribute('readonly', '');
			field.style.position = 'absolute';
			field.style.left = '-9999px';
			document.body.appendChild(field);
			field.select();
			try {
				document.execCommand('copy');
				done();
			} catch (error) {}
			document.body.removeChild(field);
		}

		var dismissButton = root.querySelector('.superpwa-bfcm-dismiss');
		if (dismissButton) {
			dismissButton.addEventListener('click', function () {
				root.style.display = 'none';
				window.clearInterval(timer);
				var body = new window.FormData();
				body.append('action', 'superpwa_bfcm_banner_dismiss');
				body.append('superpwa_security_nonce', root.getAttribute('data-nonce') || '');
				window.fetch(root.getAttribute('data-ajax'), {
					method: 'POST',
					credentials: 'same-origin',
					body: body
				}).catch(function () {});
			});
		}
	})();
	</script>
	<?php
}

/**
 * Store a 7-day snooze for the current user.
 */
function superpwa_bfcm_banner_dismiss() {
	if ( isset( $_REQUEST['superpwa_security_nonce'] ) && current_user_can( superpwa_current_user_can() ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['superpwa_security_nonce'] ) ), 'superpwa_ajax_check_nonce' ) ) {
		update_user_meta( get_current_user_id(), 'superpwa_bfcm_dismissed_until', time() + WEEK_IN_SECONDS );
		echo wp_json_encode(
			array(
				'status'  => 200,
				'message' => esc_html__( 'Hidden for 7 days', 'super-progressive-web-apps' ),
			)
		);
	} else {
		echo wp_json_encode(
			array(
				'status'  => 403,
				'message' => esc_html__( 'Unauthorized Request', 'super-progressive-web-apps' ),
			)
		);
	}
	wp_die();
}
add_action( 'wp_ajax_superpwa_bfcm_banner_dismiss', 'superpwa_bfcm_banner_dismiss' );
