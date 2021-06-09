<?php

/**
 * Class LP_REST_Admin_Themes_Addons_Controller
 *
 * @since 4.0.8
 * @author tungnx
 * @version 1.0.0
 */
class LP_REST_Admin_Themes_Addons_Controller extends LP_Abstract_REST_Controller {
	public function __construct() {
		$this->namespace = 'lp/v1/admin';
		$this->rest_base = 'themes-addons';
		parent::__construct();
	}

	/**
	 * Register rest routes.
	 */
	public function register_routes() {
		$this->routes = array(
			'get-addons'         => array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_addons' ),
					'permission_callback' => '__return_true',
				),
			),
			'install-addon-free' => array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'install_addon_free' ),
					'permission_callback' => '__return_true',
				),
			),
			'activate-addon' => array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'activate_addon' ),
					'permission_callback' => '__return_true',
				),
			),
		);

		parent::register_routes();
	}

	/**
	 * Get addons.
	 *
	 * @param WP_REST_Request $request .
	 *
	 * @return void
	 */
	public function get_addons( WP_REST_Request $request ) {
		$response = new LP_REST_Response();

		try {
			$plugins = $this->query_free_addons();

			$response->data->plugin_wp = $plugins;
			$response->status          = 'success';
		} catch ( Exception $e ) {
			$response->message = $e->getMessage();
		}

		wp_send_json( $response );
	}

	/**
	 * Get addons lp on WP.org
	 *
	 * @throws Exception
	 */
	public function query_free_addons() {
		LP_Plugins_Helper::require_plugins_api();

		$per_page = 20;
		$paged    = 1;

		$query_args = array(
			'author'   => 'ThimPress',
			'page'     => $paged,
			'per_page' => $per_page,
			'fields'   => array(
				'last_updated'    => false,
				'icons'           => true,
				'active_installs' => false,
				'ratings'         => false,
				'downloaded'      => false,
				'support_threads' => false,
				'donate_link'     => true,
				'num_ratings'     => false,
			),

		);
		$plugins = array();

		$api = plugins_api( 'query_plugins', $query_args );

		if ( is_wp_error( $api ) ) {
			throw new Exception( __( 'WP query plugins error!', 'learnpress' ) );
		}
		if ( ! is_array( $api->plugins ) ) {
			throw new Exception( __( 'WP query plugins empty!', 'learnpress' ) );
		}

		// Filter plugins with slug contains 'learnpress'
		$_plugins = array_filter( $api->plugins, array( 'LP_Plugins_Helper', '_filter_plugin' ) );
		// Ensure that the array is indexed from 0
		$_plugins = array_values( $_plugins );

		if ( ! empty( $_plugins ) ) {
			foreach ( $_plugins as $k => $plugin ) {
				$status = install_plugin_install_status( $plugin );

				$_plugins[ $k ]['status'] = $status;

				switch ( $status['status'] ) {
					case 'install':
						if ( $status['url'] ) {
							/* translators: 1: Plugin name and version. */
							$_plugins[ $k ]['link_actions'][] = '<a class="install-now addon-free button lp-btn-addon-action" href="' . esc_url( $plugin['download_link'] ) . '">' . __( 'Install Now' ) . '</a>';
						}

						break;
					case 'update_available':
						if ( $status['url'] ) {
							/* translators: 1: Plugin name and version */
							$_plugins[ $k ]['link_actions'][] = '<a class="update-now button" data-plugin="' . esc_attr( $status['file'] ) . '" data-slug="' . esc_attr( $plugin['slug'] ) . '" href="' . esc_url( $status['url'] ) . '" aria-label="' . esc_attr( sprintf( __( 'Update %s now', 'learnpress' ), 'name' ) ) . '" data-name="' . esc_attr( 'name' ) . '"><span>' . __( 'Update Now' ) . '</span></a>';
						}

						break;
					case 'latest_installed':
						$_plugins[ $k ]['link_actions'][] = '<a class="button disable-now" data-slug="' . esc_attr( $plugin['slug'] ) . '" href="#" aria-label="' . esc_attr( sprintf( __( 'Disable %s now', 'learnpress' ), '$name' ) ) . '" data-name="' . esc_attr( '$name' ) . '"><span>' . __( 'Disable Now', 'learnpress' ) . '</span></a>';
						break;
				}
			}
		}

		return $_plugins;
	}

	/**
	 * Install addon fre from wp.org
	 *
	 * @param WP_REST_Request $requests
	 */
	public function install_addon_free( WP_REST_Request $request ): LP_REST_Response {
		$response             = new LP_REST_Response();
		$response->data->type = 'install_free';

		try {
			if ( empty( $request->get_param( 'plugin_url' ) ) ) {
				throw new Exception( 'Invalid params!' );
			}

			$plugin_url = $request->get_param( 'plugin_url' );
			$result     = LP_Plugins_Helper::install_addon_free( $plugin_url );

			/**
			 * @var WP_Error $result
			 */
			if ( is_wp_error( $result ) ) {
				throw new Exception( $result->get_error_message() );
			}

			if ( $result ) {
				$response->status = 'success';
			} else {
				$response->message = __( 'Install error', 'learnpress' );
			}
		} catch ( Exception $e ) {
			$response->message = $e->getMessage();
		}

		wp_send_json( $response );
	}

	/**
	 * Activate addon
	 *
	 * @param WP_REST_Request $requests
	 */
	public function activate_addon( WP_REST_Request $request ) {
		$response             = new LP_REST_Response();
		$response->data->type = 'activate';

		try {
			include_once ABSPATH .'wp-admin/includes/ajax-actions.php';
			$_POST['slug'] = $request->get_param( 'plugin_url' );
			$m = wp_ajax_install_plugin();

			return $m;

			if ( empty( $request->get_param( 'plugin_base' ) ) ) {
				throw new Exception( 'Invalid params!' );
			}

			$plugin_url = $request->get_param( 'plugin_url' );
			$result     = LP_Plugins_Helper::install_addon_free( $plugin_url );

			/**
			 * @var WP_Error $result
			 */
			if ( is_wp_error( $result ) ) {
				throw new Exception( $result->get_error_message() );
			}

			if ( $result ) {
				$response->status = 'success';
			} else {
				$response->message = __( 'Install error', 'learnpress' );
			}
		} catch ( Exception $e ) {
			$response->message = $e->getMessage();
		}

		wp_send_json( $response );
	}

	/**
	 * Activate addon
	 *
	 * @param WP_REST_Request $requests
	 */
	public function update_addon( WP_REST_Request $request ) {
		$response             = new LP_REST_Response();
		$response->data->type = 'activate';

		try {
			include_once ABSPATH .'wp-admin/includes/ajax-actions.php';
			$_POST['plugin'] = $request->get_param( 'plugin_url' );
			$_POST['slug'] = $request->get_param( 'plugin_url' );
			//Test
			$_POST['plugin'] = 'learnpress-buddypress/learnpress-buddypress';
			$_POST['slug'] = $request->get_param( 'plugin_url' );
			// End
			$m = wp_ajax_update_plugin();
		} catch ( Exception $e ) {
			$response->message = $e->getMessage();
		}

		wp_send_json( $response );
	}
}
