<?php
/**
 * Uninstall Simple Site Password.
 *
 * @package SimpleSitePassword
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$options = get_option( 'simple_site_password_options', array() );

if ( is_array( $options ) && ! empty( $options['delete_on_uninstall'] ) ) {
	delete_option( 'simple_site_password_options' );
}
