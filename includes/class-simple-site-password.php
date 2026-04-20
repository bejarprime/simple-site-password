<?php
/**
 * Main plugin class.
 *
 * @package SimpleSitePassword
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin coordinator.
 */
final class Simple_Site_Password {

	/**
	 * Singleton instance.
	 *
	 * @var Simple_Site_Password|null
	 */
	private static $instance = null;

	/**
	 * Options service.
	 *
	 * @var Simple_Site_Password_Options
	 */
	private $options;

	/**
	 * Admin service.
	 *
	 * @var Simple_Site_Password_Admin
	 */
	private $admin;

	/**
	 * Frontend gate service.
	 *
	 * @var Simple_Site_Password_Gate
	 */
	private $gate;

	/**
	 * Get singleton instance.
	 *
	 * @return Simple_Site_Password
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->options = new Simple_Site_Password_Options();
		$this->admin   = new Simple_Site_Password_Admin( $this->options );
		$this->gate    = new Simple_Site_Password_Gate( $this->options );
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function run() {
		add_action( 'init', array( $this, 'load_textdomain' ) );

		if ( is_admin() ) {
			$this->admin->register_hooks();
		}

		$this->gate->register_hooks();
	}

	/**
	 * Load translations.
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'simple-site-password',
			false,
			dirname( SIMPLE_SITE_PASSWORD_BASENAME ) . '/languages'
		);
	}
}
