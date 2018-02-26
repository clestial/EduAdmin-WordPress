<?php

/**
 * Class EduAdminRESTClient
 */
class EduAdminRESTClient {
	protected $user_agent = 'EduAdmin REST API Client';
	/**
	 * @var string API User
	 */
	static $api_user = null;
	/**
	 * @var string API Password
	 */
	static    $api_pass = null;
	static    $root_url = 'https://api.eduadmin.se';
	protected $api_url  = '';

	/**
	 * @param resource $curl
	 *
	 * @return mixed
	 */
	private function execute_request( $curl ) {
		$r                     = curl_exec( $curl );
		$i                     = curl_getinfo( $curl );
		$obj                   = array();
		if ( false === $r || ( json_decode( $r ) && isset( json_decode( $r )->error ) ) || ( $i['http_code'] < 200 || $i['http_code'] > 299 ) ) {
			curl_close( $curl );
			if ( null !== json_decode( $r ) ) {
				$obj = json_decode( $r, true );
			} else {
				$obj['data'] = $r;
			}
			$obj['@curl']          = $i;
			$obj['@error']         = $r;

			return $obj;
		}
		curl_close( $curl );
		if ( null !== json_decode( $r ) ) {
			$obj = json_decode( $r, true );
		} else {
			$obj['data'] = $r;
		}
		$obj['@curl'] = $i;

		return $obj;
	}

	/**
	 * @param      $endpoint    string Where are we going with this request?
	 * @param      $params      string|object|array Contains all parameters that we want to pass to the API
	 * @param      $method_name string Which method called us?
	 * @param bool $is_json     Decides if this is a post with JSON
	 *
	 * @return mixed
	 */
	public function POST( $endpoint, $params, $method_name, $is_json = true ) {
		return $this->make_request( 'POST', $endpoint, $params, $method_name, $is_json );
	}

	/**
	 * @param      $endpoint    string Where are we going with this request?
	 * @param      $params      string|object|array Contains all parameters that we want to pass to the API
	 * @param      $method_name string Which method called us?
	 * @param bool $is_json     Decides if this is a post with JSON
	 *
	 * @return mixed
	 */
	public function PUT( $endpoint, $params, $method_name, $is_json = true ) {
		return $this->make_request( 'PUT', $endpoint, $params, $method_name, $is_json );
	}

	/**
	 * @param      $endpoint    string Where are we going with this request?
	 * @param      $params      string|object|array Contains all parameters that we want to pass to the API
	 * @param      $method_name string Which method called us?
	 * @param bool $is_json     Decides if this is a post with JSON
	 *
	 * @return mixed
	 */
	public function DELETE( $endpoint, $params, $method_name, $is_json = true ) {
		return $this->make_request( 'DELETE', $endpoint, $params, $method_name, $is_json );
	}

	/**
	 * @param      $endpoint    string Where are we going with this request?
	 * @param      $params      string|object|array Contains all parameters that we want to pass to the API
	 * @param      $method_name string Which method called us?
	 * @param bool $is_json     Decides if this is a post with JSON
	 *
	 * @return mixed
	 */
	public function PATCH( $endpoint, $params, $method_name, $is_json = true ) {
		return $this->make_request( 'PATCH', $endpoint, $params, $method_name, $is_json );
	}

	/**
	 * @param string              $type
	 * @param string              $endpoint
	 * @param string|array|object $params
	 * @param string              $method_name
	 * @param bool                $is_json
	 *
	 * @return mixed
	 */
	private function make_request( $type, $endpoint, $params, $method_name, $is_json = true ) {
		$t = EDUAPI()->start_timer( $method_name . ' - ' . $type );
		$c = $this->get_curl_object( $endpoint );

		$headers = array();
		$data    = null;

		if ( $is_json ) {
			$headers[] = 'Content-Type: application/json';
			$data      = json_encode( $params );
			$headers[] = 'Content-Length: ' . strlen( $data );
		} else {
			$data = http_build_query( $params );
		}
		$this->set_headers( $c, $headers );

		curl_setopt( $c, CURLOPT_CUSTOMREQUEST, $type );
		curl_setopt( $c, CURLOPT_POSTFIELDS, $data );

		$result = $this->execute_request( $c );
		EDUAPI()->stop_timer( $t );

		return $result;
	}

	/**
	 * @param string       $endpoint
	 * @param object|array $params
	 * @param string       $method_name
	 *
	 * @return mixed
	 */
	public function GET( $endpoint, $params, $method_name ) {
		$t = EDUAPI()->start_timer( $method_name . ' - GET' );
		$c = $this->get_curl_object( $endpoint . '?' . http_build_query( $params ) );
		$this->set_headers( $c, array() );
		$result = $this->execute_request( $c );
		EDUAPI()->stop_timer( $t );

		return $result;
	}

	/**
	 * @param string $endpoint
	 *
	 * @return resource
	 */
	private function get_curl_object( $endpoint ) {
		if ( ! strpos( $endpoint, '/' ) === 0 ) {
			$endpoint = '/' . $endpoint;
		}
		$c = curl_init( self::$root_url . $this->api_url . $endpoint );
		curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );

		return $c;
	}

	/**
	 * @param resource $curl_object
	 * @param array    $array
	 */
	private function set_headers( $curl_object, array $array = array() ) {
		if ( isset( EDUAPI()->api_token ) ) {
			$std_headers = array(
				'Authorization: bearer ' . EDUAPI()->api_token->AccessToken,
			);
		} else {
			$std_headers = array();
		}
		if ( ! empty( $array ) ) {
			$std_headers = array_merge( $std_headers, $array );
		}

		curl_setopt( $curl_object, CURLOPT_HTTPHEADER, $std_headers );
	}
}