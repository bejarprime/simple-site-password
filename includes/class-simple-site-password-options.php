<?php
/**
 * Options service.
 *
 * @package SimpleSitePassword
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles plugin options and defaults.
 */
class Simple_Site_Password_Options {

	/**
	 * Main option name.
	 */
	const OPTION_NAME = 'simple_site_password_options';

	/**
	 * Allowed templates.
	 */
	const ALLOWED_TEMPLATES = array( 'minimal', 'dark', 'gradient' );

	/**
	 * Get default plugin options.
	 *
	 * @return array
	 */
	public static function defaults() {
		return array(
			'enabled'             => false,
			'password_hash'       => '',
			'cookie_duration'     => 24,
			'allow_admins'        => true,
			'template'            => 'minimal',
			'title'               => __( 'Protected Site', 'simple-site-password' ),
			'description'         => __( 'Enter the password to access this site.', 'simple-site-password' ),
			'button_text'         => __( 'Access', 'simple-site-password' ),
			'delete_on_uninstall' => false,
		);
	}

	/**
	 * Ensure the option exists with default values.
	 *
	 * @return void
	 */
	public static function ensure_defaults() {
		$existing = get_option( self::OPTION_NAME, null );

		if ( null === $existing ) {
			add_option( self::OPTION_NAME, self::defaults(), '', false );
			return;
		}

		update_option( self::OPTION_NAME, self::normalize( $existing ), false );
	}

	/**
	 * Get normalized options.
	 *
	 * @return array
	 */
	public function get() {
		$options = get_option( self::OPTION_NAME, array() );

		return self::normalize( $options );
	}

	/**
	 * Get a single option value.
	 *
	 * @param string $key Option key.
	 * @param mixed  $fallback Fallback value.
	 * @return mixed
	 */
	public function get_value( $key, $fallback = null ) {
		$options = $this->get();

		return array_key_exists( $key, $options ) ? $options[ $key ] : $fallback;
	}

	/**
	 * Update options.
	 *
	 * @param array $options Options to save.
	 * @return bool
	 */
	public function update( array $options ) {
		return update_option( self::OPTION_NAME, self::normalize( $options ), false );
	}

	/**
	 * Check if a password hash is configured.
	 *
	 * @return bool
	 */
	public function has_password() {
		return '' !== $this->get_value( 'password_hash', '' );
	}

	/**
	 * Normalize options against defaults.
	 *
	 * @param mixed $options Raw options.
	 * @return array
	 */
	public static function normalize( $options ) {
		$options  = is_array( $options ) ? $options : array();
		$defaults = self::defaults();
		$merged   = wp_parse_args( $options, $defaults );

		$merged['enabled']             = ! empty( $merged['enabled'] );
		$merged['password_hash']       = is_string( $merged['password_hash'] ) ? $merged['password_hash'] : '';
		$merged['cookie_duration']     = self::normalize_cookie_duration( $merged['cookie_duration'] );
		$merged['allow_admins']        = ! empty( $merged['allow_admins'] );
		$merged['template']            = self::normalize_template( $merged['template'] );
		$merged['title']               = sanitize_text_field( $merged['title'] );
		$merged['description']         = sanitize_textarea_field( $merged['description'] );
		$merged['button_text']         = sanitize_text_field( $merged['button_text'] );
		$merged['delete_on_uninstall'] = ! empty( $merged['delete_on_uninstall'] );

		return $merged;
	}

	/**
	 * Normalize cookie duration in hours.
	 *
	 * @param mixed $value Raw duration.
	 * @return int
	 */
	private static function normalize_cookie_duration( $value ) {
		$value = absint( $value );

		if ( $value < 1 ) {
			return 24;
		}

		if ( $value > 720 ) {
			return 720;
		}

		return $value;
	}

	/**
	 * Normalize template name.
	 *
	 * @param mixed $template Raw template.
	 * @return string
	 */
	private static function normalize_template( $template ) {
		$template = is_string( $template ) ? sanitize_key( $template ) : 'minimal';

		if ( ! in_array( $template, self::ALLOWED_TEMPLATES, true ) ) {
			return 'minimal';
		}

		return $template;
	}
}
