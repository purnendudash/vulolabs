<?php
/**
 * Base REST controller file.
 *
 * @package VuloMail
 */

namespace VuloMail\Rest;

use VuloMail\Utill;

defined( 'ABSPATH' ) || exit;

/**
 * Shared permission check: every VuloMail route is admin-only. Core's REST cookie authentication
 * verifies the `wp_rest` nonce before this runs, so a logged-in admin's browser can't be tricked
 * into calling these routes from another site.
 */
abstract class Controller extends \WP_REST_Controller {

	/**
	 * @return bool|\WP_Error
	 */
	public function check_permission() {
		if ( ! current_user_can( Utill::CAPABILITY ) ) {
			return new \WP_Error( 'rest_forbidden', __( 'You are not allowed to manage VuloMail.', 'vulomail' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}
}
