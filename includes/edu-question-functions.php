<?php
// Render ALL the types
function render_question( $question ) {
	$t = EDU()->start_timer( __METHOD__ );
	switch ( $question['QuestionType'] ) {
		case 'Text':
			edu_render_text_question( $question );
			break;
		case 'Checkbox':
			edu_render_checkbox_question( $question );
			break;
		case 'Radiobutton':
			edu_render_radio_question( $question );
			break;
		case 'Numeric':
			edu_render_number_question( $question );
			break;
		case 'Textarea':
			edu_render_note_question( $question );
			break;
		case 'Info':
			edu_render_info_text( $question );
			break;
		case 'Date':
			edu_render_date_question( $question );
			break;
		case 'Dropdown':
			edu_render_drop_list_question( $question );
			break;
		default:
			EDU()->write_debug( $question );
			break;
	}
	EDU()->stop_timer( $t );
}

function edu_render_note_question( $question ) {
	echo '<label><h3 class="inputLabel noteQuestion">' . esc_html( wp_strip_all_tags( $question['QuestionText'] ) ) . ( ! empty( $question['Price'] ) ? ' <i class="priceLabel">(' . esc_html( convert_to_money( $question['Price'] ) ) . ')</i>' : '' ) . '</h3>';
	echo '<div class="inputHolder">';
	echo '<textarea placeholder="' . esc_attr( $question['QuestionText'] ) . '" name="question_' . esc_attr( $question['AnswerId'] ) . '_note" data-type="note" onchange="eduBookingView.UpdatePrice();" data-price="' . esc_attr( $question['Price'] ) . '"' . ( $question['Mandatory'] ? ' required="required"' : '' ) . ' resizable="resizable" class="questionNoteField" rows="3">' . esc_textarea( $question['DefaultAnswer'] ) . '</textarea>';
	echo '</div></label>';
}

function edu_render_checkbox_question( $question ) {
	echo '<h3 class="inputLabel checkBoxQuestion noHide">' . esc_html( wp_strip_all_tags( $question['QuestionText'] ) ) . '</h3>';
	foreach ( $question['Alternatives'] as $q ) {
		echo '<label>';
		echo '<div class="inputHolder">';
		echo '<input type="checkbox" class="questionCheck" data-type="check" data-price="' . esc_attr( $q['Price'] ) . '" onchange="eduBookingView.UpdatePrice();" name="question_' . esc_attr( $q['AnswerId'] . '_check' ) . '"' . ( $question['Mandatory'] ? ' required="required"' : '' ) . ' value="' . esc_attr( $q['AnswerId'] ) . '" /> ';
		echo esc_html( wp_strip_all_tags( $q['AnswerText'] ) );
		if ( ! empty( $q['Price'] ) ) {
			echo ' <i class="priceLabel">(' . esc_html( convert_to_money( $q['Price'] ) ) . ')</i>';
		}
		echo '</div>';
		echo '</label>';
	}
}

function edu_render_date_question( $question ) {
	echo '<label>';
	echo '<div class="inputLabel noHide">';
	echo esc_html( wp_strip_all_tags( $question['QuestionText'] ) ) . ( ! empty( $question['Price'] ) ? ' <i class="priceLabel">(' . esc_html( convert_to_money( $question['Price'] ) ) . ')</i>' : '' );
	echo '</div>';
	echo '<div class="inputHolder">';
	echo '<input type="date" class="questionDate" data-type="date" onchange="eduBookingView.UpdatePrice();" data-price="' . esc_attr( $question['Price'] ) . '"' . ( $question['Mandatory'] ? ' required="required"' : '' ) . ' name="question_' . esc_attr( $question['AnswerId'] . '_date' ) . '" />';
	if ( $question['HasTimeField'] ) {
		echo '<input type="time" onchange="eduBookingView.UpdatePrice();" class="questionTime"' . ( $question['Mandatory'] ? ' required="required"' : '' ) . ' name="question_' . esc_attr( $question['AnswerId'] . '_time' ) . '" />';
	}
	echo '</div>';
	echo '</label>';
}

function edu_render_drop_list_question( $question ) {
	echo '<label>';
	echo '<div class="inputLabel noHide">';
	echo esc_html( wp_strip_all_tags( $question['QuestionText'] ) );
	echo '</div>';
	echo '<div class="inputHolder">';
	echo '<select class="questionDropdown" onchange="eduBookingView.UpdatePrice();"' . ( $question['Mandatory'] ? ' required="required"' : '' ) . ' name="question_' . esc_attr( $question->QuestionID . '_dropdown' ) . '">';
	foreach ( $question['Alternatives'] as $q ) {
		echo '<option value="' . esc_attr( $q['AnswerId'] ) . '"' . ( $q['Selected'] ? ' selected="selected"' : '' ) . ' data-type="dropdown" data-price="' . esc_attr( $q['Price'] ) . '">';
		echo esc_html( wp_strip_all_tags( $q['AnswerText'] ) );
		if ( ! empty( $q['Price'] ) ) {
			echo ' (' . esc_html( convert_to_money( $q['Price'] ) ) . ')';
		}
		echo '</option>';
	}

	echo '</select>';
	echo '</div>';
	echo '</label>';
}

function edu_render_number_question( $question ) {
	echo '<label>';
	echo '<div class="inputLabel noHide">';
	echo esc_html( wp_strip_all_tags( $question['QuestionText'] ) );
	echo '</div>';
	echo '<div class="inputHolder">';
	echo '<input type="number" class="questionText" onchange="eduBookingView.UpdatePrice();"' . ( $question['Mandatory'] ? ' required="required"' : '' ) . ' data-price="' . esc_attr( $question['Price'] ) . '" min="0" data-type="number" name="question_' . esc_attr( $question->Answers->EventBookingAnswer->AnswerID . '_number' ) . '" placeholder="' . esc_attr__( 'Quantity', 'eduadmin-booking' ) . '" />';
	if ( ! empty( $question['Price'] ) ) {
		/* translators: 1: Price */
		echo ' <i class="priceLabel">(' . esc_html( sprintf( __( '%1$s / pcs', 'eduadmin-booking' ), convert_to_money( $question['Price'] ) ) ) . ')</i>';
	}
	echo '</div>';
	echo '</label>';
}

function edu_render_info_text( $question ) {
	if ( ! empty( $question['QuestionText'] ) ) {
		echo '<h3 class="inputLabel questionInfoQuestion">' . esc_html( wp_strip_all_tags( $question['QuestionText'] ) ) . '</h3>';
		echo '<div class="questionInfoText" data-type="infotext" data-price="' . esc_attr( $question->Answers->EventBookingAnswer->Price ) . '">';
		echo wp_kses( $question->Answers->EventBookingAnswer->AnswerText, wp_kses_allowed_html( 'post' ) );
		echo '</div>';
	}
}

function edu_render_radio_question( $question ) {
	echo '<h3 class="inputLabel radioQuestion">' . esc_html( wp_strip_all_tags( $question['QuestionText'] ) ) . '</h3>';
	foreach ( $question['Alternatives'] as $q ) {
		echo '<label class="questionRadioVertical">';
		echo '<div class="inputHolder">';
		echo '<input type="radio" class="questionRadio" data-type="radio"' . ( $question['Mandatory'] ? ' required="required"' : '' ) . ' data-price="' . esc_attr( $q['Price'] ) . '" name="question_' . esc_attr( $question->QuestionID . '_radio' ) . '" value="' . esc_attr( $q['AnswerId'] ) . '" /> ';
		echo esc_html( wp_strip_all_tags( $q['AnswerText'] ) );
		if ( ! empty( $q['Price'] ) ) {
			echo ' <i class="priceLabel">(' . esc_html( convert_to_money( $q['Price'] ) ) . ')</i>';
		}
		echo '</div>';
		echo '</label>';
	}
}

function edu_render_text_question( $question ) {
	echo '<label>';
	echo '<div class="inputLabel noHide">';
	echo esc_html( wp_strip_all_tags( $question['QuestionText'] ) ) . ( ! empty( $question['Price'] ) ? ' <i class="priceLabel">(' . esc_html( convert_to_money( $question['Price'] ) ) . ')</i>' : '' );
	echo '</div>';
	echo '<div class="inputHolder">';
	echo '<input type="text" data-price="' . esc_attr( $question['Price'] ) . '"' . ( $question['Mandatory'] ? ' required="required"' : '' ) . ' onchange="eduBookingView.UpdatePrice();" data-type="text" class="questionText" name="question_' . esc_attr( $question['AnswerId'] . '_text' ) . '" />';
	echo '</div>';
	echo '</label>';
}
