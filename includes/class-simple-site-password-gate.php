<?php
/**
 * Frontend gate service.
 *
 * @package SimpleSitePassword
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles frontend protection.
 */
class Simple_Site_Password_Gate {

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
	 * Register frontend hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		// Frontend gate will be implemented in a later phase.
	}
}
