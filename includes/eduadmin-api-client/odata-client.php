<?php

/**
 * Class EduAdminODataClient
 */
class EduAdminODataClient extends EduAdminRESTClient {
	protected $user_agent = 'EduAdmin OData API Client';

	/**
	 * @param string|null $select
	 * @param string|null $filter
	 * @param string|null $expand
	 * @param string|null $orderby
	 * @param int|null $top
	 * @param int|null $skip
	 * @param bool $count
	 *
	 * @return mixed
	 */
	public function Search( $select = null, $filter = null, $expand = null, $orderby = null, $top = null, $skip = null, $count = true ) {
		$params = array();
		if ( isset( $select ) && ! empty( $select ) ) {
			$params['$select'] = $select;
		}
		if ( isset( $filter ) && ! empty( $filter ) ) {
			$params['$filter'] = $filter;
		}
		if ( isset( $expand ) && ! empty( $expand ) ) {
			$params['$expand'] = $expand;
		}
		if ( isset( $orderby ) && ! empty( $orderby ) ) {
			$params['$orderby'] = $orderby;
		}
		if ( isset( $top ) && ! empty( $top ) ) {
			$params['$top'] = $top;
		}
		if ( isset( $skip ) && ! empty( $skip ) ) {
			$params['$skip'] = $skip;
		}
		$params['$count'] = $count ? 'true' : false;

		return parent::GET( '', $params, get_called_class() . '|' . __FUNCTION__ );
	}

	/**
	 * @param int $id
	 * @param string|null $select
	 * @param string|null $expand
	 *
	 * @return mixed
	 */
	public function GetItem( $id, $select = null, $expand = null ) {
		$params = array();
		if ( isset( $select ) && ! empty( $select ) ) {
			$params['$select'] = $select;
		}
		if ( isset( $expand ) && ! empty( $expand ) ) {
			$params['$expand'] = $expand;
		}

		return parent::GET( "($id)", $params, get_called_class() . '|' . __FUNCTION__ );
	}

	/**
	 * @deprecated Not allowed in OData
	 *
	 * @param string $endpoint
	 * @param array|object $params
	 * @param string $method_name
	 * @param bool $is_json
	 *
	 * @return mixed|void
	 * @throws Exception
	 */
	final public function GET( $endpoint, $params, $method_name, $is_json = true ) {
		throw new Exception( 'This is OData, not REST, use Search or GetItem' );
	}

	/**
	 * @deprecated Not allowed in OData
	 *
	 * @param string $endpoint
	 * @param array|object $params
	 * @param string $method_name
	 * @param bool $is_json
	 *
	 * @return mixed|void
	 * @throws Exception
	 */
	final public function POST( $endpoint, $params, $method_name, $is_json = true ) {
		throw new Exception( 'This is OData, not REST, use Search or GetItem' );
	}

	/**
	 * @deprecated Not allowed in OData
	 *
	 * @param string $endpoint
	 * @param array|object $params
	 * @param string $method_name
	 * @param bool $is_json
	 *
	 * @return mixed|void
	 * @throws Exception
	 */
	final public function PATCH( $endpoint, $params, $method_name, $is_json = true ) {
		throw new Exception( 'This is OData, not REST, use Search or GetItem' );
	}

	/**
	 * @deprecated Not allowed in OData
	 *
	 * @param string $endpoint
	 * @param array|object $params
	 * @param string $method_name
	 * @param bool $is_json
	 *
	 * @return mixed|void
	 * @throws Exception
	 */
	final public function DELETE( $endpoint, $params, $method_name, $is_json = true ) {
		throw new Exception( 'This is OData, not REST, use Search or GetItem' );
	}

	/**
	 * @deprecated Not allowed in OData
	 *
	 * @param string $endpoint
	 * @param array|object $params
	 * @param string $method_name
	 * @param bool $is_json
	 *
	 * @return mixed|void
	 * @throws Exception
	 */
	final public function PUT( $endpoint, $params, $method_name, $is_json = true ) {
		throw new Exception( 'This is OData, not REST, use Search or GetItem' );
	}
}