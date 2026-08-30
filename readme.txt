=== DVLNT Service Area Checker ===
Contributors: dvlnt
Tags: service area, zip code, modal, local business, brizy
Requires at least: 6.0
Requires PHP: 7.4
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A lightweight, accessible ZIP-code service-area checker with Brizy-compatible triggers.

== Installation ==

1. Upload the plugin folder to /wp-content/plugins/ or install its ZIP from Plugins > Add New.
2. Activate DVLNT Service Area Checker.
3. Configure ZIP codes, contact details, content, and appearance under Settings > Service Area Checker.
4. Add [service_area_checker] once on the page that needs the modal.
5. Add the CSS class service-area-trigger to any Brizy button or element that should open it.

Alternatively, use [service_area_button text="Check Your Area"] to render a ready-made trigger and the modal together.

== Frequently Asked Questions ==

= Are service ZIP codes exposed in the page source? =

No. The browser sends one nonce-protected ZIP request to WordPress AJAX, and the server checks the saved list.

= Which ZIP formats are supported? =

Version 1 accepts standard five-digit US ZIP codes. The admin list may be entered one per line, comma-separated, or mixed.

== Changelog ==

= 1.0.1 =
* Load Lato only with the frontend modal assets.
* Refine spacing across desktop, tablet, and mobile layouts.
* Keep modal controls reachable when mobile virtual keyboards open.
* Bump frontend asset versions to prevent stale cached CSS and JavaScript.

= 1.0.0 =
* Initial release.
