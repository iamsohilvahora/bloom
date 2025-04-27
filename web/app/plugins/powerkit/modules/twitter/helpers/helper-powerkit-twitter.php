<?php
/**
 * Helpers Twitter
 *
 * @package    Powerkit
 * @subpackage Modules/Helper
 */

/**
 * Convert links to clickable format
 *
 * @param string $name Specific template.
 * @param array  $twitter Array of twitter.
 * @param array  $params Array of params.
 */
function powerkit_twitter_template_handler( $name, $twitter, $params ) {
	$templates = apply_filters( 'powerkit_twitter_templates', array() );

	$new = isset( $templates['default'] ) ? false : true;

	if ( $new && count( $templates ) > 0 ) {
		$first_item = array_shift( $templates );

		if ( function_exists( $first_item['func'] ) ) {
			call_user_func( $first_item['func'], $twitter, $params );
		} else {
			call_user_func( 'powerkit_twitter_default_template', $twitter, $params );
		}
	} elseif ( isset( $templates[ $name ] ) && function_exists( $templates[ $name ]['func'] ) ) {
		call_user_func( $templates[ $name ]['func'], $twitter, $params );
	} else {
		call_user_func( 'powerkit_twitter_default_template', $twitter, $params );
	}
}

/**
 * Get templates options
 *
 * @return array Items.
 */
function powerkit_twitter_get_templates_options() {
	$options = array();

	$templates = apply_filters( 'powerkit_twitter_templates', array() );

	if ( $templates ) {
		foreach ( $templates as $key => $item ) {
			if ( isset( $item['name'] ) ) {
				$options[ $key ] = $item['name'];
			}
		}
	}

	return $options;
}

/**
 * Convert links to clickable format
 *
 * @param string $links       Text with links.
 * @param bool   $targetblank Open links in a new tab.
 * @return string Text with replaced links.
 */
function powerkit_twitter_convert_links( $links, $targetblank = true ) {

	// The target.
	$target = $targetblank ? ' target="_blank" ' : '';

	// Convert link to url.
	$links = preg_replace( '/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[A-Z0-9+&@#\/%=~_|]/i', '<a href="\0" target="_blank">\0</a>', $links );

	// Convert @ to follow.
	$links = preg_replace( '/(@([_a-z0-9\-]+))/i', '<a href="http://twitter.com/$2" title="Follow $2" $target >$1</a>', $links );

	// Convert # to search.
	$links = preg_replace( '/(#([_a-z0-9\-]+))/i', '<a href="https://twitter.com/search?q=$2" title="Search $1" $target >$1</a>', $links );

	// Return links.
	return $links;
}

/**
 * Get timeline twitter
 *
 * @param array $options    Timeline options.
 * @param array $cache_time Cache time.
 */
function powerkit_twitter_get_timeline( $options, $cache_time ) {

	if ( powerkit_connect( 'twitter_app_oauth_token' ) ) {

		$twitter_connect = new Powerkit_Twitter_API();
		$twitter_connect->init( array(
			'consumer_key'        => powerkit_connect( 'twitter_app_consumer_key' ),
			'consumer_secret'     => powerkit_connect( 'twitter_app_consumer_secret' ),
			'access_token'        => powerkit_connect( 'twitter_app_oauth_token' ),
			'access_token_secret' => powerkit_connect( 'twitter_app_oauth_token_secret' ),
		), 'timeline' );
		$twitter_connect->set_url_base();
		$twitter_connect->set_get_fields( $options );
		$twitter_connect->perform_request();

		$response_code    = wp_remote_retrieve_response_code( $twitter_connect->json );
		$response_message = wp_remote_retrieve_response_message( $twitter_connect->json );
		$response_body    = json_decode( wp_remote_retrieve_body( $twitter_connect->json ) );

		if ( 200 !== $response_code && ! empty( $response_message ) ) {

			$twitter = new WP_Error( $response_code, $response_message );

		} elseif ( 200 !== $response_code ) {

			$twitter = new WP_Error( $response_code, sprintf( '%s <a href="%s">%s</a>', esc_html__( 'Twitter data is not set. Please check your Twitter User or ', 'powerkit' ), powerkit_get_page_url( 'connect&tab=twitter' ), esc_html__( ' Twitter Settings', 'powerkit' ) ) );

		} elseif ( empty( $response_body ) ) {

			$twitter = new WP_Error( $response_code, esc_html__( 'There are no tweets in your account.', 'powerkit' ) );

		} else {
			$twitter = $response_body;
		}
	} else {
		$twitter = new WP_Error( 'No token.', sprintf( '%s <a href="%s">%s</a>', esc_html__( 'Twitter data is not set. Please check your Twitter User or ', 'powerkit' ), powerkit_get_page_url( 'connect&tab=twitter' ), esc_html__( ' Twitter Settings', 'powerkit' ) ) );
	}

	return $twitter;
}

/**
 * Get recent feed from twitter
 *
 * @param array  $params     Recent options.
 * @param string $cache_name The cache name.
 */
function powerkit_twitter_get_recent( $params, $cache_name = 'powerkit_twitter_data' ) {
	$params = array_merge(
		array(
			'title'      => esc_html__( 'Twitter Feed', 'powerkit' ),
			'username'   => '',
			'number'     => 5,
			'template'   => 'default',
			'header'     => true,
			'button'     => true,
			'replies'    => false,
			'retweets'   => false,
			'cache_time' => (int) apply_filters( 'powerkit_twitter_cache_timeout', 60 ),
		),
		(array) $params
	);

	$cache_name = $cache_name . '_' . md5( maybe_serialize( $params ) . powerkit_connect( 'twitter_app_oauth_token' ) );

	// Check if transient already exists.
	$twitter = get_transient( $cache_name );

	if ( ! empty( $twitter ) ) {

		// Fetch twitter from the transient.
		$twitter = maybe_unserialize( $twitter );

	} else {

		// Get Twitter via Twitter OAuth.
		$twitter = powerkit_twitter_get_timeline(
			array(
				'screen_name'     => $params['username'],
				'count'           => $params['number'],
				'include_rts'     => $params['retweets'] ? 'true' : 'false',
				'exclude_replies' => $params['replies'] ? 'false' : 'true',
			),
			$params['cache_time']
		);

		// Set a new transient if no errors returned.
		set_transient( $cache_name, maybe_serialize( $twitter ), $params['cache_time'] * 60 );
	}

	// Check if errors have been returned.
	if ( ! is_wp_error( $twitter ) ) {
		powerkit_twitter_template_handler( $params['template'], $twitter, $params );
	} else {
		powerkit_alert_warning( $twitter->get_error_message() );
	}
}
