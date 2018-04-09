<?php
defined( 'ABSPATH' ) || die( 'This plugin must be run within the scope of WordPress.' );

if ( ! class_exists( 'EduAdminRouter' ) ) {
	class EduAdminRouter {
		public function init() {
			add_action( 'init', array( $this, 'register_post_types' ) );
			add_action( 'init', array( $this, 'register_routes' ) );
			add_action( 'parse_request', array( $this, 'route_requests' ) );
		}

		public function route_requests( $wp_query ) {
			global $wp;

			if ( ! empty( $_GET['debug_route'] ) ) {
				EDU()->write_debug( $wp_query );
			}
		}

		public function register_post_types() {
			/*register_post_type( 'edu_programme', array(
				'public'              => true,
				'label'               => __( 'Programmes', 'eduadmin-booking' ),
				'rewrite'             => array(
					'with_front' => false,
					'slug'       => 'programmes',
				),
				'hierarchical'        => true,
				'capability_type'     => 'post',
				'exclude_from_search' => true,
			) );

			register_post_type( 'edu_coursetemplate', array(
				'public'              => true,
				'label'               => __( 'Course templates', 'eduadmin-booking' ),
				'rewrite'             => array(
					'with_front' => false,
					'slug'       => 'coursetemplates',
				),
				'hierarchical'        => true,
				'capability_type'     => 'post',
				'exclude_from_search' => true,
			) );*/
		}

		public function register_routes() {
			$this->register_programme_routes();
			/*
			 * Course template routes
			 */
		}

		private function register_programme_routes() {
			$programme_list_id   = get_option( 'eduadmin-programme-list' );
			$programme_detail_id = get_option( 'eduadmin-programme-detail' );
			$programme_book_id   = get_option( 'eduadmin-programme-book' );
			/*
			 * Programme routes
			 */

			add_rewrite_tag( '%edu_programme%', '([^&]+)' );

			add_rewrite_rule(
				'programmes/?$',
				'index.php?page_id=' . $programme_list_id,
				'top'
			);

			add_rewrite_rule(
				'programmes/([^/]+)/?$',
				'index.php?page_id=' . $programme_detail_id . '&edu_programme=$matches[1]',
				'top'
			);
			add_rewrite_rule(
				'programmes/([^/]+)/book/?$',
				'index.php?page_id=' . $programme_book_id . '&edu_programme=$matches[1]',
				'top'
			);
		}
	}
}
