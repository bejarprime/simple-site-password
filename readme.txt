=== Simple Site Password ===
Contributors: wphubb
Tags: password, private site, privacy, access, maintenance
Requires at least: 6.0
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 0.1.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Protect your WordPress site with a simple global password for visitors.

== Description ==

Simple Site Password lets you protect an entire WordPress site with one global password.

It is designed for simple access restriction use cases:

* Development sites.
* Private client previews.
* Pre-launch landing pages.
* Temporary private websites.
* Internal or low-risk private pages.

The plugin shows a password screen to visitors before they can access the public site. After entering the correct password, access is remembered with a signed cookie for the configured duration.

Simple Site Password focuses on being small, clear and maintainable.

= Features =

* Enable or disable global password protection.
* Store the password as a hash, never in plain text.
* Remember access with a signed cookie.
* Configure cookie duration in hours.
* Allow administrators to bypass protection.
* Avoid blocking WordPress login, admin, AJAX, REST API, cron and WP-CLI.
* Choose between three templates: Minimal, Dark and Gradient.
* Customize title, description and button text.
* Live preview in the settings screen.
* Spanish translation included.
* Optional cleanup of settings on uninstall.

= Important limitation =

This plugin is not a replacement for:

* A membership plugin.
* A firewall.
* Role-based access control.
* Enterprise authentication.
* Advanced security for highly sensitive information.

It is intended for simple password-based access restriction.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`.
2. Activate the plugin from the Plugins screen.
3. Go to `Settings > Simple Site Password`.
4. Set a password.
5. Enable global protection.
6. Save settings.

== Frequently Asked Questions ==

= Is this a membership plugin? =

No. It is a simple global password gate for visitors.

= Does it replace proper authentication for sensitive data? =

No. It is intended for simple access restriction, not for highly sensitive information.

= Is the password stored in plain text? =

No. The password is stored as a hash using WordPress password functions.

= Can I view the current password after saving it? =

No. The current password cannot be revealed because it is stored as a one-way hash. You can replace it with a new password.

= Why can administrators bypass the password screen? =

Administrators can optionally bypass protection to avoid being locked out while managing the site. This can be disabled in the settings.

= Does the plugin block wp-admin or wp-login.php? =

No. The plugin intentionally avoids blocking WordPress login, admin, AJAX, REST API, cron and WP-CLI.

= Can I customize the password screen? =

Yes. You can choose between Minimal, Dark and Gradient templates and customize the title, description and button text.

= Does it support Spanish? =

Yes. The plugin includes Spanish translation files.

== Screenshots ==

1. Settings screen with global protection options.
2. Password screen using the Dark template.
3. Password screen using the Minimal template.
4. Password screen using the Gradient template.

== Changelog ==

= 0.1.4 =
* Add Spanish translation files and localized admin script strings.

= 0.1.3 =
* Improve admin password UX, template preview, and form controls.

= 0.1.2 =
* Improve Minimal template styling.

= 0.1.1 =
* Improve frontend gate styling and asset cache busting.

= 0.1.0 =
* Initial scaffold.

