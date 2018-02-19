<?php
$user         = EDU()->session['eduadmin-loginUser'];
$contact      = $user->Contact;
$customer     = $user->Customer;
$certificates = EDUAPI()->OData->Persons->GetItem( $contact->PersonId,
                                                   'PersonId',
                                                   'Certificates'
)['Certificates'];
?>
<div class="eduadmin">
	<?php
	$tab = 'certificates';
	include_once 'login_tab_header.php';
	?>
	<h2><?php esc_html_e( 'Certificates', 'eduadmin-booking' ); ?></h2>
	<table class="myCertificationsTable">
		<tr>
			<th align="left"><?php esc_html_e( 'Name', 'eduadmin-booking' ); ?></th>
			<th align="left"><?php esc_html_e( 'Certified', 'eduadmin-booking' ); ?></th>
			<th align="left"><?php esc_html_e( 'Valid', 'eduadmin-booking' ); ?></th>
		</tr>
		<?php
		if ( ! empty( $certificates ) ) {
			foreach ( $certificates as $certificate ) {
				?>
				<tr>
					<td align="left"><?php echo esc_html( $certificate['CertificateName'] ); ?></td>
					<td align="left"><?php echo date( 'Y-m-d', strtotime( $certificate['CertificateDate'] ) ); ?></td>
					<td align="left"><?php echo get_old_start_end_display_date( $certificate['ValidFrom'], $certificate['ValidTo'] ); ?></td>
				</tr>
				<?php
			}
		} else {
			?>
			<tr>
				<td colspan="3" align="center">
					<i><?php esc_html_e( 'You have no certificates.', 'eduadmin-booking' ); ?></i>
				</td>
			</tr>
			<?php
		}
		?>
	</table>
