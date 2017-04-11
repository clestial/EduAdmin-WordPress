<?php
// Render ALL the types
function renderQuestion($question)
{
	switch ($question->QuestionTypeID)
	{
		case 1: // Text-fråga
			renderTextQuestion($question);
			break;
		case 2: // Checkbox-fråga
			renderCheckBoxQuestion($question);
			break;
		case 3: // Radio - Vertikal
			renderRadioQuestion($question, 'vertical');
			break;
		case 4: // Nummerfråga
			renderNumberQuestion($question);
			break;
		case 5: // Anteckningar
			renderNoteQuestion($question);
			break;
		case 6: // Infotext - hel rad
			renderInfoText($question);
			break;
		case 7: // Radbrytning
			break;
		case 8: // Datum-fråga
			renderDateQuestion($question);
			break;
		case 9: // Infotext - halv rad
			renderInfoText($question);
			break;
		case 10: // Radio - horisontell
			renderRadioQuestion($question, 'horizontal');
			break;
		case 11: // Droplist-fråga
			renderDropListQuestion($question);
			break;
		default:
			echo "<xmp>" . print_r($question, true) . "</xmp>";
		break;
	}
}

// QuestionTypeID 5
function renderNoteQuestion($question)
{
	echo "<label><h3 class=\"inputLabel noteQuestion\">" . $question->QuestionText . ($question->Answers->EventBookingAnswer->Price > 0 ? " <i class=\"priceLabel\">(" . convertToMoney($question->Answers->EventBookingAnswer->Price) . ")</i>" : "") . "</h3>";
	echo "<div class=\"inputHolder\">";
	echo "<textarea name=\"question_" . $question->Answers->EventBookingAnswer->AnswerID . "_note\" data-type=\"note\" onchange=\"eduBookingView.UpdatePrice();\" data-price=\"" . $question->Answers->EventBookingAnswer->Price . "\" resizable=\"resizable\" class=\"questionNoteField\" rows=\"3\">" . $question->Answers->EventBookingAnswer->DefaultAnswerText . "</textarea>";
	echo "</div></label>";
}

// QuestionTypeID 2
function renderCheckBoxQuestion($question)
{
	echo "<h3 class=\"inputLabel checkBoxQuestion\">" . $question->QuestionText . "</h3>";
	foreach ($question->Answers->EventBookingAnswer as $q)
	{
		echo "<label>";
		echo "<div class=\"inputHolder\">";
		echo "<input type=\"checkbox\" class=\"questionCheck\" data-type=\"check\" data-price=\"" . $q->Price . "\" onchange=\"eduBookingView.UpdatePrice();\" name=\"question_" . $question->QuestionID . "_check\"" . ($q->DefaultAlternative == 1 ? " checked=\"checked\"" : "") . " value=\"" . $q->AnswerID . "\" /> ";
		echo $q->AnswerText;
		if ($q->Price > 0) {
			echo " <i class=\"priceLabel\">(" . convertToMoney($q->Price) . ")</i>";
		}
		echo "</div>";
		echo "</label>";
	}
}

// QuestionTypeID 8
function renderDateQuestion($question)
{
	echo "<label>";
	echo "<div class=\"inputLabel noHide\">";
	echo $question->QuestionText . ($question->Answers->EventBookingAnswer->Price > 0 ? " <i class=\"priceLabel\">(" . convertToMoney($question->Answers->EventBookingAnswer->Price) . ")</i>" : "");
	echo "</div>";
	echo "<div class=\"inputHolder\">";
	echo "<input type=\"date\" class=\"questionDate\" data-type=\"date\" onchange=\"eduBookingView.UpdatePrice();\" data-price=\"" . $question->Answers->EventBookingAnswer->Price . "\" name=\"question_" . $question->Answers->EventBookingAnswer->AnswerID . "_date\" />";
	if ($question->Time == 1)
	{
		echo "<input type=\"time\" onchange=\"eduBookingView.UpdatePrice();\" class=\"questionTime\" name=\"question_" . $question->Answers->EventBookingAnswer->AnswerID . "_time\" />";
	}
	echo "</div>";
	echo "</label>";
}

// QuestionTypeID 11
function renderDropListQuestion($question)
{
	echo "<label>";
	echo "<div class=\"inputLabel noHide\">";
	echo $question->QuestionText;
	echo "</div>";
	echo "<div class=\"inputHolder\">";
	echo "<select class=\"questionDropdown\" onchange=\"eduBookingView.UpdatePrice();\" name=\"question_" . $question->QuestionID . "_dropdown\">";
	foreach ($question->Answers->EventBookingAnswer as $q)
	{
		echo "<option value=\"" . $q->AnswerID . "\"" . ($q->DefaultAlternative == 1 ? " selected=\"selected\"" : "") . " data-type=\"dropdown\" data-price=\"" . $q->Price . "\">";
		echo $q->AnswerText;
		if ($q->Price > 0) {
			echo " (" . convertToMoney($q->Price) . ")";
		}
		echo "</option>";
	}
	echo "</select>";
	echo "</div>";
	echo "</label>";
}

function renderNumberQuestion($question)
{
	echo "<label>";
	echo "<div class=\"inputLabel noHide\">";
	echo $question->QuestionText;
	echo "</div>";
	echo "<div class=\"inputHolder\">";
	echo "<input type=\"number\" class=\"questionText\" onchange=\"eduBookingView.UpdatePrice();\" data-price=\"" . $question->Answers->EventBookingAnswer->Price . "\" min=\"0\" data-type=\"number\" name=\"question_" . $question->Answers->EventBookingAnswer->AnswerID . "_number\" placeholder=\"" . edu__("Quantity") . "\" />";
	if ($question->Answers->EventBookingAnswer->Price > 0) {
		echo " <i class=\"priceLabel\">(" . sprintf(edu__('%1$s / pcs'), convertToMoney($question->Answers->EventBookingAnswer->Price)) . ")</i>";
	}
	echo "</div>";
	echo "</label>";
}

function renderInfoText($question)
{
	if (trim($question->Answers->EventBookingAnswer->AnswerText) != "")
	{
		echo "<h3 class=\"inputLabel questionInfoQuestion\">" . $question->QuestionText . ($question->Answers->EventBookingAnswer->Price > 0 ? " <i class=\"priceLabel\">(" . convertToMoney($question->Answers->EventBookingAnswer->Price) . ")</i>" : "") . "</h3>";
		echo "<div class=\"questionInfoText\" data-type=\"infotext\" data-price=\"" . $question->Answers->EventBookingAnswer->Price . "\">";
		echo $question->Answers->EventBookingAnswer->AnswerText;
		echo "</div>";
	}
	// Hittade inget sätt att fylla i info-text-fält för ett tillfälle.
}

function renderRadioQuestion($question, $display)
{
	echo "<h3 class=\"inputLabel radioQuestion\">" . $question->QuestionText . "</h3>";
	if ($display == 'vertical')
	{
		foreach ($question->Answers->EventBookingAnswer as $q)
		{
			echo "<label class=\"questionRadioVertical\">";
			echo "<div class=\"inputHolder\">";
			echo "<input type=\"radio\" class=\"questionRadio\" data-type=\"radio\" data-price=\"" . $q->Price . "\" name=\"question_" . $question->QuestionID . "_radio\" value=\"" . $q->AnswerID . "\" /> ";
			echo $q->AnswerText;
			if ($q->Price > 0) {
				echo " <i class=\"priceLabel\">(" . convertToMoney($q->Price) . ")</i>";
			}
			echo "</div>";
			echo "</label>";
		}
	}
	else if ($display == 'horizontal')
	{
		foreach ($question->Answers->EventBookingAnswer as $q)
		{
			echo "<label class=\"questionRadioHorizontal\">";
			echo "<div class=\"inputHolder\">";
			echo "<input type=\"radio\" class=\"questionRadio\" data-type=\"radio\" data-price=\"" . $q->Price . "\" name=\"question_" . $question->QuestionID . "_radio\" value=\"" . $q->AnswerID . "\" /> ";
			echo $q->AnswerText;
			if ($q->Price > 0) {
				echo " <i class=\"priceLabel\">(" . convertToMoney($q->Price) . ")</i>";
			}
			echo "</div>";
			echo "</label>";
		}
	}
	else
	{
		// Not supposed to happen.. But ok.
	}
}

// QuestionTypeID 1
function renderTextQuestion($question)
{
	echo "<label>";
	echo "<div class=\"inputLabel noHide\">";
	echo $question->QuestionText . ($question->Answers->EventBookingAnswer->Price > 0 ? " <i class=\"priceLabel\">(" . convertToMoney($question->Answers->EventBookingAnswer->Price) . ")</i>" : "");
	echo "</div>";
	echo "<div class=\"inputHolder\">";
	echo "<input type=\"text\" data-price=\"" . $question->Answers->EventBookingAnswer->Price . "\" onchange=\"eduBookingView.UpdatePrice();\" data-type=\"text\" class=\"questionText\" name=\"question_" . $question->Answers->EventBookingAnswer->AnswerID . "_text\" />";
	echo "</div>";
	echo "</label>";
}