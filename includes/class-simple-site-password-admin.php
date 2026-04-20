<?php
/**
 * Admin service.
 *
 * @package SimpleSitePassword
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles admin hooks.
 */
class Simple_Site_Password_Admin {

	/**
	 * Options service.
	 *
	 * @var Simple_Site_Password_Options
	 */
	private $options;

	/**
	 * Constructor.
	 *
	 * @param Simple_Site_Password_Options $options Options service.
	 */
	public function __construct( Simple_Site_Password_Options $options ) {
		$this->options = $options;
	}

	/**
	 * Register admin hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		// Admin UI will be implemented in the next phase.
	}
}
