<?php
// phpcs:disable WordPress.NamingConventions
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class EDU_Integration {
	public $id             = '';
	public $plugin_id      = 'eduadmin_';
	public $displayName    = '';
	public $description    = '';
	public $setting_fields = array();
	public $settings       = array();
	public $errors         = array();
	public $data           = array();

	public function __construct() {
	}

	public function get_settings() {
		$t = EDU()->start_timer( __METHOD__ );
		ob_start();
		$fields = $this->get_form_fields();
		?>
		<table class="form-table">
			<?php
			foreach ( $fields as $key => $field ) {
				$this->render_field( $key, $field );
			}
			?>
		</table>
		<?php
		EDU()->stop_timer( $t );

		return ob_get_clean();
	}

	private function get_form_fields() {
		return array_map( array( $this, 'set_defaults' ), $this->setting_fields );
	}

	private function render_field( $key, $field ) {
		$t = EDU()->start_timer( __METHOD__ );
		?>
		<tr valign="top">
			<th scope="row">
				<label for="<?php echo esc_attr( $this->get_field_key( $key ) ); ?>"><?php echo wp_kses_post( $field['title'] ); ?></label>
			</th>
			<td>
				<fieldset>
					<?php
					switch ( $field['type'] ) {
						case 'checkbox':
							$this->render_check_box( $key, $field );
							break;
						case 'text':
						case 'password':
						case 'number':
						case 'email':
						case 'phone':
							$this->render_text_box( $key, $field );
							break;
						default: // Unhandled field types
							EDU()->write_debug( $field );
							break;
					}
					?>
				</fieldset>
			</td>
		</tr>
		<?php
		EDU()->stop_timer( $t );
	}

	private function get_field_key( $key ) {
		return $this->plugin_id . $this->id . '_' . $key;
	}

	private function render_check_box( $key, $field ) {
		$t = EDU()->start_timer( __METHOD__ );
		?>
		<label>
			<input type="checkbox" name="<?php echo esc_attr( $this->get_field_key( $key ) ); ?>" id="<?php echo esc_attr( $this->get_field_key( $key ) ); ?>"
				<?php checked( $this->get_option( $key ), '1' ); ?>
					value="1"/>
			<?php echo esc_html( $field['description'] ); ?>
		</label>
		<?php
		EDU()->stop_timer( $t );
	}

	public function get_option( $key, $empty_value = null ) {
		$t = EDU()->start_timer( __METHOD__ );
		if ( empty( $this->settings ) ) {
			$this->init_settings();
		}

		if ( ! isset( $this->settings[ $key ] ) ) {
			$form_fields            = $this->get_form_fields();
			$this->settings[ $key ] = isset( $form_fields[ $key ] ) ? $form_fields[ $key ]['default'] : '';
		}

		if ( ! is_null( $empty_value ) && '' === $this->settings[ $key ] ) {
			$this->settings[ $key ] = $empty_value;
		}
		EDU()->stop_timer( $t );

		return $this->settings[ $key ];
	}

	public function init_settings() {
		$t              = EDU()->start_timer( __METHOD__ );
		$this->settings = get_option( $this->get_option_key(), null );
		add_action( 'eduadmin-plugin-save_' . $this->id, array( $this, 'save_options' ) );

		if ( ! is_array( $this->settings ) ) {
			$form_fields    = $this->get_form_fields();
			$this->settings = array_merge( array_fill_keys( array_keys( $form_fields ), '' ), wp_list_pluck( $form_fields, 'default' ) );
		}
		EDU()->stop_timer( $t );
	}

	private function get_option_key() {
		return $this->plugin_id . $this->id . '_settings';
	}

	private function render_text_box( $key, $field ) {
		$t = EDU()->start_timer( __METHOD__ );
		?>
		<input class="regular-text" type="<?php echo esc_attr( $field['type'] ); ?>" name="<?php echo esc_attr( $this->get_field_key( $key ) ); ?>" id="<?php echo esc_attr( $this->get_field_key( $key ) ); ?>" placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" value="<?php echo esc_attr( $this->get_option( $key ) ); ?>"/>
		<p class="description"><?php echo esc_html( $field['description'] ); ?></p>
		<?php
		EDU()->stop_timer( $t );
	}

	public function save_options() {
		$t = EDU()->start_timer( __METHOD__ );
		if ( wp_verify_nonce( $_POST['plugin-settings-nonce'], 'eduadmin-plugin-settings' ) ) {
			$this->init_settings();

			$post_data = $this->get_post_data();
			$fields    = $this->get_form_fields();

			foreach ( $fields as $key => $field ) {
				try {
					$this->settings[ $key ] = $this->get_field_value( $key, $post_data );
				} catch ( Exception $e ) {
					// Ignore problems with saving options.
				}
			}
			EDU()->stop_timer( $t );

			return update_option( $this->get_option_key(), $this->settings );
		}

		return false;
	}

	private function get_post_data() {
		if ( ! empty( $this->data ) && is_array( $this->data ) ) {
			return $this->data;
		}
		if ( wp_verify_nonce( $_POST['plugin-settings-nonce'], 'eduadmin-plugin-settings' ) ) {
			return $_POST; // Input var okay.
		}

		return null;
	}

	private function get_field_value( $key, $post_data = array() ) {
		$f_key = $this->get_field_key( $key );
		if ( wp_verify_nonce( $_POST['plugin-settings-nonce'], 'eduadmin-plugin-settings' ) ) {
			$post_data = empty( $post_data ) ? $_POST : $post_data; // Input var okay.
		} else {
			$post_data = null;
		}
		$value = isset( $post_data[ $f_key ] ) ? $post_data[ $f_key ] : null;

		return $value;
	}

	private function set_post_data( $data = array() ) {
		$this->data = $data;
	}

	private function set_defaults( $field ) {
		$defaults = array(
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'type'              => 'text',
			'description'       => '',
			'default'           => '',
			'custom_attributes' => array(),
		);
		$field    = wp_parse_args( $field, $defaults );

		return $field;
	}
}
