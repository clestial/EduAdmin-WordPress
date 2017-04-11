<?php
if (isset($customer->CustomerID) && isset($contact->CustomerContactID)) {
	$f = new XFiltering();
	$ft = new XFilter('CustomerID', '=', $customer->CustomerID);
	$f->AddItem($ft);

	$cards = $eduapi->GetLimitedDiscount($edutoken, '', $f->ToString());

	$cCards = array();
	$cCardIds = array();
	foreach ($cards as $card) {
		$addCard = false;
		if (empty($card->CustomerContactID) || empty($card->CategoryID)) {
			$addCard = true;
		}

		if ($card->CustomerContactID == $contact->CustomerContactID) {
			$addCard = true;
		}
		if ($card->CategoryID == $selectedCourse->CategoryID) {
			$addCard = true;
		}

		if (!empty($card->CategoryID) && $card->CategoryID != $selectedCourse->CategoryID) {
			$addCard = false;
		}
		if (!empty($card->CustomerContactID) && $card->CustomerContactID != $contact->CustomerContactID) {
			$addCard = false;
		}

		if ($addCard) { $cCards[] = $card; $cCardIds[] = $card->LimitedDiscountID; }
	}

	$f = new XFiltering();
	$ft = new XFilter('LimitedDiscountID', 'in', join(',', $cCardIds));
	$f->AddItem($ft);

	$objectCards = $eduapi->GetLimitedDiscountObjectStatus($edutoken, '', $f->ToString());
	$cCardIds = array();
	foreach ($objectCards as $oCard) {
		$addCard = false;
		if ($oCard->ObjectID == $selectedCourse->ObjectID) {
			$addCard = true;
		}

		if ($addCard && !in_array($oCard->LimitedDiscountID, $cCardIds)) {
			$cCardIds[] = $oCard->LimitedDiscountID;
		}
	}

	if (count($objectCards) > 0 && count($cCardIds) == 0) {
		$cCards = array();
	}

	array_filter($cCards, function($card) use (&$cCardIds) {
		$valid = false;
		foreach ($cCardIds as $cid) {
			if ($cid == $card->LimitedDiscountID) {
				$valid = true;
			}
		}
		return $valid;
	});
?>
<div class="discountCardView">

</div>
<?php
}