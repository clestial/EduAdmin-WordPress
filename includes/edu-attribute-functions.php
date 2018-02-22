<?php
/**
 * @param                            $attribute
 * @param bool                       $multiple
 * @param string                     $suffix
 * @param stdClass|array|object|null $data
 */
function render_attribute( $attribute, $multiple = false, $suffix = '', $data = null ) {
	$t = EDU()->start_timer( __METHOD__ );

	switch ( $attribute['CustomFieldType'] ) {
		case 'Checkbox': // Checkbox
			render_check_field( $attribute, $multiple, $suffix, $data );
			break;
		case 'Text': // Textfält
			render_text_field( $attribute, $multiple, $suffix, $data );
			break;
		case 'Numeric': // Nummerfält
			render_number_field( $attribute, $multiple, $suffix, $data );
			break;
		case 'Dropdown': // Dropdownlista
			render_select_field( $attribute, $multiple, $suffix, $data );
			break;
		case 'Textarea': // Anteckningsfält
			render_textarea_field( $attribute, $multiple, $suffix, $data );
			break;
		case 'Date':
			render_date_field( $attribute, $multiple, $suffix, $data );
			break;
		case 'CheckboxList':
			//render_checkbox_list_field( $attribute, $multiple, $suffix, $data );
			break;
		case 'Html':
			break;
		default:
			render_debug_attribute( $attribute );
			break;
	}
	EDU()->stop_timer( $t );
}

function render_check_field( $attribute, $multiple, $suffix, $data ) {
	echo '<label><div class="inputLabel noHide">';
	echo wp_kses( $attribute['CustomFieldName'], wp_kses_allowed_html( 'post' ) );
	echo '</div><div class="inputHolder">';
	echo '<input type="checkbox"' . ( ! empty( $data ) && $data ? ' checked="checked"' : '' ) . ' placeholder="' . esc_attr( wp_strip_all_tags( $attribute['CustomFieldName'] ) ) . '" name="edu-attr_' . esc_attr( $attribute['CustomFieldId'] . ( '' !== $suffix ? '-' . $suffix : '' ) . ( $multiple ? '[]' : '' ) ) . '" />';
	echo '</div></label>';
}

function render_text_field( $attribute, $multiple, $suffix, $data ) {
	echo '<label><div class="inputLabel">';
	echo wp_kses( $attribute['CustomFieldName'], wp_kses_allowed_html( 'post' ) );
	echo '</div><div class="inputHolder">';
	echo '<input type="text" placeholder="' . esc_attr( wp_strip_all_tags( $attribute['CustomFieldName'] ) ) . '" name="edu-attr_' . esc_attr( $attribute['CustomFieldId'] . ( '' !== $suffix ? '-' . $suffix : '' ) . ( $multiple ? '[]' : '' ) ) . '" value="' . esc_attr( ( ! empty( $data ) ? $data : '' ) ) . '" />';
	echo '</div></label>';
}

function render_number_field( $attribute, $multiple, $suffix, $data ) {
	echo '<label><div class="inputLabel">';
	echo wp_kses( $attribute['CustomFieldName'], wp_kses_allowed_html( 'post' ) );
	echo '</div><div class="inputHolder">';
	echo '<input type="number" placeholder="' . esc_attr( wp_strip_all_tags( $attribute['CustomFieldName'] ) ) . '" name="edu-attr_' . esc_attr( $attribute['CustomFieldId'] . ( '' !== $suffix ? '-' . $suffix : '' ) . ( $multiple ? '[]' : '' ) ) . '" value="' . esc_attr( ! empty( $data ) ? $data : '' ) . '" />';
	echo '</div></label>';
}

function render_date_field( $attribute, $multiple, $suffix, $data ) {
	echo '<label><div class="inputLabel">';
	echo wp_kses( $attribute['CustomFieldName'], wp_kses_allowed_html( 'post' ) );
	echo '</div><div class="inputHolder">';
	echo '<input type="date" placeholder="' . esc_attr( wp_strip_all_tags( $attribute['CustomFieldName'] ) ) . '" name="edu-attr_' . esc_attr( $attribute['CustomFieldId'] . ( '' !== $suffix ? '-' . $suffix : '' ) . ( $multiple ? '[]' : '' ) ) . '" value="' . esc_attr( $data ) . '" />';
	echo '</div></label>';
}

function render_textarea_field( $attribute, $multiple, $suffix, $data ) {
	echo '<label><div class="inputLabel">';
	echo wp_kses( $attribute['CustomFieldName'], wp_kses_allowed_html( 'post' ) );
	echo '</div><div class="inputHolder">';
	echo '<textarea placeholder="' . esc_attr( wp_strip_all_tags( $attribute['CustomFieldName'] ) ) . '" name="edu-attr_' . esc_attr( $attribute['CustomFieldId'] . ( '' !== $suffix ? '-' . $suffix : '' ) . ( $multiple ? '[]' : '' ) ) . '" rows="3" resizable="resizable">' . esc_textarea( ! empty( $data ) ? $data : '' ) . '</textarea>';
	echo '</div></label>';
}

function render_select_field( $attribute, $multiple, $suffix, $data ) {
	echo '<label><div class="inputLabel">';
	echo wp_kses( $attribute['CustomFieldName'], wp_kses_allowed_html( 'post' ) );
	echo '</div><div class="inputHolder">';
	echo '<select name="edu-attr_' . esc_attr( $attribute['CustomFieldId'] . ( '' !== $suffix ? '-' . $suffix : '' ) . ( $multiple ? '[]' : '' ) ) . "\">\n";
	foreach ( $attribute['CustomFieldAlternatives'] as $val ) {
		echo "\t<option" . ( ! empty( $data ) && $data === $val['CustomFieldAlternativeId'] ? ' selected="selected"' : '' ) . ' value="' . esc_attr( $val['CustomFieldAlternativeId'] ) . '">' . esc_html( $val['CustomFieldAlternativeValue'] ) . "</option>\n";
	}
	echo '</select>';
	echo '</div></label>';
}

function render_checkbox_list_field( $attribute, $multiple, $suffix, $data ) {
	echo '<div class="inputLabel"><label>';
	echo wp_kses( $attribute['CustomFieldName'], wp_kses_allowed_html( 'post' ) );
	echo '</label></div><div class="inputHolder">';
	foreach ( $attribute['CustomFieldAlternatives'] as $val ) {
		echo "\t<label><input" . ( ! empty( $data ) && $data === $val['CustomFieldAlternativeId'] ? ' checked="checked"' : '' ) . ' type="checkbox" name="edu-attr_' . esc_attr( $attribute['CustomFieldId'] . ( '' !== $suffix ? '-' . $suffix : '' ) . ( $multiple ? '[]' : '' ) ) . '" value="' . esc_attr( $val['CustomFieldAlternativeId'] ) . '">' . esc_html( $val['CustomFieldAlternativeValue'] ) . "</label>\n";
	}
	echo '</div>';
}

function render_debug_attribute( $attribute ) {
	echo '<label><div class="inputLabel">';
	echo wp_kses( $attribute['CustomFieldName'], wp_kses_allowed_html( 'post' ) );
	echo '</div><div class="inputHolder">';
	EDU()->write_debug( $attribute );
	echo '</div></label>';
}
