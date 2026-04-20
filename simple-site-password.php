<?php
/**
 * Plugin Name: Simple Site Password
 * Plugin URI: https://example.com/simple-site-password
 * Description: Protege un sitio WordPress con una contraseña global sencilla para visitantes.
 * Version: 0.1.2
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: WPHubb
 * Author URI: https://wphubb.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: simple-site-password
 * Domain Path: /languages
 *
 * @package SimpleSitePassword
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SIMPLE_SITE_PASSWORD_VERSION', '0.1.2' );
define( 'SIMPLE_SITE_PASSWORD_FILE', __FILE__ );
define( 'SIMPLE_SITE_PASSWORD_PATH', plugin_dir_path( __FILE__ ) );
define( 'SIMPLE_SITE_PASSWORD_URL', plugin_dir_url( __FILE__ ) );
define( 'SIMPLE_SITE_PASSWORD_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Load plugin classes.
 */
function simple_site_password_load_classes() {
	require_once SIMPLE_SITE_PASSWORD_PATH . 'includes/class-simple-site-password-options.php';
	require_once SIMPLE_SITE_PASSWORD_PATH . 'includes/class-simple-site-password-admin.php';
	require_once SIMPLE_SITE_PASSWORD_PATH . 'includes/class-simple-site-password-gate.php';
	require_once SIMPLE_SITE_PASSWORD_PATH . 'includes/class-simple-site-password.php';
}

/**
 * Bootstrap the plugin.
 */
function simple_site_password_bootstrap() {
	simple_site_password_load_classes();
	Simple_Site_Password::instance()->run();
}
add_action( 'plugins_loaded', 'simple_site_password_bootstrap' );

/**
 * Activation callback.
 */
function simple_site_password_activate() {
	simple_site_password_load_classes();
	Simple_Site_Password_Options::ensure_defaults();
}
register_activation_hook( __FILE__, 'simple_site_password_activate' );
