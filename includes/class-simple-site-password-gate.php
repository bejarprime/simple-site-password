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
	 * Form action name.
	 */
	const ACTION_NAME = 'simple_site_password_unlock';

	/**
	 * Cookie name.
	 */
	const COOKIE_NAME = 'simple_site_password_access';

	/**
	 * Logout query argument.
	 */
	const LOGOUT_QUERY_ARG = 'simple_site_password_logout';

	/**
	 * Request error flag.
	 *
	 * @var string
	 */
	private $error = '';

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
		add_action( 'template_redirect', array( $this, 'maybe_protect_site' ), 0 );
	}

	/**
	 * Protect frontend requests when needed.
	 *
	 * @return void
	 */
	public function maybe_protect_site() {
		if ( $this->should_skip_request() ) {
			return;
		}

		$options = $this->options->get();

		if ( $this->is_logout_request() ) {
			$this->clear_access_cookie();
			wp_safe_redirect( home_url( '/' ) );
			exit;
		}

		if ( empty( $options['enabled'] ) || ! $this->options->has_password() ) {
			return;
		}

		if ( ! empty( $options['allow_admins'] ) && current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( $this->has_valid_access() ) {
			return;
		}

		if ( $this->is_unlock_request() ) {
			$this->handle_unlock_request( $options );
		}

		$this->render_gate( $options );
	}

	/**
	 * Determine if the current request should never be protected.
	 *
	 * @return bool
	 */
	private function should_skip_request() {
		if ( is_admin() || wp_doing_ajax() || wp_doing_cron() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
			return true;
		}

		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return true;
		}

		if ( function_exists( 'wp_is_json_request' ) && wp_is_json_request() ) {
			return true;
		}

		$script_name = isset( $_SERVER['SCRIPT_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SCRIPT_NAME'] ) ) : '';
		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';

		if ( false !== strpos( $script_name, 'wp-login.php' ) || false !== strpos( $request_uri, 'wp-login.php' ) ) {
			return true;
		}

		if ( false !== strpos( $request_uri, 'wp-admin' ) || false !== strpos( $request_uri, 'admin-ajax.php' ) ) {
			return true;
		}

		if ( 0 === strpos( trim( $request_uri, '/' ), 'wp-json' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check whether the visitor already has access.
	 *
	 * @return bool
	 */
	private function has_valid_access() {
		if ( empty( $_COOKIE[ self::COOKIE_NAME ] ) ) {
			return false;
		}

		$cookie = sanitize_text_field( wp_unslash( $_COOKIE[ self::COOKIE_NAME ] ) );
		$parts  = explode( '|', $cookie );

		if ( 3 !== count( $parts ) ) {
			return false;
		}

		list( $expires, $password_fingerprint, $signature ) = $parts;

		$expires = absint( $expires );

		if ( $expires < time() ) {
			return false;
		}

		$options = $this->options->get();

		if ( empty( $options['password_hash'] ) || ! hash_equals( $this->get_password_fingerprint( $options['password_hash'] ), $password_fingerprint ) ) {
			return false;
		}

		$expected = $this->create_cookie_signature( $expires, $password_fingerprint );

		return hash_equals( $expected, $signature );
	}

	/**
	 * Check if this request submitted the unlock form.
	 *
	 * @return bool
	 */
	private function is_unlock_request() {
		if ( 'POST' !== strtoupper( isset( $_SERVER['REQUEST_METHOD'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) : '' ) ) {
			return false;
		}

		$action = isset( $_POST['simple_site_password_action'] ) ? sanitize_key( wp_unslash( $_POST['simple_site_password_action'] ) ) : '';

		return self::ACTION_NAME === $action;
	}

	/**
	 * Handle password form submission.
	 *
	 * @param array $options Plugin options.
	 * @return void
	 */
	private function handle_unlock_request( array $options ) {
		$nonce = isset( $_POST['simple_site_password_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['simple_site_password_nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, self::ACTION_NAME ) ) {
			$this->error = __( 'The form expired. Please try again.', 'simple-site-password' );
			return;
		}

		$password = isset( $_POST['simple_site_password'] ) ? sanitize_text_field( wp_unslash( $_POST['simple_site_password'] ) ) : '';

		if ( '' === $password || empty( $options['password_hash'] ) || ! wp_check_password( $password, $options['password_hash'] ) ) {
			$this->error = __( 'Incorrect password. Please try again.', 'simple-site-password' );
			return;
		}

		$this->set_access_cookie( $options );

		wp_safe_redirect( $this->get_current_url() );
		exit;
	}

	/**
	 * Check if current request should clear the access cookie.
	 *
	 * @return bool
	 */
	private function is_logout_request() {
		return isset( $_GET[ self::LOGOUT_QUERY_ARG ] ) && '1' === sanitize_text_field( wp_unslash( $_GET[ self::LOGOUT_QUERY_ARG ] ) );
	}

	/**
	 * Set signed access cookie.
	 *
	 * @param array $options Plugin options.
	 * @return void
	 */
	private function set_access_cookie( array $options ) {
		$duration_hours       = isset( $options['cookie_duration'] ) ? absint( $options['cookie_duration'] ) : 24;
		$expires              = time() + ( max( 1, $duration_hours ) * HOUR_IN_SECONDS );
		$password_fingerprint = $this->get_password_fingerprint( $options['password_hash'] );
		$signature            = $this->create_cookie_signature( $expires, $password_fingerprint );
		$value                = $expires . '|' . $password_fingerprint . '|' . $signature;

		$this->set_cookie( self::COOKIE_NAME, $value, $expires );
		$_COOKIE[ self::COOKIE_NAME ] = $value;
	}

	/**
	 * Clear access cookie.
	 *
	 * @return void
	 */
	private function clear_access_cookie() {
		$this->set_cookie( self::COOKIE_NAME, '', time() - HOUR_IN_SECONDS );
		unset( $_COOKIE[ self::COOKIE_NAME ] );
	}

	/**
	 * Set cookie with secure defaults.
	 *
	 * @param string $name Cookie name.
	 * @param string $value Cookie value.
	 * @param int    $expires Expiration timestamp.
	 * @return void
	 */
	private function set_cookie( $name, $value, $expires ) {
		$path     = defined( 'COOKIEPATH' ) && COOKIEPATH ? COOKIEPATH : '/';
		$domain   = defined( 'COOKIE_DOMAIN' ) ? COOKIE_DOMAIN : '';
		$secure   = is_ssl();
		$httponly = true;

		setcookie(
			$name,
			$value,
			array(
				'expires'  => $expires,
				'path'     => $path,
				'domain'   => $domain,
				'secure'   => $secure,
				'httponly' => $httponly,
				'samesite' => 'Lax',
			)
		);
	}

	/**
	 * Create password fingerprint to invalidate cookies when password changes.
	 *
	 * @param string $password_hash Stored password hash.
	 * @return string
	 */
	private function get_password_fingerprint( $password_hash ) {
		return hash_hmac( 'sha256', $password_hash, wp_salt( 'auth' ) );
	}

	/**
	 * Create cookie signature.
	 *
	 * @param int    $expires Expiration timestamp.
	 * @param string $password_fingerprint Password fingerprint.
	 * @return string
	 */
	private function create_cookie_signature( $expires, $password_fingerprint ) {
		return hash_hmac( 'sha256', $expires . '|' . $password_fingerprint, wp_salt( 'secure_auth' ) );
	}

	/**
	 * Get current URL without unlock POST data.
	 *
	 * @return string
	 */
	private function get_current_url() {
		$host        = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : wp_parse_url( home_url(), PHP_URL_HOST );
		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '/';
		$scheme      = is_ssl() ? 'https' : 'http';
		$url         = $scheme . '://' . $host . $request_uri;

		return remove_query_arg( self::LOGOUT_QUERY_ARG, $url );
	}

	/**
	 * Render the password gate and stop WordPress rendering.
	 *
	 * @param array $options Plugin options.
	 * @return void
	 */
	private function render_gate( array $options ) {
		status_header( 200 );
		nocache_headers();

		$template = isset( $options['template'] ) ? sanitize_html_class( $options['template'] ) : 'minimal';
		$title    = isset( $options['title'] ) ? $options['title'] : __( 'Protected Site', 'simple-site-password' );
		$message  = isset( $options['description'] ) ? $options['description'] : __( 'Enter the password to access this site.', 'simple-site-password' );
		$button   = isset( $options['button_text'] ) ? $options['button_text'] : __( 'Access', 'simple-site-password' );

		$this->print_gate_document( $template, $title, $message, $button );
		exit;
	}

	/**
	 * Print standalone gate HTML document.
	 *
	 * @param string $template Template name.
	 * @param string $title Gate title.
	 * @param string $message Gate message.
	 * @param string $button Button text.
	 * @return void
	 */
	private function print_gate_document( $template, $title, $message, $button ) {
		$stylesheet_url = SIMPLE_SITE_PASSWORD_URL . 'assets/css/frontend.css?ver=' . rawurlencode( SIMPLE_SITE_PASSWORD_VERSION );
		?>
		<!doctype html>
		<html <?php language_attributes(); ?>>
		<head>
			<meta charset="<?php bloginfo( 'charset' ); ?>" />
			<meta name="viewport" content="width=device-width, initial-scale=1" />
			<meta name="robots" content="noindex,nofollow" />
			<title><?php echo esc_html( wp_strip_all_tags( $title ) ); ?></title>
			<link rel="stylesheet" href="<?php echo esc_url( $stylesheet_url ); ?>" />
			<?php wp_print_head_scripts(); ?>
		</head>
		<body class="ssp-gate ssp-template-<?php echo esc_attr( $template ); ?>">
			<main class="ssp-gate-shell" role="main">
				<section class="ssp-gate-card" aria-labelledby="ssp-gate-title">
					<div class="ssp-gate-brand"><?php echo esc_html__( 'Protected access', 'simple-site-password' ); ?></div>
					<h1 id="ssp-gate-title" class="ssp-gate-title"><?php echo esc_html( $title ); ?></h1>
					<p class="ssp-gate-description"><?php echo esc_html( $message ); ?></p>

					<?php if ( '' !== $this->error ) : ?>
						<div class="ssp-gate-error" role="alert"><?php echo esc_html( $this->error ); ?></div>
					<?php endif; ?>

					<form class="ssp-gate-form" method="post" action="">
						<?php wp_nonce_field( self::ACTION_NAME, 'simple_site_password_nonce' ); ?>
						<input type="hidden" name="simple_site_password_action" value="<?php echo esc_attr( self::ACTION_NAME ); ?>" />

						<label class="ssp-gate-label" for="simple-site-password-input">
							<?php echo esc_html__( 'Password', 'simple-site-password' ); ?>
						</label>
						<input id="simple-site-password-input" class="ssp-gate-input" type="password" name="simple_site_password" autocomplete="current-password" required autofocus />

						<button class="ssp-gate-button" type="submit"><?php echo esc_html( $button ); ?></button>
					</form>
				</section>
			</main>
			<?php wp_print_footer_scripts(); ?>
		</body>
		</html>
		<?php
	}
}
