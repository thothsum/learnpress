<?php

/**
 * Class LP_REST_Users_Controller
 *
 * @since 3.3.0
 */
class LP_REST_Admin_Database_Controller extends LP_Abstract_REST_Controller {
	/**
	 * @var LP_User
	 */
	protected $user = null;

	public function __construct() {
		$this->namespace = 'lp/v1';
		$this->rest_base = 'database';
		parent::__construct();

		add_filter( 'rest_pre_dispatch', array( $this, 'rest_pre_dispatch' ), 10, 3 );
	}

	/**
	 * Init data prepares for callbacks of rest
	 *
	 * @param                 $null
	 * @param WP_REST_Server  $server
	 * @param WP_REST_Request $request
	 *
	 * @return mixed
	 */
	public function rest_pre_dispatch( $response, $handler, $request ) {

		return $response;
	}

	/**
	 * Register rest routes.
	 */
	public function register_routes() {
		$this->routes = array(
			'upgrade'     => array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'upgrade' ),
					'permission_callback' => array( $this, 'check_admin_permission' ),
				),
			),
			'get_steps'   => array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'get_steps' ),
					'permission_callback' => array( $this, 'check_admin_permission' ),
				),
			),
			'agree_terms' => array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'agree_terms_upgrade' ),
					'permission_callback' => array( $this, 'check_admin_permission' ),
				),
			),
		);

		parent::register_routes();
	}

	public function check_admin_permission() {
		return LP_REST_Authentication::check_admin_permission();
	}

	/**
	 * Set agree terms upgrade database.
	 *
	 * @param WP_REST_Request $request .
	 *
	 * @return void
	 */
	public function agree_terms_upgrade( WP_REST_Request $request ) {
		$result = new LP_REST_Response();

		if ( $request->get_param( 'agree_terms' ) ) {
			LP_Settings::update_option( 'agree_terms', 1 );
			$result->status = 'success';
		}

		wp_send_json( $result );
	}

	/**
	 * Upgrade DB
	 *
	 * @param WP_REST_Request $request .
	 *
	 * @return void
	 */
	public function upgrade( WP_REST_Request $request ) {
		$lp_updater   = LP_Updater::instance();
		$result       = new LP_REST_Response();
		$class_handle = $lp_updater->load_file_version_upgrade_db();

		if ( empty( $class_handle ) ) {
			$result->message = sprintf(
				'%s %s',
				__( 'The LP Database is Latest:', 'learnpress' ),
				'v' . get_option( 'learnpress_db_version' )
			);
			wp_send_json( $result );
		}

		$params = $request->get_params();

		wp_send_json( $class_handle->handle( $params ) );
	}

	/**
	 * Get Steps upgrade completed.
	 */
	public function get_steps() {
		$lp_updater      = LP_Updater::instance();
		$lp_db           = LP_Database::getInstance();
		$steps_completed = array();
		$steps_default   = array();

		$class_handle = $lp_updater->load_file_version_upgrade_db();

		if ( ! empty( $class_handle ) ) {
			$steps_default = $class_handle->group_steps;

			$tb_lp_upgrade_db_exists = $lp_db->check_table_exists( $lp_db->tb_lp_upgrade_db );

			if ( $tb_lp_upgrade_db_exists ) {
				$steps_completed = $lp_db->get_steps_completed();
			}
		}

		$steps = array(
			'steps_default'   => $steps_default,
			'steps_completed' => $steps_completed,
		);

		wp_send_json( $steps );
	}
}