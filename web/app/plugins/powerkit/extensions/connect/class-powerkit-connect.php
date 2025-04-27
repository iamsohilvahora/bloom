<?php
/**
 * Connect
 *
 * @package    Powerkit
 * @subpackage Extensions
 */

if ( class_exists( 'Powerkit_Module' ) ) {
	/**
	 * Init module
	 */
	class Powerkit_Connect extends Powerkit_Module {

		/**
		 * Register module
		 */
		public function register() {
			$this->name     = 'connect';
			$this->slug     = 'connect';
			$this->type     = 'extension';
			$this->category = 'basic';
			$this->public   = false;
			$this->enabled  = true;
		}

		/**
		 * Initialize module
		 */
		public function initialize() {
			add_action( 'powerkit_plugin_activation', array( $this, 'activation' ) );
			add_action( 'powerkit_plugin_deactivation', array( $this, 'deactivation' ) );
			add_action( 'event_access_token_refresh', array( $this, 'cron_access_token_refresh' ) );
			add_action( 'admin_menu', array( $this, 'register_options_page' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'wp_ajax_powerkit_reset_cache', array( $this, 'ajax_reset_cache' ) );
			add_action( 'wp_ajax_nopriv_powerkit_reset_cache', array( $this, 'ajax_reset_cache' ) );
		}

		/**
		 * Plugin activation
		 *
		 * @param bool $networkwide The networkwide.
		 */
		public function activation( $networkwide ) {
			if ( ! wp_next_scheduled( 'event_access_token_refresh' ) ) {
				wp_schedule_event( time(), 'daily', 'event_access_token_refresh' );
			}
		}

		/**
		 * Plugin activation
		 *
		 * @param bool $networkwide The networkwide.
		 */
		public function deactivation( $networkwide ) {
			wp_clear_scheduled_hook( 'event_access_token_refresh' );
		}

		/**
		 * Register admin page
		 *
		 * @since 1.0.0
		 */
		public function register_options_page() {
			add_options_page( esc_html__( 'Connect', 'powerkit' ), esc_html__( 'Connect', 'powerkit' ), 'manage_options', powerkit_get_page_slug( $this->slug ), array( $this, 'build_options_page' ) );
		}

		/**
		 * Build admin page
		 *
		 * @since 1.0.0
		 */
		public function build_options_page() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( 'You do not have sufficient rights to view this page.', 'powerkit' ) );
			}
			$this->logout_account();
			$this->save_options_page();
			$this->user_reset_cache();
			?>

				<div class="wrap">
					<h1><?php esc_html_e( 'Connect', 'powerkit' ); ?></h1>

					<div class="settings">
						<?php $connect_list = apply_filters( 'powerkit_register_connect_list', array() ); ?>

						<?php if ( $connect_list ) : ?>
							<div class="tabs">
								<?php
								$connect_list = array_values( $connect_list );

								$tab = sanitize_title( isset( $_GET['tab'] ) ? $_GET['tab'] : $connect_list[0]['id'] ); // Input var ok; sanitization ok.
								?>

								<nav class="nav-tab-wrapper woo-nav-tab-wrapper">
									<?php
									foreach ( $connect_list as $item ) {
										$class = ( $item['id'] === $tab ) ? 'nav-tab-active' : ''; // Input var ok.
										printf(
											'<a class="nav-tab %4$s" href="%1$s&tab=%2$s">%3$s</a>',
											esc_url( powerkit_get_page_url( $this->slug ) ),
											esc_attr( $item['id'] ),
											esc_html( $item['name'] ),
											esc_attr( $class )
										);
									}
									?>
								</nav>

								<?php
								foreach ( $connect_list as $item ) {
									// Instagram tab.
									if ( 'instagram' === $item['id'] && $item['id'] === $tab ) { // Input var ok.
										?>
											<div id="tab-<?php echo esc_attr( $item['id'] ); ?>" class="tab-wrap">
												<?php $this->instagram_custom_tab( $tab ); ?>
											</div>
										<?php
										// Facebook tab.
									} elseif ( 'facebook' === $item['id'] && $item['id'] === $tab ) { // Input var ok.
										?>
											<div id="tab-<?php echo esc_attr( $item['id'] ); ?>" class="tab-wrap">
												<?php $this->facebook_custom_tab( $tab ); ?>
											</div>
										<?php
										// Twitter tab.
									} elseif ( 'twitter' === $item['id'] && $item['id'] === $tab ) { // Input var ok.
										?>
											<div id="tab-<?php echo esc_attr( $item['id'] ); ?>" class="tab-wrap">
												<?php $this->twitter_custom_tab( $tab ); ?>
											</div>
										<?php
									} elseif ( $item['id'] === $tab ) {
										?>
										<form class="basic" method="post">
											<div id="tab-<?php echo esc_attr( $item['id'] ); ?>" class="tab-wrap">
												<!-- Render Fields -->
												<?php
												if ( isset( $item['fields'] ) && $item['fields'] ) {
													?>
													<table class="form-table">
														<tbody>
															<?php
															foreach ( $item['fields'] as $field ) {
																?>
																	<tr>
																		<th scope="row"><label class="title" for="<?php echo esc_attr( $field['key'] ); ?>"><?php echo esc_html( $field['caption'] ); ?></label></th>
																		<td>
																			<input class="regular-text" id="<?php echo esc_attr( $field['key'] ); ?>" name="<?php echo esc_attr( $field['key'] ); ?>" type="text" value="<?php echo esc_attr( powerkit_connect( $field['key'] ) ); ?>" />

																			<?php if ( isset( $field['instruction'] ) ) { ?>
																				<p><?php echo wp_kses_post( $field['instruction'] ); ?></p>
																			<?php } ?>
																		</td>
																	</tr>
																<?php
															}
															?>
														</tbody>
													</table>
													<?php
												}
												?>
											</div>

											<?php wp_nonce_field(); ?>

											<p class="submit">
												<input class="button button-primary" name="save_settings" type="submit" value="<?php esc_html_e( 'Save changes', 'powerkit' ); ?>" />
											</p>
										</form>
										<?php
									}
								}
								?>
							</div>

							<hr>

							<form method="post" class="form-reset">
								<?php wp_nonce_field(); ?>

								<p class="submit"><input class="button" name="powerkit_reset_cache" type="submit" value="<?php esc_html_e( 'Reset cache', 'powerkit' ); ?>" /></p>
							</form>
						<?php else : ?>
							<p class="submit">
								<?php esc_html_e( 'The list of social network settings is empty!!!', 'powerkit' ); ?>
							</p>
						<?php endif; ?>
					</div>
				</div>

				<style>
				.tab-badge-success {
					display: inline-block;
					border: 1px solid #46b450;
					padding: 0.5rem 0.75rem;
					color: #32373c;
					font-weight: 600;
					border-radius: 5px;
					margin-top: 1rem;
				}
				</style>
			<?php
		}

		/**
		 * Get API data
		 *
		 * @param mixed $type The type of data.
		 */
		public function api_get_data( $type ) {

			powerkit_uuid_hash();

			// Check wpnonce.
			if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'] ) ) { // Input var ok; sanitization ok.
				return;
			}

			if ( ! $type ) {
				return;
			}

			// Set types.
			$types = array();

			if ( is_string( $type ) ) {
				$types[] = $type;
			} elseif ( is_array( $type ) ) {
				$types = $type;
			}

			// Get data.
			if ( isset( $_REQUEST['api_data'] ) ) { // Input var ok; sanitization ok.

				$data = json_decode( base64_decode( $_REQUEST['api_data'] ), true );

				if ( ! is_array( $data ) ) {
					return;
				}

				if ( ! isset( $data['type'] ) || ! isset( $data['marker'] ) ) {
					return;
				}

				// Check type.
				if ( ! in_array( $data['type'], $types, true ) ) {
					return;
				}

				// Check marker.
				if ( get_transient( $data['marker'] ) ) {
					return;
				}

				// Add marker to DB.
				set_transient( $data['marker'], true, HOUR_IN_SECONDS );

				return $data;
			}
		}

		/**
		 * Get API message
		 *
		 * @param mixed $status The status of data.
		 */
		public function api_get_message( $status ) {

			$message = esc_html__( 'Something went wrong, please contact the plugin administrator.', 'powerkit' );

			// Check type status.
			if ( ! is_numeric( $status ) && is_string( $status ) ) {
				if ( empty( $status ) ) {
					$message = esc_html__( 'Failed to get status, please contact the plugin administrator.', 'powerkit' );
				}

				// If the status is a message.
				$message = $status;
			}

			// Common.
			if ( 200 === $status ) {
				$message = esc_html__( 'Successfully connected.', 'powerkit' );
			}
			if ( 201 === $status ) {
				$message = esc_html__( 'Successfully connected, please select a profile!', 'powerkit' );
			}
			if ( 401 === $status ) {
				$message = esc_html__( 'Access code not available.', 'powerkit' );
			}

			// Instagram.
			if ( 4101 === $status ) {
				$message = esc_html__( "Can't Get Short-Lived Token.", 'powerkit' );
			}
			if ( 4102 === $status ) {
				$message = esc_html__( "Can't Get Long-Lived Token.", 'powerkit' );
			}

			// Facebook.
			if ( 4201 === $status ) {
				$message = esc_html__( "Can't Get Short-Lived Token.", 'powerkit' );
			}
			if ( 4202 === $status ) {
				$message = esc_html__( "Can't Get Long-Lived Token.", 'powerkit' );
			}
			if ( 4203 === $status || 4204 === $status ) {
				$message = esc_html__( "Couldn't find Business Profile.", 'powerkit' );
			}
			if ( 4205 === $status || 4206 === $status ) {
				$message = sprintf( __( 'No Facebook pages linked to your Instagram Business account were found. Please link a public Facebook page to your Instagram Business account and connect again. See <a target="_blank" href="%s">this article</a> for details.', 'powerkit' ), 'https://help.instagram.com/399237934150902' );
			}

			// Twitter.
			if ( 4301 === $status ) {
				$message = esc_html__( "Can't Get Request Token.", 'powerkit' );
			}
			if ( 4302 === $status ) {
				$message = esc_html__( 'Something went wrong, try again or contact the plugin administrator.', 'powerkit' );
			}

			return $message;
		}

		/**
		 * Instagram custom tab
		 *
		 * @param string $tab The name of tab.
		 */
		public function instagram_custom_tab( $tab ) {

			$api_data = $this->api_get_data( array( 'instagram-connect', 'facebook-connect' ) );

			// Update connect settigs.
			if ( is_array( $api_data ) && ! empty( $api_data ) ) {
				$ig_status       = isset( $api_data['status'] ) && $api_data['status'] ? $api_data['status'] : null;
				$ig_access_token = isset( $api_data['access_token'] ) && $api_data['access_token'] ? $api_data['access_token'] : null;
				$ig_user_id      = isset( $api_data['user_id'] ) && $api_data['user_id'] ? $api_data['user_id'] : null;
				$ig_username     = isset( $api_data['username'] ) && $api_data['username'] ? $api_data['username'] : null;

				if ( ( 200 === $ig_status ) && $ig_access_token && $ig_user_id ) {
					update_option( 'powerkit_connect_instagram_app_type', 'instagram-connect' === $api_data['type'] ? 'personal' : 'business' );
					update_option( 'powerkit_connect_instagram_app_access_token', $ig_access_token );
					update_option( 'powerkit_connect_instagram_app_user_id', $ig_user_id );
					update_option( 'powerkit_connect_instagram_app_username', $ig_username ? $ig_username : $ig_user_id );
					update_option( 'powerkit_connect_instagram_app_refresh_time', time() + ( DAY_IN_SECONDS * 30 ) );

					$this->location_reset_cache( 'instagram' );
				}
			}

			// Notice.
			if ( isset( $ig_status ) && ( 200 === $ig_status || 201 === $ig_status ) ) {
				?>
					<div class="notice notice-success is-dismissible">
						<p><?php echo wp_kses_post( wp_unslash( $this->api_get_message( $ig_status ) ) ); ?></p>
					</div>
				<?php
			} elseif ( isset( $ig_status ) && $ig_status ) {
				?>
					<div class="notice notice-error is-dismissible">
						<p><?php echo wp_kses_post( wp_unslash( $this->api_get_message( $ig_status ) ) ); ?></p>
					</div>
				<?php
			}
			?>

			<?php
			// Select Business Profile.
			if ( is_array( $api_data ) && ! empty( $api_data ) && 'facebook-connect' === $api_data['type'] ) {
				$ig_type         = isset( $api_data['type'] ) && $api_data['type'] ? $api_data['type'] : null;
				$ig_status       = isset( $api_data['status'] ) && $api_data['status'] ? $api_data['status'] : null;
				$ids_business    = isset( $api_data['ids_business'] ) && $api_data['ids_business'] ? $api_data['ids_business'] : null;
				$ig_access_token = isset( $api_data['access_token'] ) && $api_data['access_token'] ? $api_data['access_token'] : null;

				if ( is_array( $ids_business ) && 1 === count( $ids_business ) ) {

					$profile = reset( $ids_business );

					update_option( 'powerkit_connect_instagram_app_type', 'business' );
					update_option( 'powerkit_connect_instagram_app_access_token', $ig_access_token );
					update_option( 'powerkit_connect_instagram_app_user_id', $profile['id'] );
					update_option( 'powerkit_connect_instagram_app_username', $profile['username'] );
					update_option( 'powerkit_connect_instagram_app_refresh_time', time() + ( DAY_IN_SECONDS * 30 ) );

					$this->location_reset_cache( 'instagram' );

				} elseif ( is_array( $ids_business ) && $ids_business ) {
					$form_business = true;
					?>
					<form method="post" id="form_business">
						<?php wp_nonce_field(); ?>

						<h3><?php esc_html_e( 'Instagram Business Profiles for This Account', 'powerkit' ); ?></h3>

						<p><?php esc_html_e( 'Please select the profile that you’d like to connect:', 'powerkit' ); ?></p>

						<?php
						$id_counter = 0;
						foreach ( $ids_business as $item ) {
							$id_counter++;

							$data = array(
								'status'       => 200,
								'type'         => $ig_type,
								'access_token' => $ig_access_token,
								'user_id'      => $item['id'],
								'username'     => $item['username'],
							);

							// Generate marker.
							$data['marker'] = md5( uniqid( 'connect', true ) );

							// Set API data.
							$api_data = base64_encode( json_encode( $data ) );
							?>
							<label>
								<input type="radio" name="api_data" <?php checked( $id_counter, 1 ); ?> value="<?php echo esc_attr( $api_data ); ?>">

								<?php echo esc_attr( $item['name'] ); ?> (<?php echo esc_attr( $item['id'] ); ?>)
							</label>
							<?php
						}
						?>
						<p class="submit">
							<input class="button button-primary" id="save_business" type="submit" value="<?php esc_html_e( 'Connect', 'powerkit' ); ?>" />
						</p>
					</form>

					<script>
					window.onbeforeunload = function(e) {
						return '<?php esc_html_e( 'You need to select a Business profile', 'powerkit' ); ?>';
					};

					document.getElementById( 'form_business' ).onsubmit = function(e) {
						window.onbeforeunload = null;

						return true;
					};
					</script>
					<?php
				}
			}
			?>

			<?php
			// Display information or sign buttons.
			if ( powerkit_connect( 'instagram_app_access_token' ) ) {
				?>

				<?php if ( 'business' === powerkit_connect( 'instagram_app_type' ) ) { ?>
					<h3><?php esc_html_e( 'Business Instagram Account', 'powerkit' ); ?></h3>
				<?php } else { ?>
					<h3><?php esc_html_e( 'Personal Instagram Account', 'powerkit' ); ?></h3>
				<?php } ?>

				<p><span class="tab-badge-success"><?php esc_html_e( '✓ Account', 'powerkit' ); ?> (<a href="https://www.instagram.com/<?php echo esc_attr( powerkit_connect( 'instagram_app_username' ) ); ?>" target="_blank"><?php echo esc_attr( powerkit_connect( 'instagram_app_username' ) ); ?></a>) <?php esc_html_e( 'successfully connected', 'powerkit' ); ?></span></p>

				<p><?php esc_html_e( 'Your Instagram User ID:', 'powerkit' ); ?> <code><?php echo esc_attr( powerkit_connect( 'instagram_app_username' ) ); ?></code> <?php esc_html_e( 'Please use this ID in settings when requested.', 'powerkit' ); ?></p>

				<form method="post" class="form-logout">
					<?php wp_nonce_field(); ?>

					<input type="hidden" name="logout_account_type" value="instagram">

					<p class="submit">
						<input class="button button-primary" name="logout_account" type="submit" value="<?php esc_html_e( 'Disconnect', 'powerkit' ); ?>" />
					</p>
				</form>

			<?php } elseif ( ! isset( $form_business ) ) { ?>

				<h3><?php esc_html_e( 'Personal Account', 'powerkit' ); ?></h3>

				<div class="notice inline">
					<p><?php echo sprintf( __( 'The number of followers, likes, comments and profile image are not available to Instagram personal accounts. Please add these <a href="%s" target="_blank">manually</a> in the Manual Settings.', 'powerkit' ), admin_url( 'options-general.php?page=powerkit_social_links' ) ); ?></p>
				</div>

				<p><?php esc_html_e( 'Used for displaying user feeds from a Personal Instagram account.', 'powerkit' ); ?></p>

				<?php
					$app_authorize = add_query_arg(
						array(
							'app_id'        => powerkit_connect( 'instagram_app_id' ),
							'redirect_uri'  => rawurlencode( powerkit_connect( 'instagram_app_url' ) ),
							'response_type' => 'code',
							'scope'         => 'user_profile,user_media',
							'state'         => base64_encode( sprintf( '%s&tab=%s', powerkit_get_page_url( $this->slug ), $tab ) ),
						),
						'https://www.instagram.com/oauth/authorize'
					);
				?>

				<p>
					<a class="button button-primary" href="<?php echo esc_url( $app_authorize ); ?>">
						<?php esc_html_e( 'Connect', 'powerkit' ); ?>
					</a>
				</p>

				<hr>

				<h3><?php esc_html_e( 'Business Account', 'powerkit' ); ?></h3>

				<div class="notice inline">
					<p><?php echo sprintf( __( 'Please make sure you link your Instagram Business account to a Facebook page. This is required to display number of followers, likes, comments and profile image. Please follow <a href="%s" target="_blank">this link</a> to learn more about linking your Instagram account to a Facebook page.', 'powerkit' ), 'https://help.instagram.com/399237934150902' ); ?></p>
				</div>

				<p><?php esc_html_e( 'Used for displaying user feeds from an Instagram Business or Creator account.', 'powerkit' ); ?></p>

				<?php
					$app_authorize = add_query_arg(
						array(
							'client_id'    => powerkit_connect( 'instagram_app_fb_client_id' ),
							'redirect_uri' => rawurlencode( powerkit_connect( 'instagram_app_fb_url' ) ),
							'scope'        => 'instagram_basic',
							'state'        => base64_encode( sprintf( '%s&tab=%s', powerkit_get_page_url( $this->slug ), $tab ) ),
						),
						'https://www.facebook.com/dialog/oauth'
					);
				?>

				<p>
					<a class="button button-primary" href="<?php echo esc_url( $app_authorize ); ?>">
						<?php esc_html_e( 'Connect', 'powerkit' ); ?>
					</a>
				</p>
				<?php
			}
			?>

			<hr>

			<form class="basic" method="post">
				<h3><?php esc_html_e( 'Manual Settings', 'powerkit' ); ?></h3>

				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">
								<label for="powerkit_connect_instagram_following" class="title">
									<?php esc_html_e( 'Following', 'powerkit' ); ?>
								</label>
							</th>
							<td>
								<input class="regular-text" name="powerkit_connect_instagram_following" id="powerkit_connect_instagram_following" type="number" value="<?php echo esc_attr( get_option( 'powerkit_connect_instagram_following' ) ); ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="powerkit_connect_instagram_custom_followers" class="title">
									<?php esc_html_e( 'Followers', 'powerkit' ); ?>
								</label>
							</th>
							<td>
								<input class="regular-text" name="powerkit_connect_instagram_custom_followers" id="powerkit_connect_instagram_custom_followers" type="number" value="<?php echo esc_attr( get_option( 'powerkit_connect_instagram_custom_followers' ) ); ?>" />
								<p class="description">
									<?php esc_html_e( 'The number of followers is automatically retrieved from Instagram for Business accounts.', 'powerkit' ); ?>
									<br>
									<?php echo sprintf( __( 'Please note, this value is displayed in the Instagram feed only. You may change the number of followers in Social Links on <a href="%s" target="_blank">this page</a>.', 'powerkit' ), admin_url( 'options-general.php?page=powerkit_social_links' ) ); ?>
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="powerkit_connect_instagram_custom_name" class="title">
									<?php esc_html_e( 'Name', 'powerkit' ); ?>
								</label>
							</th>
							<td>
								<input class="regular-text" name="powerkit_connect_instagram_custom_name" id="powerkit_connect_instagram_custom_name" type="text" value="<?php echo esc_attr( get_option( 'powerkit_connect_instagram_custom_name' ) ); ?>" />
								<p class="description"><?php esc_html_e( 'Name is automatically retrieved from Instagram for Business accounts.', 'powerkit' ); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="powerkit_connect_instagram_custom_avatar" class="title">
									<?php esc_html_e( 'Profile Image', 'powerkit' ); ?>
								</label>
							</th>
							<td>
								<input placeholder="https://example.com/avatar.jpg" class="regular-text" name="powerkit_connect_instagram_custom_avatar" id="powerkit_connect_instagram_custom_avatar" type="text" value="<?php echo esc_attr( get_option( 'powerkit_connect_instagram_custom_avatar' ) ); ?>" />
								<p class="description"><?php esc_html_e( 'Avatar is automatically retrieved from Instagram for Business accounts.', 'powerkit' ); ?></p>
							</td>
						</tr>
					</tbody>
				</table>

				<?php wp_nonce_field(); ?>

				<p class="submit">
					<input class="button button-primary" name="save_instagram_settings" type="submit" value="<?php esc_html_e( 'Save settings', 'powerkit' ); ?>" />
				</p>
			</form>
			<?php
		}

		/**
		 * Facebook custom tab
		 *
		 * @param string $tab The name of tab.
		 */
		public function facebook_custom_tab( $tab ) {

			$api_data = $this->api_get_data( 'facebook-connect' );

			// Update connect settigs.
			if ( is_array( $api_data ) && ! empty( $api_data ) && 'facebook-connect' === $api_data['type'] ) {
				$fb_status       = isset( $api_data['status'] ) && $api_data['status'] ? $api_data['status'] : null;
				$fb_content      = isset( $api_data['content'] ) && $api_data['content'] ? $api_data['content'] : null;
				$fb_access_token = isset( $api_data['access_token'] ) && $api_data['access_token'] ? $api_data['access_token'] : null;
				$fb_accounts     = isset( $api_data['ids_accounts'] ) && $api_data['ids_accounts'] ? $api_data['ids_accounts'] : array();

				if ( 200 === $fb_status && $fb_access_token ) {
					update_option( 'powerkit_connect_facebook_app_access_token', $fb_access_token );
					update_option( 'powerkit_connect_facebook_app_accounts', $fb_accounts );
					update_option( 'powerkit_connect_facebook_app_refresh', true );

					$this->location_reset_cache( 'facebook' );
				}
			}

			// Notice.
			if ( isset( $fb_status ) && ( 200 === $fb_status ) ) {
				?>
					<div class="notice notice-success is-dismissible">
						<p><?php echo wp_kses_post( wp_unslash( $this->api_get_message( $fb_status ) ) ); ?></p>
					</div>
				<?php
			} elseif ( isset( $fb_status ) && $fb_status ) {
				?>
					<div class="notice notice-error is-dismissible">
						<p><?php echo wp_kses_post( wp_unslash( $this->api_get_message( $fb_status ) ) ); ?></p>
					</div>
				<?php
			}
			?>

			<?php
			// Display information or sign buttons.
			if ( powerkit_connect( 'facebook_app_access_token' ) ) {
				?>

				<h3><?php esc_html_e( 'Facebook Account', 'powerkit' ); ?></h3>

				<p><span class="tab-badge-success"><?php esc_html_e( '✓ Account successfully connected', 'powerkit' ); ?></span></p>

				<?php
				$accounts = powerkit_connect( 'facebook_app_accounts' );

				if ( $accounts ) {
					$usernames = array();

					foreach ( $accounts as $account ) {
						if ( isset( $account['username'] ) ) {
							$usernames[] = sprintf( '<code>%s</code>', $account['username'] );
						} else {
							$usernames[] = sprintf( '<code>%s</code>', $account['id'] );
						}
					}

					if ( 1 === count( $usernames ) ) {
						?>
						<p><?php esc_html_e( 'Your Facebook page ID:', 'powerkit' ); ?> <?php echo wp_kses_post( implode( ', ', $usernames ) ); ?><?php esc_html_e( '. Please use this ID in settings when requested.', 'powerkit' ); ?></p>
						<?php
					} else {
						?>
						<p><?php esc_html_e( 'Available Facebook page IDs:', 'powerkit' ); ?> <?php echo wp_kses_post( implode( ', ', $usernames ) ); ?><?php esc_html_e( '. Please use one of these IDs in settings when requested.', 'powerkit' ); ?></p>
						<?php

					}
				}
				?>

				<form method="post" class="form-logout">
					<?php wp_nonce_field(); ?>

					<input type="hidden" name="logout_account_type" value="facebook">

					<p class="submit">
						<input class="button button-primary" name="logout_account" type="submit" value="<?php esc_html_e( 'Disconnect', 'powerkit' ); ?>" />
					</p>
				</form>
			<?php } else { ?>

					<h3><?php esc_html_e( 'Facebook Account', 'powerkit' ); ?></h3>

					<p><?php echo sprintf( __( 'Connect your Facebook account to automatically fetch the number of followers of your Facebook page. You may also change this number manually on <a href="%s" target="_blank">this page</a>.', 'powerkit' ), admin_url( 'options-general.php?page=powerkit_social_links' ) ); ?></p>

					<?php
						$app_authorize = add_query_arg(
							array(
								'client_id'    => powerkit_connect( 'facebook_app_id' ),
								'redirect_uri' => rawurlencode( powerkit_connect( 'facebook_app_url' ) ),
								'state'        => base64_encode( sprintf( '%s&tab=%s', powerkit_get_page_url( $this->slug ), $tab ) ),
							),
							'https://www.facebook.com/dialog/oauth'
						);
					?>

					<p>
						<a class="button button-primary" href="<?php echo esc_url( $app_authorize ); ?>">
							<?php esc_html_e( 'Connect', 'powerkit' ); ?>
						</a>
					</p>
				<?php
			}
		}

		/**
		 * Facebook custom tab
		 *
		 * @param string $tab The name of tab.
		 */
		public function twitter_custom_tab( $tab ) {

			$api_data = $this->api_get_data( 'twitter-connect' );

			// Update connect settigs.
			if ( is_array( $api_data ) && ! empty( $api_data ) && 'twitter-connect' === $api_data['type'] ) {
				$tw_status             = isset( $api_data['status'] ) && $api_data['status'] ? $api_data['status'] : null;
				$tw_user_id            = isset( $api_data['user_id'] ) && $api_data['user_id'] ? $api_data['user_id'] : null;
				$tw_screen_name        = isset( $api_data['screen_name'] ) && $api_data['screen_name'] ? $api_data['screen_name'] : null;
				$tw_oauth_token        = isset( $api_data['oauth_token'] ) && $api_data['oauth_token'] ? $api_data['oauth_token'] : null;
				$tw_oauth_token_secret = isset( $api_data['oauth_token_secret'] ) && $api_data['oauth_token_secret'] ? $api_data['oauth_token_secret'] : null;
				$tw_consumer_key       = isset( $api_data['consumer_key'] ) && $api_data['consumer_key'] ? $api_data['consumer_key'] : null;
				$tw_consumer_secret    = isset( $api_data['consumer_secret'] ) && $api_data['consumer_secret'] ? $api_data['consumer_secret'] : null;

				if ( 200 === $tw_status && $tw_oauth_token && $tw_oauth_token_secret ) {
					update_option( 'powerkit_connect_twitter_app_consumer_key', $tw_consumer_key );
					update_option( 'powerkit_connect_twitter_app_consumer_secret', $tw_consumer_secret );
					update_option( 'powerkit_connect_twitter_app_user_id', $tw_user_id );
					update_option( 'powerkit_connect_twitter_app_screen_name', $tw_screen_name );
					update_option( 'powerkit_connect_twitter_app_oauth_token', $tw_oauth_token );
					update_option( 'powerkit_connect_twitter_app_oauth_token_secret', $tw_oauth_token_secret );

					$this->location_reset_cache( 'twitter' );
				}
			}

			// Notice.
			if ( isset( $tw_status ) && ( 200 === $tw_status ) ) {
				?>
					<div class="notice notice-success is-dismissible">
						<p><?php echo wp_kses_post( wp_unslash( $this->api_get_message( $tw_status ) ) ); ?></p>
					</div>
				<?php
			} elseif ( isset( $tw_status ) && $tw_status ) {
				?>
					<div class="notice notice-error is-dismissible">
						<p><?php echo wp_kses_post( wp_unslash( $this->api_get_message( $tw_status ) ) ); ?></p>
					</div>
				<?php
			}
			?>

			<?php
			// Display information or sign buttons.
			if ( powerkit_connect( 'twitter_app_oauth_token' ) ) {
				?>

				<h3><?php esc_html_e( 'Twitter Account', 'powerkit' ); ?></h3>

				<p><span class="tab-badge-success"><?php esc_html_e( '✓ Account', 'powerkit' ); ?> (<a href="https://twitter.com/<?php echo esc_attr( powerkit_connect( 'twitter_app_screen_name' ) ); ?>" target="_blank"><?php echo esc_attr( powerkit_connect( 'twitter_app_screen_name' ) ); ?></a>) <?php esc_html_e( 'successfully connected', 'powerkit' ); ?></span></p>

				<p><?php esc_html_e( 'Your Twitter User ID:', 'powerkit' ); ?> <code><?php echo esc_attr( powerkit_connect( 'twitter_app_screen_name' ) ); ?></code> <?php esc_html_e( 'Please use this ID in settings when requested.', 'powerkit' ); ?></p>

				<form method="post" class="form-logout">
					<?php wp_nonce_field(); ?>

					<input type="hidden" name="logout_account_type" value="twitter">

					<p class="submit">
						<input class="button button-primary" name="logout_account" type="submit" value="<?php esc_html_e( 'Disconnect', 'powerkit' ); ?>" />
					</p>
				</form>
			<?php } else { ?>
					<h3><?php esc_html_e( 'Twitter Account', 'powerkit' ); ?></h3>

					<p><?php echo sprintf( __( 'Connect your Twitter account to display your Twitter feed and the number of followers in Social Links. You may also change the number of followers manually on <a href="%s" target="_blank">this page</a>.', 'powerkit' ), admin_url( 'options-general.php?page=powerkit_social_links' ) ); ?></p>

					<?php
						$app_authorize = add_query_arg(
							array(
								'state' => rawurlencode( sprintf( '%s&tab=%s', powerkit_get_page_url( $this->slug ), $tab ) ),
							),
							powerkit_connect( 'twitter_app_url' )
						);
					?>

					<p>
						<a class="button button-primary" href="<?php echo esc_url( $app_authorize ); ?>">
							<?php esc_html_e( 'Connect', 'powerkit' ); ?>
						</a>
					</p>
				<?php
			}
		}

		/**
		 * Settings save
		 */
		public function save_options_page() {
			if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'] ) ) { // Input var ok; sanitization ok.
				return;
			}

			if ( isset( $_POST['save_settings'] ) ) { // Input var ok.
				// Save social params.
				$connect_list = apply_filters( 'powerkit_register_connect_list', array() );
				foreach ( $connect_list as $item ) {
					$id = $item['id'];
					if ( isset( $item['fields'] ) && $item['fields'] ) {
						foreach ( $item['fields'] as $field ) {
							if ( isset( $_POST[ $field['key'] ] ) ) { // Input var ok.
								update_option( $field['key'], sanitize_text_field( wp_unslash( $_POST[ $field['key'] ] ) ) ); // Input var ok.
							}
						}
					}
				}

				self::reset_cache();

				printf( '<div id="message" class="updated fade"><p><strong>%s</strong></p></div>', esc_html__( 'The settings are saved.', 'powerkit' ) );
			}

			if ( isset( $_POST['save_instagram_settings'] ) ) { // Input var ok.
				// Custom fields.
				if ( isset( $_POST['powerkit_connect_instagram_following'] ) ) { // Input var ok.
					update_option( 'powerkit_connect_instagram_following', sanitize_text_field( wp_unslash( $_POST['powerkit_connect_instagram_following'] ) ) ); // Input var ok.
				}
				if ( isset( $_POST['powerkit_connect_instagram_custom_followers'] ) ) { // Input var ok.
					update_option( 'powerkit_connect_instagram_custom_followers', sanitize_text_field( wp_unslash( $_POST['powerkit_connect_instagram_custom_followers'] ) ) ); // Input var ok.
				}
				if ( isset( $_POST['powerkit_connect_instagram_custom_name'] ) ) { // Input var ok.
					update_option( 'powerkit_connect_instagram_custom_name', sanitize_text_field( wp_unslash( $_POST['powerkit_connect_instagram_custom_name'] ) ) ); // Input var ok.
				}
				if ( isset( $_POST['powerkit_connect_instagram_custom_avatar'] ) ) { // Input var ok.
					update_option( 'powerkit_connect_instagram_custom_avatar', sanitize_text_field( wp_unslash( $_POST['powerkit_connect_instagram_custom_avatar'] ) ) ); // Input var ok.
				}

				$this->location_reset_cache( 'instagram' );

				printf( '<div id="message" class="updated fade"><p><strong>%s</strong></p></div>', esc_html__( 'The settings are saved.', 'powerkit' ) );
			}
		}

		/**
		 * Logout account
		 *
		 * @since 1.0.0
		 */
		public function logout_account() {
			if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'] ) ) { // Input var ok; sanitization ok.
				return;
			}

			if ( isset( $_POST['logout_account'] ) ) { // Input var ok.

				// Logout Instagram.
				if ( isset( $_POST['logout_account_type'] ) && 'instagram' === $_POST['logout_account_type'] ) {
					delete_option( 'powerkit_connect_instagram_app_type' );
					delete_option( 'powerkit_connect_instagram_app_access_token' );
					delete_option( 'powerkit_connect_instagram_app_user_id' );
					delete_option( 'powerkit_connect_instagram_app_username' );
					delete_option( 'powerkit_connect_instagram_app_refresh_time' );
				}

				// Logout Facebook.
				if ( isset( $_POST['logout_account_type'] ) && 'facebook' === $_POST['logout_account_type'] ) {
					delete_option( 'powerkit_connect_facebook_app_access_token' );
					delete_option( 'powerkit_connect_facebook_app_accounts' );
				}

				// Logout Facebook.
				if ( isset( $_POST['logout_account_type'] ) && 'twitter' === $_POST['logout_account_type'] ) {
					delete_option( 'powerkit_connect_twitter_app_consumer_key' );
					delete_option( 'powerkit_connect_twitter_app_consumer_secret' );
					delete_option( 'powerkit_connect_twitter_app_user_id' );
					delete_option( 'powerkit_connect_twitter_app_screen_name' );
					delete_option( 'powerkit_connect_twitter_app_oauth_token' );
					delete_option( 'powerkit_connect_twitter_app_oauth_token_secret' );
				}

				if ( isset( $_POST['logout_account_type'] ) ) {
					$this->location_reset_cache( $_POST['logout_account_type'] );
				}

				printf( '<div id="message" class="updated fade"><p><strong>%s</strong></p></div>', esc_html__( 'Account disabled successfully.', 'powerkit' ) );
			}
		}

		/**
		 * Tab reset cache
		 *
		 * @param string $slug Slug of tab.
		 */
		public function location_reset_cache( $slug ) {

			// Base.
			$list = array(
				'powerkit_social_follow',
				'powerkit_social_links_counter',
			);

			// Instagram.
			if ( 'instagram' === $slug ) {
				$list = array_merge(
					$list,
					array(
						'powerkit_instagram_data',
						'powerkit_instagram_recent',
					)
				);

			}

			// Twitter.
			if ( 'twitter' === $slug ) {
				$list = array_merge(
					$list,
					array(
						'powerkit_twitter_data',
						'powerkit_twitter_block_cache',
						'powerkit_twitter_shortcode_cache',
						'powerkit_twitter_widget_cache',
					)
				);
			}

			self::reset_cache( $list );
		}

		/**
		 * User reset cache
		 *
		 * @param bool $forcibly Force clear the cache.
		 */
		public function user_reset_cache( $forcibly = false ) {
			if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'] ) ) { // Input var ok; sanitization ok.
				return;
			}

			if ( isset( $_POST['powerkit_reset_cache'] ) ) { // Input var ok.
				self::reset_cache();
			}
		}

		/**
		 * Ajax Reset cache
		 */
		public function ajax_reset_cache() {
			powerkit_uuid_hash();

			// Check wpnonce.
			if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'] ) ) { // Input var ok; sanitization ok.
				return;
			}

			$list = apply_filters( 'powerkit_ajax_reset_cache', array() );

			if ( ! isset( $_REQUEST['page'] ) ) { // Input var ok.
				return false;
			}

			$page = sanitize_key( $_REQUEST['page'] ); // Input var ok; sanitization ok.

			if ( ! isset( $list[ $page ] ) ) {
				return false;
			}

			self::reset_cache( $list[ $page ] );

			die();
		}

		/**
		 * Cron refresh access token
		 */
		public function cron_access_token_refresh() {
			self::access_token_refresh( 'instagram' );
		}

		/**
		 * Refresh access token
		 *
		 * @param string $type                   The type.
		 * @param bool   $should_attempt_refresh The refresh.
		 */
		public static function access_token_refresh( $type, $should_attempt_refresh = false ) {

			if ( 'instagram' === $type && 'personal' === powerkit_connect( 'instagram_app_type' ) ) {

				if ( (int) get_option( 'powerkit_connect_instagram_app_refresh_time' ) < time() ) {
					$should_attempt_refresh = true;
				}

				if ( $should_attempt_refresh ) {

					update_option( 'powerkit_connect_instagram_app_refresh_time', time() + ( DAY_IN_SECONDS * 30 ) );

					$remote_link = add_query_arg(
						array(
							'grant_type'   => 'ig_refresh_token',
							'access_token' => powerkit_connect( 'instagram_app_access_token' ),
						),
						'https://graph.instagram.com/refresh_access_token'
					);

					$response = wp_safe_remote_get(
						$remote_link,
						array(
							'timeout' => 3,
						)
					);

					$response = wp_remote_retrieve_body( $response );

					$data = json_decode( $response, true );

					// Update settings.
					if ( isset( $data['access_token'] ) ) {
						update_option( 'powerkit_connect_instagram_app_access_token', $data['access_token'] );
					}
				}
			}
		}

		/**
		 * Reset cache
		 *
		 * @param array $list Reset list.
		 */
		public static function reset_cache( $list = false ) {
			if ( is_array( $list ) ) {

				$list = $list;

			} elseif ( is_string( $list ) && $list ) {

				$list = explode( ' ', $list );

			} else {
				$list = apply_filters( 'powerkit_reset_cache', array() );

				$puck = array();

				foreach ( $list as $item ) {
					if ( is_array( $item ) ) {
						$puck = array_merge( $puck, $item );
					} elseif ( is_string( $item ) ) {
						$puck = array_merge( $puck, explode( ' ', $item ) );
					}
				}

				$list = $puck;
			}

			if ( $list ) {
				global $wpdb;

				foreach ( $list as $option_name ) {
					$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name LIKE '%%%s%%'", $option_name ) ); // db call ok; no-cache ok.
					$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->postmeta WHERE meta_key LIKE '%%%s%%'", $option_name ) ); // db call ok; no-cache ok.
				}

				printf( '<div id="message" class="updated fade"><p><strong>%s</strong></p></div>', esc_html__( 'Cache purged.', 'powerkit' ) );
			}
		}

		/**
		 * Register the stylesheets and JavaScript for the admin area.
		 *
		 * @param string $page Current page.
		 */
		public function enqueue_scripts( $page ) {
			if ( is_customize_preview() || 'toplevel_page_powerkit_manager' === $page ) {

				wp_enqueue_script( 'admin-powerkit-connect', plugin_dir_url( __FILE__ ) . 'js/admin-powerkit-connect.js', array( 'jquery' ), powerkit_get_setting( 'version' ), false );

				wp_localize_script(
					'admin-powerkit-connect',
					'pk_connect',
					array(
						'ajax_url' => admin_url( 'admin-ajax.php' ),
					)
				);
			}
		}
	}

	new Powerkit_Connect();
}
