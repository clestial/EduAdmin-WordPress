<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class EDU_Integration {
	public $id = '';

	public $plugin_id = 'eduadmin_';

	public $displayName = '';
	public $description = '';

	public $setting_fields = array();

	public $settings = array();
	public $errors = array();

	public $data = array();

	public function __construct() {
	}

	public function get_settings() {
		ob_start();
		$fields = $this->get_form_fields();
		?>
		<table class="form-table">
			<?php
			foreach ( $fields as $key => $field ) {
				echo $this->renderField( $key, $field );
			}
			?>
		</table>
		<?php
		return ob_get_clean();
	}

	private function get_form_fields() {
		return array_map( array( $this, 'set_defaults' ), $this->setting_fields );
	}

	private function renderField( $key, $field ) {
		ob_start();
		?>
		<tr valign="top">
			<th scope="row"><label
						for="<?php echo esc_attr( $this->get_field_key( $key ) ); ?>"><?php echo wp_kses_post( $field['title'] ); ?></label>
			</th>
			<td>
				<fieldset>
					<?php
					switch ( $field['type'] ) {
						case 'checkbox':
							echo $this->renderCheckBox( $key, $field );
							break;
						case 'text':
						case 'password':
							echo $this->renderTextBox( $key, $field );
							break;
						default: // Unhandled field types
							echo '<pre id="' . esc_attr( $this->get_field_key( $key ) ) . '">' . print_r( $field, true ) . '</pre>';
							break;
					}
					?>
				</fieldset>
			</td>
		</tr>
		<?php
		return ob_get_clean();
	}

	private function get_field_key( $key ) {
		return $this->plugin_id . $this->id . '_' . $key;
	}

	private function renderCheckBox( $key, $field ) {
		ob_start();
		?>
		<label>
			<input
					type="checkbox"
					name="<?php echo esc_attr( $this->get_field_key( $key ) ); ?>"
					id="<?php echo esc_attr( $this->get_field_key( $key ) ); ?>"
				<?php checked( $this->get_option( $key ), '1' ); ?>
					value="1"
			/>
			<?php echo $field['description']; ?>
		</label>
		<?php
		return ob_get_clean();
	}

	public function get_option( $key, $empty_value = null ) {
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

		return $this->settings[ $key ];
	}

	public function init_settings() {
		$this->settings = get_option( $this->get_option_key(), null );
		add_action( 'eduadmin-plugin-save_' . $this->id, array( $this, 'save_options' ) );

		if ( ! is_array( $this->settings ) ) {
			$form_fields    = $this->get_form_fields();
			$this->settings = array_merge( array_fill_keys( array_keys( $form_fields ), '' ), wp_list_pluck( $form_fields, 'default' ) );
		}
	}

	private function get_option_key() {
		return $this->plugin_id . $this->id . '_settings';
	}

	private function renderTextBox( $key, $field ) {
		ob_start();
		?>
		<input
				class="regular-text"
				type="<?php echo esc_attr( $field['type'] ); ?>"
				name="<?php echo esc_attr( $this->get_field_key( $key ) ); ?>"
				id="<?php echo esc_attr( $this->get_field_key( $key ) ); ?>"
				placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>"
				value="<?php echo esc_attr( $this->get_option( $key ) ); ?>"
		/>
		<p class="description"><?php echo $field['description']; ?></p>
		<?php
		return ob_get_clean();
	}

	public function save_options() {
		$this->init_settings();

		$post_data = $this->get_post_data();
		$fields    = $this->get_form_fields();

		foreach ( $fields as $key => $field ) {
			try {
				$this->settings[ $key ] = $this->get_field_value( $key, $post_data );
			} catch ( Exception $e ) {
			}
		}

		return update_option( $this->get_option_key(), $this->settings );
	}

	private function get_post_data() {
		if ( ! empty( $this->data ) && is_array( $this->data ) ) {
			return $this->data;
		}

		return $_POST;
	}

	private function get_field_value( $key, $post_data = array() ) {
		$fKey      = $this->get_field_key( $key );
		$post_data = empty( $post_data ) ? $_POST : $post_data;
		$value     = isset( $post_data[ $fKey ] ) ? $post_data[ $fKey ] : null;

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