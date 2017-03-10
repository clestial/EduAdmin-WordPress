<?php
$user = $_SESSION['eduadmin-loginUser'];
$contact = $user->Contact;
$customer = $user->Customer;

global $eduapi;
global $edutoken;
?>
<div class="eduadmin">
<?php
$tab = "bookings";
include_once("login_tab_header.php");
?>
	<h2><?php edu_e("Reservations"); ?></h2>
	<?php
	$filtering = new XFiltering();
	$f = new XFilter('CustomerID', '=', $customer->CustomerID);
	$filtering->AddItem($f);
	$f = new XFilter('ParticipantNr', '>', 0);
	$filtering->AddItem($f);

	$sorting = new XSorting();
	$s = new XSort('Created', 'DESC');
	$sorting->AddItem($s);
	$bookings = $eduapi->GetEventBooking($edutoken, $sorting->ToString(), $filtering->ToString());

	$eclIds = array();
	foreach($bookings as $book)
	{
		$eclIds[] = $book->EventCustomerLnkID;
	}

	$filtering = new XFiltering();
	$f = new XFilter('EventCustomerLnkID', 'IN', join(',', $eclIds));
	$filtering->AddItem($f);

	$f = new XFilter('Canceled', '=', 'false');
	$filtering->AddItem($f);

	$participants = $eduapi->GetEventParticipantV2($edutoken, $sorting->ToString(), $filtering->ToString());

	$partPerEvent = array();
	foreach($participants as $p)
	{
		$partPerEvent[$p->EventCustomerLnkID][] = $p;
	}

	$currency = get_option('eduadmin-currency', 'SEK');
	?>
	<table class="myReservationsTable">
		<tr>
			<th align="left"><?php edu_e("Booked"); ?></th>
			<th align="left"><?php edu_e("Course"); ?></th>
			<th align="left"><?php edu_e("Dates"); ?></th>
			<th align="right"><?php edu_e("Participants"); ?></th>
			<th align="right"><?php edu_e("Price"); ?></th>
		</tr>
		<?php
		if(empty($bookings)) {
		?>
		<tr><td colspan="5" align="center"><i><?php edu_e("No courses booked"); ?></i></td></tr>
		<?php
		} else {
			foreach($bookings as $book) {
				if(array_key_exists($book->EventCustomerLnkID, $partPerEvent))
				{
					$book->Participants = $partPerEvent[$book->EventCustomerLnkID];
				}
				else
				{
					$book->Participants = array();
				}
		?>
		<tr>
			<td><?php echo getDisplayDate($book->Created, true); ?></td>
			<td><?php echo $book->EventDescription; ?></td>
			<td><?php echo GetOldStartEndDisplayDate($book->PeriodStart, $book->PeriodEnd, true); ?></td>
			<td align="right"><?php echo $book->ParticipantNr; ?></td>
			<td align="right"><?php echo convertToMoney($book->TotalPrice, $currency); ?></td>
		</tr>
		<?php
		if(count($book->Participants) > 0) {
		?>
		<tr class="edu-participants-row">
			<td colspan="5">
				<table class="edu-event-participantList">
					<tr>
						<th align="left" class="edu-participantList-name"><?php edu_e("Participant name"); ?></th>
						<th align="center" class="edu-participantList-arrived"><?php edu_e("Arrived"); ?></th>
						<th align="right" class="edu-participantList-grade"><?php edu_e("Grade"); ?></th>
					</tr>
					<?php
					foreach($book->Participants as $participant)
					{
						?>
					<tr>
						<td align="left"><?php echo $participant->PersonName; ?></td>
						<td align="center"><?php echo $participant->Arrived == "1" ? "&#9745;" : "&#9744;"; ?></td>
						<td align="right"><?php echo (!empty($participant->GradeName) ? $participant->GradeName : '<i>' . edu__('Not graded') . '</i>'); ?></td>
					</tr>
						<?php
					}
					?>
				</table>
			</td>
		</tr>
		<?php } ?>
		<?php }
		} ?>
	</table>
<?php include_once("login_tab_footer.php"); ?>
</div>