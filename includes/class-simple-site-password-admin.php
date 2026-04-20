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
	 * Settings page hook suffix.
	 *
	 * @var string
	 */
	private $hook_suffix = '';

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
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_filter( 'admin_body_class', array( $this, 'add_admin_body_class' ) );
	}

	/**
	 * Add settings page under Settings.
	 *
	 * @return void
	 */
	public function add_settings_page() {
		$this->hook_suffix = add_options_page(
			__( 'Simple Site Password', 'simple-site-password' ),
			__( 'Simple Site Password', 'simple-site-password' ),
			'manage_options',
			'simple-site-password',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Enqueue admin assets only on the plugin settings page.
	 *
	 * @param string $hook Current admin page hook.
	 * @return void
	 */
	public function enqueue_assets( $hook ) {
		if ( $hook !== $this->hook_suffix ) {
			return;
		}

		wp_enqueue_style(
			'simple-site-password-admin',
			SIMPLE_SITE_PASSWORD_URL . 'assets/css/admin.css',
			array(),
			SIMPLE_SITE_PASSWORD_VERSION
		);

		wp_enqueue_script(
			'simple-site-password-admin',
			SIMPLE_SITE_PASSWORD_URL . 'assets/js/admin.js',
			array(),
			SIMPLE_SITE_PASSWORD_VERSION,
			true
		);
	}

	/**
	 * Add a page-specific body class for scoped admin styling.
	 *
	 * @param string $classes Current body classes.
	 * @return string
	 */
	public function add_admin_body_class( $classes ) {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

		if ( $screen && 'settings_page_simple-site-password' === $screen->id ) {
			$classes .= ' simple-site-password-admin-page';
		}

		return $classes;
	}

	/**
	 * Render settings page.
	 *
	 * @return void
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'simple-site-password' ) );
		}

		$saved = false;
		$error = '';

		if ( isset( $_POST['simple_site_password_action'] ) ) {
			check_admin_referer( 'simple_site_password_save_settings', 'simple_site_password_nonce' );

			$result = $this->save_settings();
			$saved  = $result['saved'];
			$error  = $result['error'];
		}

		$options      = $this->options->get();
		$has_password = $this->options->has_password();
		$is_enabled   = ! empty( $options['enabled'] );
		$status_class = $is_enabled && $has_password ? 'wphubb-badge-active' : 'wphubb-badge-inactive';
		$status_text  = $is_enabled && $has_password
			? __( 'Active', 'simple-site-password' )
			: __( 'Inactive', 'simple-site-password' );
		?>
		<div class="wrap">
			<div class="wphubb-admin ssp-admin">
				<div class="wphubb-header">
					<div>
						<span class="wphubb-eyebrow"><?php echo esc_html__( 'WPHubb Plugin', 'simple-site-password' ); ?></span>
						<h1><?php echo esc_html__( 'Simple Site Password', 'simple-site-password' ); ?></h1>
						<p><?php echo esc_html__( 'Protect your WordPress site with a simple global password for visitors.', 'simple-site-password' ); ?></p>
					</div>
					<span class="wphubb-badge <?php echo esc_attr( $status_class ); ?>"><?php echo esc_html( $status_text ); ?></span>
				</div>

				<?php if ( $saved ) : ?>
					<div class="wphubb-notice wphubb-notice-success">
						<strong><?php echo esc_html__( 'Settings saved.', 'simple-site-password' ); ?></strong>
					</div>
				<?php endif; ?>

				<?php if ( '' !== $error ) : ?>
					<div class="wphubb-notice wphubb-notice-error">
						<strong><?php echo esc_html( $error ); ?></strong>
					</div>
				<?php endif; ?>

				<?php if ( $is_enabled && ! $has_password ) : ?>
					<div class="wphubb-notice wphubb-notice-warning">
						<strong><?php echo esc_html__( 'Protection is enabled, but no password is configured yet.', 'simple-site-password' ); ?></strong>
					</div>
				<?php endif; ?>

				<form method="post" action="">
					<?php wp_nonce_field( 'simple_site_password_save_settings', 'simple_site_password_nonce' ); ?>
					<input type="hidden" name="simple_site_password_action" value="save_settings" />

					<div class="wphubb-grid wphubb-grid-2">
						<div>
							<div class="wphubb-card">
								<h2><?php echo esc_html__( 'Status', 'simple-site-password' ); ?></h2>
								<p><?php echo esc_html__( 'Enable or disable the global password gate.', 'simple-site-password' ); ?></p>

								<label class="wphubb-toggle">
									<input type="checkbox" name="enabled" value="1" <?php checked( $options['enabled'] ); ?> />
									<span class="wphubb-toggle-slider"></span>
									<span><?php echo esc_html__( 'Enable global protection', 'simple-site-password' ); ?></span>
								</label>

								<div class="ssp-password-status">
									<?php if ( $has_password ) : ?>
										<span class="wphubb-badge wphubb-badge-active"><?php echo esc_html__( 'Password configured', 'simple-site-password' ); ?></span>
									<?php else : ?>
										<span class="wphubb-badge wphubb-badge-warning"><?php echo esc_html__( 'No password configured', 'simple-site-password' ); ?></span>
									<?php endif; ?>
								</div>
							</div>

							<div class="wphubb-card">
								<h2><?php echo esc_html__( 'Access', 'simple-site-password' ); ?></h2>
								<p><?php echo esc_html__( 'Set the password and access duration for visitors.', 'simple-site-password' ); ?></p>

								<div class="wphubb-field">
									<label for="ssp-password"><?php echo esc_html__( 'Password', 'simple-site-password' ); ?></label>

									<?php if ( $has_password ) : ?>
										<div class="ssp-current-password" id="ssp-current-password">
											<input class="wphubb-input" type="password" value="••••••••••••" disabled />
											<button type="button" class="wphubb-button wphubb-button-secondary ssp-change-password" data-ssp-change-password>
												<?php echo esc_html__( 'Change', 'simple-site-password' ); ?>
											</button>
										</div>
									<?php endif; ?>

									<div class="ssp-new-password <?php echo $has_password ? 'ssp-is-hidden' : ''; ?>" data-ssp-new-password-wrap>
										<div class="ssp-password-input-wrap">
											<input id="ssp-password" class="wphubb-input" type="password" name="password" value="" autocomplete="new-password" />
											<button type="button" class="wphubb-button wphubb-button-secondary ssp-toggle-password" data-ssp-toggle-password aria-controls="ssp-password">
												<?php echo esc_html__( 'Show', 'simple-site-password' ); ?>
											</button>
										</div>
									</div>

									<div class="wphubb-field-description">
										<?php echo esc_html__( 'Passwords are stored hashed, never in plain text. Leave the new password empty to keep the current one.', 'simple-site-password' ); ?>
									</div>
								</div>

								<div class="wphubb-field">
									<label for="ssp-cookie-duration"><?php echo esc_html__( 'Remember access for', 'simple-site-password' ); ?></label>
									<input id="ssp-cookie-duration" class="wphubb-input ssp-small-input" type="number" name="cookie_duration" value="<?php echo esc_attr( $options['cookie_duration'] ); ?>" min="1" max="720" />
									<div class="wphubb-field-description">
										<?php echo esc_html__( 'Duration in hours. Minimum 1 hour, maximum 720 hours.', 'simple-site-password' ); ?>
									</div>
								</div>

								<label class="wphubb-toggle">
									<input type="checkbox" name="allow_admins" value="1" <?php checked( $options['allow_admins'] ); ?> />
									<span class="wphubb-toggle-slider"></span>
									<span><?php echo esc_html__( 'Allow administrators to bypass protection', 'simple-site-password' ); ?></span>
								</label>
							</div>

							<div class="wphubb-card">
								<h2><?php echo esc_html__( 'Advanced', 'simple-site-password' ); ?></h2>
								<p><?php echo esc_html__( 'Control what happens when the plugin is uninstalled.', 'simple-site-password' ); ?></p>

								<label class="wphubb-toggle">
									<input type="checkbox" name="delete_on_uninstall" value="1" <?php checked( $options['delete_on_uninstall'] ); ?> />
									<span class="wphubb-toggle-slider"></span>
									<span><?php echo esc_html__( 'Delete settings on uninstall', 'simple-site-password' ); ?></span>
								</label>
							</div>
						</div>

						<div>
							<div class="wphubb-card">
								<h2><?php echo esc_html__( 'Design', 'simple-site-password' ); ?></h2>
								<p><?php echo esc_html__( 'Customize the password screen shown to visitors.', 'simple-site-password' ); ?></p>

								<div class="wphubb-field">
									<label for="ssp-template"><?php echo esc_html__( 'Template', 'simple-site-password' ); ?></label>
									<select id="ssp-template" class="wphubb-select" name="template">
										<option value="minimal" <?php selected( $options['template'], 'minimal' ); ?>><?php echo esc_html__( 'Minimal', 'simple-site-password' ); ?></option>
										<option value="dark" <?php selected( $options['template'], 'dark' ); ?>><?php echo esc_html__( 'Dark', 'simple-site-password' ); ?></option>
										<option value="gradient" <?php selected( $options['template'], 'gradient' ); ?>><?php echo esc_html__( 'Gradient', 'simple-site-password' ); ?></option>
									</select>
								</div>

								<div class="wphubb-field">
									<label for="ssp-title"><?php echo esc_html__( 'Title', 'simple-site-password' ); ?></label>
									<input id="ssp-title" class="wphubb-input" type="text" name="title" value="<?php echo esc_attr( $options['title'] ); ?>" />
								</div>

								<div class="wphubb-field">
									<label for="ssp-description"><?php echo esc_html__( 'Description', 'simple-site-password' ); ?></label>
									<textarea id="ssp-description" class="wphubb-textarea" name="description"><?php echo esc_textarea( $options['description'] ); ?></textarea>
								</div>

								<div class="wphubb-field">
									<label for="ssp-button-text"><?php echo esc_html__( 'Button text', 'simple-site-password' ); ?></label>
									<input id="ssp-button-text" class="wphubb-input" type="text" name="button_text" value="<?php echo esc_attr( $options['button_text'] ); ?>" />
								</div>

								<div class="wphubb-preview ssp-template-preview ssp-preview-<?php echo esc_attr( $options['template'] ); ?>" data-ssp-preview>
									<div class="ssp-preview-card">
										<h3 class="wphubb-preview-title" data-ssp-preview-title><?php echo esc_html( $options['title'] ); ?></h3>
										<p class="wphubb-preview-text" data-ssp-preview-description><?php echo esc_html( $options['description'] ); ?></p>
										<div class="ssp-preview-form">
											<span class="ssp-preview-input"><?php echo esc_html__( 'Password', 'simple-site-password' ); ?></span>
											<span class="ssp-preview-button" data-ssp-preview-button><?php echo esc_html( $options['button_text'] ); ?></span>
										</div>
									</div>
								</div>
							</div>

							<p class="submit ssp-submit">
								<button type="submit" class="wphubb-button wphubb-button-primary">
									<?php echo esc_html__( 'Save settings', 'simple-site-password' ); ?>
								</button>
							</p>
						</div>
					</div>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Save settings from POST.
	 *
	 * @return array{saved: bool, error: string}
	 */
	private function save_settings() {
		$current = $this->options->get();

		$new_password = isset( $_POST['password'] ) ? sanitize_text_field( wp_unslash( $_POST['password'] ) ) : '';

		$options = array(
			'enabled'             => ! empty( $_POST['enabled'] ),
			'password_hash'       => $current['password_hash'],
			'cookie_duration'     => isset( $_POST['cookie_duration'] ) ? absint( wp_unslash( $_POST['cookie_duration'] ) ) : 24,
			'allow_admins'        => ! empty( $_POST['allow_admins'] ),
			'template'            => isset( $_POST['template'] ) ? sanitize_key( wp_unslash( $_POST['template'] ) ) : 'minimal',
			'title'               => isset( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : '',
			'description'         => isset( $_POST['description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['description'] ) ) : '',
			'button_text'         => isset( $_POST['button_text'] ) ? sanitize_text_field( wp_unslash( $_POST['button_text'] ) ) : '',
			'delete_on_uninstall' => ! empty( $_POST['delete_on_uninstall'] ),
		);

		if ( '' !== $new_password ) {
			$options['password_hash'] = wp_hash_password( $new_password );
		}

		$this->options->update( $options );

		return array(
			'saved' => true,
			'error' => '',
		);
	}
}
