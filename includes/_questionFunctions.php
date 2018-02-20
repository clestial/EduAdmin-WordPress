<?php
// Render ALL the types
function render_question( $question ) {
	$t = EDU()->start_timer( __METHOD__ );
	switch ( $question->QuestionTypeID ) {
		case 1: // Text-fråga
			edu_render_text_question( $question );
			break;
		case 2: // Checkbox-fråga
			edu_render_checkbox_question( $question );
			break;
		case 3: // Radio - Vertikal
			edu_render_radio_question( $question, 'vertical' );
			break;
		case 4: // Nummerfråga
			edu_render_number_question( $question );
			break;
		case 5: // Anteckningar
			edu_render_note_question( $question );
			break;
		case 6: // Infotext - hel rad
			edu_render_info_text( $question );
			break;
		case 7: // Radbrytning
			break;
		case 8: // Datum-fråga
			edu_render_date_question( $question );
			break;
		case 9: // Infotext - halv rad
			edu_render_info_text( $question );
			break;
		case 10: // Radio - horisontell
			edu_render_radio_question( $question, 'horizontal' );
			break;
		case 11: // Droplist-fråga
			edu_render_drop_list_question( $question );
			break;
		default:
			EDU()->write_debug( $question );
			break;
	}
	EDU()->stop_timer( $t );
}

// QuestionTypeID 5
function edu_render_note_question( $question ) {
	echo "<label><h3 class=\"inputLabel noteQuestion\">" . $question->QuestionText . ( $question->Answers->EventBookingAnswer->Price > 0 ? " <i class=\"priceLabel\">(" . convert_to_money( $question->Answers->EventBookingAnswer->Price ) . ")</i>" : "" ) . "</h3>";
	echo "<div class=\"inputHolder\">";
	echo "<textarea placeholder=\"" . wp_strip_all_tags( $question->QuestionText ) . "\" name=\"question_" . $question->Answers->EventBookingAnswer->AnswerID . "_note\" data-type=\"note\" onchange=\"eduBookingView.UpdatePrice();\" data-price=\"" . $question->Answers->EventBookingAnswer->Price . "\"" . ( $question->Mandatory ? " required=\"required\"" : "" ) . " resizable=\"resizable\" class=\"questionNoteField\" rows=\"3\">" . $question->Answers->EventBookingAnswer->DefaultAnswerText . "</textarea>";
	echo "</div></label>";
}

// QuestionTypeID 2
function edu_render_checkbox_question( $question ) {
	echo "<h3 class=\"inputLabel checkBoxQuestion noHide\">" . $question->QuestionText . "</h3>";
	if ( is_array( $question->Answers->EventBookingAnswer ) ) {
		foreach ( $question->Answers->EventBookingAnswer as $q ) {
			echo "<label>";
			echo "<div class=\"inputHolder\">";
			echo "<input type=\"checkbox\" class=\"questionCheck\" data-type=\"check\" data-price=\"" . $q->Price . "\" onchange=\"eduBookingView.UpdatePrice();\" name=\"question_" . $question->QuestionID . "_check\"" . ( $q->DefaultAlternative == 1 ? " checked=\"checked\"" : "" ) . "" . ( $question->Mandatory ? " required=\"required\"" : "" ) . " value=\"" . $q->AnswerID . "\" /> ";
			echo $q->AnswerText;
			if ( $q->Price > 0 ) {
				echo " <i class=\"priceLabel\">(" . convert_to_money( $q->Price ) . ")</i>";
			}
			echo "</div>";
			echo "</label>";
		}
	} else if ( is_object( $question->Answers->EventBookingAnswer ) ) {
		$q = $question->Answers->EventBookingAnswer;
		echo "<label>";
		echo "<div class=\"inputHolder\">";
		echo "<input type=\"checkbox\" class=\"questionCheck\" data-type=\"check\" data-price=\"" . $q->Price . "\" onchange=\"eduBookingView.UpdatePrice();\"" . ( $question->Mandatory ? " required=\"required\"" : "" ) . " name=\"question_" . $question->QuestionID . "_check\"" . ( $q->DefaultAlternative == 1 ? " checked=\"checked\"" : "" ) . " value=\"" . $q->AnswerID . "\" /> ";
		echo $q->AnswerText;
		if ( $q->Price > 0 ) {
			echo " <i class=\"priceLabel\">(" . convert_to_money( $q->Price ) . ")</i>";
		}
		echo "</div>";
		echo "</label>";
	}
}

// QuestionTypeID 8
function edu_render_date_question( $question ) {
	echo "<label>";
	echo "<div class=\"inputLabel noHide\">";
	echo $question->QuestionText . ( $question->Answers->EventBookingAnswer->Price > 0 ? " <i class=\"priceLabel\">(" . convert_to_money( $question->Answers->EventBookingAnswer->Price ) . ")</i>" : "" );
	echo "</div>";
	echo "<div class=\"inputHolder\">";
	echo "<input type=\"date\" class=\"questionDate\" data-type=\"date\" onchange=\"eduBookingView.UpdatePrice();\" data-price=\"" . $question->Answers->EventBookingAnswer->Price . "\"" . ( $question->Mandatory ? " required=\"required\"" : "" ) . " name=\"question_" . $question->Answers->EventBookingAnswer->AnswerID . "_date\" />";
	if ( $question->Time == 1 ) {
		echo "<input type=\"time\" onchange=\"eduBookingView.UpdatePrice();\" class=\"questionTime\"" . ( $question->Mandatory ? " required=\"required\"" : "" ) . " name=\"question_" . $question->Answers->EventBookingAnswer->AnswerID . "_time\" />";
	}
	echo "</div>";
	echo "</label>";
}

// QuestionTypeID 11
function edu_render_drop_list_question( $question ) {
	echo "<label>";
	echo "<div class=\"inputLabel noHide\">";
	echo $question->QuestionText;
	echo "</div>";
	echo "<div class=\"inputHolder\">";
	echo "<select class=\"questionDropdown\" onchange=\"eduBookingView.UpdatePrice();\"" . ( $question->Mandatory ? " required=\"required\"" : "" ) . " name=\"question_" . $question->QuestionID . "_dropdown\">";
	if ( is_array( $question->Answers->EventBookingAnswer ) ) {
		foreach ( $question->Answers->EventBookingAnswer as $q ) {
			echo "<option value=\"" . $q->AnswerID . "\"" . ( $q->DefaultAlternative == 1 ? " selected=\"selected\"" : "" ) . " data-type=\"dropdown\" data-price=\"" . $q->Price . "\">";
			echo $q->AnswerText;
			if ( $q->Price > 0 ) {
				echo " (" . convert_to_money( $q->Price ) . ")";
			}
			echo "</option>";
		}
	} else if ( is_object( $question->Answers->EventBookingAnswer ) ) {
		$q = $question->Answers->EventBookingAnswer;
		echo "<option value=\"" . $q->AnswerID . "\"" . ( $q->DefaultAlternative == 1 ? " selected=\"selected\"" : "" ) . " data-type=\"dropdown\" data-price=\"" . $q->Price . "\">";
		echo $q->AnswerText;
		if ( $q->Price > 0 ) {
			echo " (" . convert_to_money( $q->Price ) . ")";
		}
		echo "</option>";
	}
	echo "</select>";
	echo "</div>";
	echo "</label>";
}

function edu_render_number_question( $question ) {
	echo "<label>";
	echo "<div class=\"inputLabel noHide\">";
	echo $question->QuestionText;
	echo "</div>";
	echo "<div class=\"inputHolder\">";
	echo "<input type=\"number\" class=\"questionText\" onchange=\"eduBookingView.UpdatePrice();\"" . ( $question->Mandatory ? " required=\"required\"" : "" ) . " data-price=\"" . $question->Answers->EventBookingAnswer->Price . "\" min=\"0\" data-type=\"number\" name=\"question_" . $question->Answers->EventBookingAnswer->AnswerID . "_number\" placeholder=\"" . __( "Quantity", 'eduadmin-booking' ) . "\" />";
	if ( $question->Answers->EventBookingAnswer->Price > 0 ) {
		echo " <i class=\"priceLabel\">(" . sprintf( __( '%1$s / pcs', 'eduadmin-booking' ), convert_to_money( $question->Answers->EventBookingAnswer->Price ) ) . ")</i>";
	}
	echo "</div>";
	echo "</label>";
}

function edu_render_info_text( $question ) {
	if ( trim( $question->Answers->EventBookingAnswer->AnswerText ) != "" ) {
		echo "<h3 class=\"inputLabel questionInfoQuestion\">" . $question->QuestionText . ( $question->Answers->EventBookingAnswer->Price > 0 ? " <i class=\"priceLabel\">(" . convert_to_money( $question->Answers->EventBookingAnswer->Price ) . ")</i>" : "" ) . "</h3>";
		echo "<div class=\"questionInfoText\" data-type=\"infotext\" data-price=\"" . $question->Answers->EventBookingAnswer->Price . "\">";
		echo $question->Answers->EventBookingAnswer->AnswerText;
		echo "</div>";
	}
	// Hittade inget sätt att fylla i info-text-fält för ett tillfälle.
}

function edu_render_radio_question( $question, $display ) {
	echo "<h3 class=\"inputLabel radioQuestion\">" . $question->QuestionText . "</h3>";
	if ( $display == 'vertical' ) {
		if ( is_array( $question->Answers->EventBookingAnswer ) ) {
			foreach ( $question->Answers->EventBookingAnswer as $q ) {
				echo "<label class=\"questionRadioVertical\">";
				echo "<div class=\"inputHolder\">";
				echo "<input type=\"radio\" class=\"questionRadio\" data-type=\"radio\"" . ( $question->Mandatory ? " required=\"required\"" : "" ) . " data-price=\"" . $q->Price . "\" name=\"question_" . $question->QuestionID . "_radio\" value=\"" . $q->AnswerID . "\" /> ";
				echo $q->AnswerText;
				if ( $q->Price > 0 ) {
					echo " <i class=\"priceLabel\">(" . convert_to_money( $q->Price ) . ")</i>";
				}
				echo "</div>";
				echo "</label>";
			}
		} else if ( is_object( $question->Answers->EventBookingAnswer ) ) {
			$q = $question->Answers->EventBookingAnswer;
			echo "<label class=\"questionRadioVertical\">";
			echo "<div class=\"inputHolder\">";
			echo "<input type=\"radio\" class=\"questionRadio\" data-type=\"radio\"" . ( $question->Mandatory ? " required=\"required\"" : "" ) . " data-price=\"" . $q->Price . "\" name=\"question_" . $question->QuestionID . "_radio\" value=\"" . $q->AnswerID . "\" /> ";
			echo $q->AnswerText;
			if ( $q->Price > 0 ) {
				echo " <i class=\"priceLabel\">(" . convert_to_money( $q->Price ) . ")</i>";
			}
			echo "</div>";
			echo "</label>";
		}
	} else if ( $display == 'horizontal' ) {
		if ( is_array( $question->Answers->EventBookingAnswer ) ) {
			foreach ( $question->Answers->EventBookingAnswer as $q ) {
				echo "<label class=\"questionRadioHorizontal\">";
				echo "<div class=\"inputHolder\">";
				echo "<input type=\"radio\" class=\"questionRadio\" data-type=\"radio\"" . ( $question->Mandatory ? " required=\"required\"" : "" ) . " data-price=\"" . $q->Price . "\" name=\"question_" . $question->QuestionID . "_radio\" value=\"" . $q->AnswerID . "\" /> ";
				echo $q->AnswerText;
				if ( $q->Price > 0 ) {
					echo " <i class=\"priceLabel\">(" . convert_to_money( $q->Price ) . ")</i>";
				}
				echo "</div>";
				echo "</label>";
			}
		} else if ( is_object( $question->Answers->EventBookingAnswer ) ) {
			$q = $question->Answers->EventBookingAnswer;
			echo "<label class=\"questionRadioHorizontal\">";
			echo "<div class=\"inputHolder\">";
			echo "<input type=\"radio\" class=\"questionRadio\" data-type=\"radio\"" . ( $question->Mandatory ? " required=\"required\"" : "" ) . " data-price=\"" . $q->Price . "\" name=\"question_" . $question->QuestionID . "_radio\" value=\"" . $q->AnswerID . "\" /> ";
			echo $q->AnswerText;
			if ( $q->Price > 0 ) {
				echo " <i class=\"priceLabel\">(" . convert_to_money( $q->Price ) . ")</i>";
			}
			echo "</div>";
			echo "</label>";
		}
	}
}

// QuestionTypeID 1
function edu_render_text_question( $question ) {
	echo "<label>";
	echo "<div class=\"inputLabel noHide\">";
	echo $question->QuestionText . ( $question->Answers->EventBookingAnswer->Price > 0 ? " <i class=\"priceLabel\">(" . convert_to_money( $question->Answers->EventBookingAnswer->Price ) . ")</i>" : "" );
	echo "</div>";
	echo "<div class=\"inputHolder\">";
	echo "<input type=\"text\" data-price=\"" . $question->Answers->EventBookingAnswer->Price . "\"" . ( $question->Mandatory ? " required=\"required\"" : "" ) . " onchange=\"eduBookingView.UpdatePrice();\" data-type=\"text\" class=\"questionText\" name=\"question_" . $question->Answers->EventBookingAnswer->AnswerID . "_text\" />";
	echo "</div>";
	echo "</label>";
}
