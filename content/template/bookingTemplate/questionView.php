		<div class="questionPanel">
		<?php
		if(isset($_REQUEST['eid']))
		{
			$questions = $eduapi->GetEventBookingQuestion($edutoken, $_REQUEST['eid']);
			// VatPercent EventBookingAnswer
			$groupedQuestions = array();

			$qCategories = array();
			$qSortIndex = array();

			foreach($questions as $q => $row)
			{

				$qCategories[$q] = $row->CategoryID;
				$qSortIndex[$q] = $row->SortIndex;
			}

			array_multisort($qCategories, SORT_ASC, $qSortIndex, SORT_ASC, $questions);

			foreach($questions as $q)
			{
				if($q->ShowExternal) {
					$groupedQuestions[$q->QuestionID] = $q;
				}
			}

			if(!empty($groupedQuestions))
			{
				$lastQuestionId = -1;
				foreach($groupedQuestions as $question)
				{
					if($lastQuestionId != $question->QuestionID)
					{
						renderQuestion($question);
					}

					$lastQuestionId = $question->QuestionID;
				}
			}
		}
		?>
		</div>