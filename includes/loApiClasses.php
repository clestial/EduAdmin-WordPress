<?php

/**
 * Class XFilter
 */
class XFilter {
	/**
	 * @var string
	 */
	var $FilterName = '';
	/**
	 * @var string
	 */
	var $FilterCondition = '';
	/**
	 * @var string
	 */
	var $FilterValue = '';

	/**
	 * XFilter constructor.
	 *
	 * @param $name         string  Namnet på fältet du vill filtrera på
	 * @param $condition    string  Vilken sorts condition du vill köra
	 * @param $value        string  Värdet du filtrerar på
	 */
	public function __construct( $name, $condition, $value ) {
		$this->FilterName      = $name;
		$this->FilterCondition = $condition;
		$this->FilterValue     = $value;
	}
}

/**
 * Class XFiltering
 */
class XFiltering {
	/**
	 * @var string
	 */
	var $pre = '<Filtering>';
	/**
	 * @var string
	 */
	var $post = '</Filtering>';
	/**
	 * @var array
	 */
	var $filterItems = array();

	/**
	 * XFiltering constructor.
	 */
	public function __construct() {
		$this->filterItems = array();
	}

	/**
	 * @param XFilter $filter
	 */
	public function AddItem( XFilter $filter ) {
		$this->filterItems[] = $filter;
	}

	/**
	 * @return string Serializes the filter-items to xml
	 */
	public function ToString() {
		$xml = $this->pre;
		foreach ( $this->filterItems as $filter ) {
			$xml .= '<Filter>';
			$xml .= '<FilterName>' . $filter->FilterName . '</FilterName>';
			$xml .= '<FilterCondition>' . str_replace( array(
				                                           '<',
				                                           '>',
			                                           ), array(
				                                           '&lt;',
				                                           '&gt;',
			                                           ), $filter->FilterCondition ) . '</FilterCondition>';
			$xml .= '<FilterValue>' . $filter->FilterValue . '</FilterValue>';
			$xml .= '</Filter>';
		}
		$xml .= $this->post;

		return $xml;
	}
}

/**
 * Class XSort
 */
class XSort {
	/**
	 * @var string
	 */
	var $SortName = '';
	/**
	 * @var string
	 */
	var $SortDirection = 'ASC';

	/**
	 * XSort constructor.
	 *
	 * @param        $name
	 * @param string $direction
	 */
	public function __construct( $name, $direction = 'ASC' ) {
		$this->SortName      = $name;
		$this->SortDirection = $direction;
	}
}

/**
 * Class XSorting
 */
class XSorting {
	/**
	 * @var string
	 */
	var $pre = '<Sorting>';
	/**
	 * @var string
	 */
	var $post = '</Sorting>';
	/**
	 * @var array
	 */
	var $sortItems = array();

	/**
	 * XSorting constructor.
	 */
	public function __construct() {
		$this->sortItems = array();
	}

	/**
	 * @param XSort $sort
	 */
	public function AddItem( XSort $sort ) {
		$this->sortItems[] = $sort;
	}

	/**
	 * @return string
	 */
	public function ToString() {
		$xml = $this->pre;
		foreach ( $this->sortItems as $sort ) {
			$xml .= '<Sort>';
			$xml .= '<SortName>' . $sort->SortName . '</SortName>';
			$xml .= '<SortDirection>' . $sort->SortDirection . '</SortDirection>';
			$xml .= '</Sort>';
		}
		$xml .= $this->post;

		return $xml;
	}
}