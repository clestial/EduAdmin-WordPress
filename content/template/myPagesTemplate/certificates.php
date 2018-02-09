<?php
	$user         = EDU()->session['eduadmin-loginUser'];
	$contact      = $user->Contact;
	$customer     = $user->Customer;
	$certificates = EDUAPI()->OData->Persons->GetItem( $contact->PersonId,
	                                                   "PersonId",
	                                                   "Certificates"
	)["Certificates"];
?>
<div class="eduadmin">
	<?php
		$tab = "certificates";
		include_once( "login_tab_header.php" );
	?>
    <h2><?php _e( "Certificates", 'eduadmin-booking' ); ?></h2>
    <table class="myCertificationsTable">
        <tr>
            <th align="left"><?php _e( "Name", 'eduadmin-booking' ); ?></th>
            <th align="left"><?php _e( "Certified", 'eduadmin-booking' ); ?></th>
            <th align="left"><?php _e( "Valid", 'eduadmin-booking' ); ?></th>
        </tr>
		<?php
			if ( ! empty( $certificates ) ) {
				foreach ( $certificates as $certificate ) {
					?>
                    <tr>
                        <td align="left"><?php echo wp_strip_all_tags( $certificate["CertificateName"] ); ?></td>
                        <td align="left"><?php echo date( "Y-m-d", strtotime( $certificate["CertificateName"] ) ); ?></td>
                        <td align="left"><?php echo GetOldStartEndDisplayDate( $certificate["ValidFrom"], $certificate["ValidTo"] ); ?></td>
                    </tr>
					<?php
				}
			} else {
				?>
                <tr>
                <td colspan="3" align="center"><i><?php _e( 'You have no certificates.', 'eduadmin-booking' ); ?></i>
                </td></tr><?php
			}
		?></table>