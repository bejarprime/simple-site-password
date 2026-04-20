<?php
/**
 * Plugin Name: Simple Site Password
 * Plugin URI: https://example.com/simple-site-password
 * Description: Protege un sitio WordPress con una contraseña global sencilla para visitantes.
 * Version: 0.1.0
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

define( 'SIMPLE_SITE_PASSWORD_VERSION', '0.1.0' );
define( 'SIMPLE_SITE_PASSWORD_FILE', __FILE__ );
define( 'SIMPLE_SITE_PASSWORD_PATH', plugin_dir_path( __FILE__ ) );
define( 'SIMPLE_SITE_PASSWORD_URL', plugin_dir_url( __FILE__ ) );

/**
 * Placeholder inicial.
 *
 * La implementación real se añadirá en los siguientes pasos siguiendo SPEC.md.
 */
function simple_site_password_loaded() {
	// Scaffold inicial del plugin.
}
add_action( 'plugins_loaded', 'simple_site_password_loaded' );

