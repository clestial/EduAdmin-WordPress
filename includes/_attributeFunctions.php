<?php
function render_attribute( $attribute, $multiple = false, $suffix = '', $data = null ) {
	$t = EDU()->start_timer( __METHOD__ );
	switch ( $attribute->AttributeTypeID ) {
		case 1: // Checkbox
			render_check_field( $attribute, $multiple, $suffix, $data );
			break;
		case 2: // Textfält
			render_text_field( $attribute, $multiple, $suffix, $data );
			break;
		case 3: // Nummerfält
			render_number_field( $attribute, $multiple, $suffix, $data );
			break;
		case 4: // Flervärdesfält
			//renderTextField($attribute, $multiple, $suffix, $data);
			break;
		case 5: // Dropdownlista
			render_select_field( $attribute, $multiple, $suffix, $data );
			break;
		case 6: // Anteckningsfält
			render_textarea_field( $attribute, $multiple, $suffix, $data );
			break;
		case 7: // Datumfält
			//renderDateField($attribute, $multiple, $suffix, $data);
			break;
		case 8: // HTML
			//renderTextAreaField($attribute, $multiple, $suffix, $data);
			break;
		case 9: // Checkboxlista
			//renderCheckboxListField($attribute, $multiple, $suffix, $data);
			break;
		case 10: // Pinkod
			break;
		default:
			render_debug_attribute( $attribute );
			break;
	}
	EDU()->stop_timer( $t );
}

function render_check_field( $attribute, $multiple, $suffix, $data ) {
	echo '<label><div class="inputLabel noHide">';
	echo wp_kses( $attribute->AttributeDescription, wp_kses_allowed_html( 'post' ) );
	echo '</div><div class="inputHolder">';
	echo '<input type="checkbox"' . ( null !== $data && $data ? ' checked="checked"' : '' ) . ' placeholder="' . esc_attr( wp_strip_all_tags( $attribute->AttributeDescription ) ) . '" name="edu-attr_' . esc_attr( $attribute->AttributeID . ( '' !== $suffix ? '-' . $suffix : '' ) . ( $multiple ? '[]' : '' ) ) . '" />';
	echo '</div></label>';
}

function render_text_field( $attribute, $multiple, $suffix, $data ) {
	echo '<label><div class="inputLabel">';
	echo wp_kses( $attribute->AttributeDescription, wp_kses_allowed_html( 'post' ) );
	echo '</div><div class="inputHolder">';
	echo '<input type="text" placeholder="' . esc_attr( wp_strip_all_tags( $attribute->AttributeDescription ) ) . '" name="edu-attr_' . esc_attr( $attribute->AttributeID . ( '' !== $suffix ? '-' . $suffix : '' ) . ( $multiple ? '[]' : '' ) ) . '" value="' . esc_attr( ( null !== $data ? $data : $attribute->AttributeValue ) ) . '" />';
	echo '</div></label>';
}

function render_number_field( $attribute, $multiple, $suffix, $data ) {
	echo '<label><div class="inputLabel">';
	echo wp_kses( $attribute->AttributeDescription, wp_kses_allowed_html( 'post' ) );
	echo '</div><div class="inputHolder">';
	echo '<input type="number" placeholder="' . esc_attr( wp_strip_all_tags( $attribute->AttributeDescription ) ) . '" name="edu-attr_' . esc_attr( $attribute->AttributeID . ( '' !== $suffix ? '-' . $suffix : '' ) . ( $multiple ? '[]' : '' ) ) . '" value="' . esc_attr( null !== $data ? $data : $attribute->AttributeValue ) . '" />';
	echo '</div></label>';
}

function render_date_field( $attribute, $multiple, $suffix, $data ) {
	echo '<label><div class="inputLabel">';
	echo wp_kses( $attribute->AttributeDescription, wp_kses_allowed_html( 'post' ) );
	echo '</div><div class="inputHolder">';
	echo '<input type="date" placeholder="' . esc_attr( wp_strip_all_tags( $attribute->AttributeDescription ) ) . '" name="edu-attr_' . esc_attr( $attribute->AttributeID . ( '' !== $suffix ? '-' . $suffix : '' ) . ( $multiple ? '[]' : '' ) ) . '" />';
	echo '</div></label>';
}

function render_textarea_field( $attribute, $multiple, $suffix, $data ) {
	echo '<label><div class="inputLabel">';
	echo wp_kses( $attribute->AttributeDescription, wp_kses_allowed_html( 'post' ) );
	echo '</div><div class="inputHolder">';
	echo '<textarea placeholder="' . esc_attr( wp_strip_all_tags( $attribute->AttributeDescription ) ) . '" name="edu-attr_' . esc_attr( $attribute->AttributeID . ( '' !== $suffix ? '-' . $suffix : '' ) . ( $multiple ? '[]' : '' ) ) . '" rows="3" resizable="resizable">' . esc_textarea( null !== $data ? $data : $attribute->AttributeValue ) . '</textarea>';
	echo '</div></label>';
}

function render_select_field( $attribute, $multiple, $suffix, $data ) {
	echo '<label><div class="inputLabel">';
	echo wp_kses( $attribute->AttributeDescription, wp_kses_allowed_html( 'post' ) );
	echo '</div><div class="inputHolder">';
	echo '<select name="edu-attr_' . esc_attr( $attribute->AttributeID . ( '' !== $suffix ? '-' . $suffix : '' ) . ( $multiple ? '[]' : '' ) ) . "\">\n";
	if ( is_array( $attribute->AttributeAlternative ) ) {
		foreach ( $attribute->AttributeAlternative as $val ) {
			echo "\t<option" . ( null !== $data && $data === $val->AttributeAlternativeID ? ' selected="selected"' : '' ) . ' value="' . esc_attr( $val->AttributeAlternativeID ) . '">' . esc_html( $val->AttributeAlternativeDescription ) . "</option>\n";
		}
	} else {
		$val = $attribute->AttributeAlternative;
		echo "\t<option" . ( null !== $data && $data === $val->AttributeAlternativeID ? ' selected="selected"' : '' ) . ' value="' . esc_attr( $val->AttributeAlternativeID ) . '">' . esc_html( $val->AttributeAlternativeDescription ) . "</option>\n";
	}
	echo '</select>';
	echo '</div></label>';
}

function render_checkbox_list_field( $attribute, $multiple, $suffix, $data ) {
	echo '<div class="inputLabel">';
	echo wp_kses( $attribute->AttributeDescription, wp_kses_allowed_html( 'post' ) );
	echo '</div><div class="inputHolder">';
	if ( is_array( $attribute->AttributeAlternative ) ) {
		foreach ( $attribute->AttributeAlternative as $val ) {
			echo "\t<label><input" . ( null !== $data && $data === $val->AttributeAlternativeID ? ' checked="checked"' : '' ) . ' type="checkbox" name="edu-attr_' . esc_attr( $attribute->AttributeID . ( '' !== $suffix ? '-' . $suffix : '' ) . ( $multiple ? '[]' : '' ) ) . '" value="' . esc_attr( $val->AttributeAlternativeID ) . '">' . esc_html( $val->AttributeAlternativeDescription ) . "</label>\n";
		}
	} else {
		$val = $attribute->AttributeAlternative;
		echo "\t<label><input" . ( null !== $data && $data === $val->AttributeAlternativeID ? " checked=\"checked\"" : "" ) . " type=\"checkbox\" name=\"edu-attr_" . $attribute->AttributeID . ( $suffix != "" ? "-" . $suffix : "" ) . ( $multiple ? "[]" : "" ) . "\" value=\"" . $val->AttributeAlternativeID . "\">" . $val->AttributeAlternativeDescription . "</label>\n";
	}
	echo '</div>';
}

function render_debug_attribute( $attribute ) {
	echo '<label><div class="inputLabel">';
	echo wp_kses( $attribute->AttributeDescription, wp_kses_allowed_html( 'post' ) );
	echo '</div><div class="inputHolder">';
	EDU()->write_debug( $attribute );
	echo '</div></label>';
}
