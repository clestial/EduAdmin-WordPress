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
	static $api_pass = null;
	static $root_url = 'https://api.eduadmin.se';
	protected $api_url = '';

	/**
	 * @param resource $curl
	 *
	 * @return mixed
	 */
	private function executeRequest( $curl ) {
		$r   = curl_exec( $curl );
		$i   = curl_getinfo( $curl );
		$obj = array();

		if ( $r == false || ( json_decode( $r ) && isset( json_decode( $r )->error ) ) || ( $i["http_code"] < 200 || $i["http_code"] > 299 ) ) {
			curl_close( $curl );
			if ( json_decode( $r ) ) {
				$obj = json_decode( $r, true );
			} else {
				$obj["data"] = $r;
			}
			$obj["@curl"]  = $i;
			$obj["@error"] = $r;

			return $obj;
		}
		curl_close( $curl );
		$obj          = json_decode( $r, true );
		$obj["@curl"] = $i;

		return $obj;
	}

	/**
	 * @param      $endpoint   string Where are we going with this request?
	 * @param      $params     string|object|array Contains all parameters that we want to pass to the API
	 * @param      $methodName string Which method called us?
	 * @param bool $is_json Decides if this is a post with JSON
	 *
	 * @return mixed
	 */
	public function POST( $endpoint, $params, $methodName, $is_json = true ) {
		return $this->makeRequest( "POST", $endpoint, $params, $methodName, $is_json );
	}

	/**
	 * @param      $endpoint   string Where are we going with this request?
	 * @param      $params     string|object|array Contains all parameters that we want to pass to the API
	 * @param      $methodName string Which method called us?
	 * @param bool $is_json Decides if this is a post with JSON
	 *
	 * @return mixed
	 */
	public function PUT( $endpoint, $params, $methodName, $is_json = true ) {
		return $this->makeRequest( "PUT", $endpoint, $params, $methodName, $is_json );
	}

	/**
	 * @param      $endpoint   string Where are we going with this request?
	 * @param      $params     string|object|array Contains all parameters that we want to pass to the API
	 * @param      $methodName string Which method called us?
	 * @param bool $is_json Decides if this is a post with JSON
	 *
	 * @return mixed
	 */
	public function DELETE( $endpoint, $params, $methodName, $is_json = true ) {
		return $this->makeRequest( "DELETE", $endpoint, $params, $methodName, $is_json );
	}

	/**
	 * @param      $endpoint   string Where are we going with this request?
	 * @param      $params     string|object|array Contains all parameters that we want to pass to the API
	 * @param      $methodName string Which method called us?
	 * @param bool $is_json Decides if this is a post with JSON
	 *
	 * @return mixed
	 */
	public function PATCH( $endpoint, $params, $methodName, $is_json = true ) {
		return $this->makeRequest( "PATCH", $endpoint, $params, $methodName, $is_json );
	}

	/**
	 * @param string $type
	 * @param string $endpoint
	 * @param string|array|object $params
	 * @param string $methodName
	 * @param bool $is_json
	 *
	 * @return mixed
	 */
	private function makeRequest( $type, $endpoint, $params, $methodName, $is_json = true ) {
		$t = EDUAPI()->StartTimer( $methodName . ' - ' . $type );
		$c = $this->getCurlObject( $endpoint );

		$headers = array();
		$data    = null;

		if ( $is_json ) {
			$headers[] = "Content-Type: application/json";
			$data      = json_encode( $params );
			$headers[] = "Content-Length: " . strlen( $data );
		} else {
			$data = http_build_query( $params );
		}
		$this->setHeaders( $c, $headers );

		curl_setopt( $c, CURLOPT_CUSTOMREQUEST, $type );
		curl_setopt( $c, CURLOPT_POSTFIELDS, $data );

		$result = $this->executeRequest( $c );
		EDUAPI()->StopTimer( $t );

		return $result;
	}

	/**
	 * @param string $endpoint
	 * @param object|array $params
	 * @param string $methodName
	 *
	 * @return mixed
	 */
	public function GET( $endpoint, $params, $methodName ) {
		$t = EDUAPI()->StartTimer( $methodName . ' - GET' );
		$c = $this->getCurlObject( $endpoint . "?" . http_build_query( $params ) );
		$this->setHeaders( $c, array() );
		$result = $this->executeRequest( $c );
		EDUAPI()->StopTimer( $t );

		return $result;
	}

	/**
	 * @param string $endpoint
	 *
	 * @return resource
	 */
	private function getCurlObject( $endpoint ) {
		if ( ! strpos( $endpoint, "/" ) === 0 ) {
			$endpoint = "/" . $endpoint;
		}
		$c = curl_init( self::$root_url . $this->api_url . $endpoint );
		curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );

		return $c;
	}

	/**
	 * @param resource $curlObject
	 * @param array $array
	 */
	private function setHeaders( $curlObject, array $array = array() ) {
		if ( isset( EDUAPI()->api_token ) ) {
			$stdHeaders = array(
				'Authorization: bearer ' . EDUAPI()->api_token->AccessToken,
			);
		} else {
			$stdHeaders = array();
		}
		if ( ! empty( $array ) ) {
			$stdHeaders = array_merge( $stdHeaders, $array );
		}

		curl_setopt( $curlObject, CURLOPT_HTTPHEADER, $stdHeaders );
	}
}