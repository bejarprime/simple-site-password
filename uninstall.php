<?php
/**
 * Uninstall Simple Site Password.
 *
 * @package SimpleSitePassword
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'simple_site_password_options' );

