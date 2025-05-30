<?php
/**
 * Twitter API
 *
 * @package    Powerkit
 * @subpackage Extensions
 */

if ( class_exists( 'Powerkit_Module' ) ) {
	/**
	 * Init module
	 */
	class Powerkit_Twitter_API extends Powerkit_Module {

		/**
		 * Register module
		 */
		public function register() {
			$this->name     = 'Twitter API';
			$this->slug     = 'twitter-api';
			$this->type     = 'extension';
			$this->category = 'basic';
			$this->public   = false;
			$this->enabled  = false;
		}

		/**
		 * The base_url.
		 *
		 * @var string $base_url The base_url.
		 */
		protected $base_url;

		/**
		 * The get_fields.
		 *
		 * @var string $get_fields The get_fields.
		 */
		private $get_fields;

		/**
		 * The oauth.
		 *
		 * @var string $oauth The oauth.
		 */
		private $oauth;

		/**
		 * The header.
		 *
		 * @var string $header The header.
		 */
		private $header;

		/**
		 * The json.
		 *
		 * @var string $json The json.
		 */
		public $json;

		/**
		 * Init API
		 *
		 * @param array  $request_settings All necessary tokens for OAuth connection.
		 * @param string $feed_type       Type of Twitter feed.
		 */
		public function init( $request_settings, $feed_type ) {
			$this->consumer_key        = $request_settings['consumer_key'];
			$this->consumer_secret     = $request_settings['consumer_secret'];
			$this->access_token        = $request_settings['access_token'];
			$this->access_token_secret = $request_settings['access_token_secret'];
			$this->feed_type           = $feed_type;
		}

		/**
		 * Sets the complete url for our API endpoint. GET fields will be added later
		 */
		public function set_url_base() {
			switch ( $this->feed_type ) {
				case 'hometimeline':
					$this->base_url = 'https://api.twitter.com/1.1/statuses/home_timeline.json';
					break;
				case 'search':
					$this->base_url = 'https://api.twitter.com/1.1/search/tweets.json';
					break;
				default:
					$this->base_url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
			}
		}

		/**
		 * Encodes an array of GET field data into html characters for including in a URL
		 *
		 * @param array $get_fields Array of GET fields that are compatible with the Twitter API.
		 */
		public function set_get_fields( array $get_fields ) {
			$url_string = '?';
			$length     = count( $get_fields );
			$j          = 1;
			foreach ( $get_fields as $key => $value ) {
				$url_string .= rawurlencode( $key ) . '=' . rawurlencode( $value );
				if ( $j != $length ) {
					$url_string .= '&';
				}
				$j++;
			}

			$this->get_fields = $url_string;
		}

		/**
		 * Uses the OAuth data to build the base string needed to create the
		 * OAuth signature to be used in the header of the request
		 *
		 * @param array $oauth Oauth data without the signature.
		 */
		private function build_base_string( $oauth ) {
			$base_string = array();
			ksort( $oauth );

			// Start forming the header string by creating a numeric index array with each part of the header string it's own element in the array.
			foreach ( $oauth as $key => $value ) {
				$base_string[] = rawurlencode( $key ) . '=' . rawurlencode( $value );
			}

			// Convert the array of values into a single encoded string and return.
			return 'GET&' . rawurlencode( $this->base_url ) . '&' . rawurlencode( implode( '&', $base_string ) );
		}

		/**
		 * Builds the OAuth data array that is used to authenticate the connection
		 * to the Twitter API
		 */
		public function build_oauth() {
			$oauth = array(
				'oauth_consumer_key'     => $this->consumer_key,
				'oauth_nonce'            => time(),
				'oauth_signature_method' => 'HMAC-SHA1',
				'oauth_token'            => $this->access_token,
				'oauth_timestamp'        => time(),
				'oauth_version'          => '1.0',
			);

			$getfields = str_replace( '?', '', explode( '&', $this->get_fields ) );

			// Add the get fields to the oauth associative array to be formed into the header string eventually.
			foreach ( $getfields as $getfield ) {
				$split = explode( '=', $getfield );

				if ( isset( $split[1] ) ) {
					$oauth[ $split[0] ] = urldecode( $split[1] );
				}
			}

			// The OAuth signature for Twitter is a hashed, encoded version of the base url, 4 different keys.
			$base_string              = $this->build_base_string( $oauth );
			$composite_key            = rawurlencode( $this->consumer_secret ) . '&' . rawurlencode( $this->access_token_secret );
			$oauth_signature          = base64_encode( hash_hmac( 'sha1', $base_string, $composite_key, true ) );
			$oauth['oauth_signature'] = $oauth_signature;

			$this->oauth = $oauth;
		}

		/**
		 * Since the OAuth data is passed in a url, special characters need to be encoded
		 */
		private function encode_header() {
			$header = 'Authorization: OAuth ';
			$values = array();

			// Each element of the header needs to have it's special characters encoded for passing through a url.
			foreach ( $this->oauth as $key => $value ) {
				if ( in_array(
					$key,
					array(
						'oauth_consumer_key',
						'oauth_nonce',
						'oauth_signature',
						'oauth_signature_method',
						'oauth_timestamp',
						'oauth_token',
						'oauth_version',
					)
				) ) {
					$values[] = "$key=\"" . rawurlencode( $value ) . '"';
				}
			}

			$header      .= implode( ', ', $values );
			$this->header = $header;
		}


		/**
		 * Attempts to connect to the Twitter api using WP_HTTP class
		 *
		 * @param string $url The complete api endpoint url.
		 */
		private function wp_http_request( $url ) {
			$args   = array(
				'headers'   => $this->header,
				'timeout'   => 60,
				'sslverify' => false,
			);

			return wp_remote_get( $url, $args );
		}

		/**
		 * Uses the data created and gathered up to this point to make the actual connection
		 * to the Twitter API. It first tests whether or not a curl connection is possible,
		 * followed by file_get_contents connection, then defaults to the WordPress WP_HTTP object
		 *
		 * @return mixed|string raw json data retrieved from the API request
		 */
		public function perform_request() {
			$url = $this->base_url . $this->get_fields;

			$this->build_oauth();
			$this->encode_header();

			$this->json = $this->wp_http_request( $url );

			return $this;
		}
	}
}
