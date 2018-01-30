<?php
	ob_start();
	include( "list-events.php" );
?>
    <div class="eventListTable"
         data-eduwidget="listview-eventlist"
         data-template="A"
         data-subject="<?php echo @esc_attr( $attributes['subject'] ); ?>"
         data-subjectid="<?php echo @esc_attr( $attributes['subjectid'] ); ?>"
         data-category="<?php echo @esc_attr( $attributes['category'] ); ?>"
         data-courselevel="<?php echo @esc_attr( $attributes['courselevel'] ); ?>"
         data-city="<?php echo @esc_attr( $attributes['city'] ); ?>"
         data-search="<?php echo @esc_attr( sanitize_text_field( $_REQUEST['searchCourses'] ) ); ?>"
         data-numberofevents="<?php echo @esc_attr( $attributes['numberofevents'] ); ?>"
         data-orderby="<?php echo @esc_attr( $attributes['orderby'] ); ?>"
         data-order="<?php echo @esc_attr( $attributes['order'] ); ?>"
         data-showmore="<?php echo @esc_attr( $attributes['showmore'] ); ?>"
         data-showcity="<?php echo @esc_attr( $attributes['showcity'] ); ?>"
         data-showbookbtn="<?php echo @esc_attr( $attributes['showbookbtn'] ); ?>"
         data-showreadmorebtn="<?php echo @esc_attr( $attributes['showreadmorebtn'] ); ?>"
    >
		<?php

			$numberOfEvents = $attributes['numberofevents'];
			$currentEvents  = 0;

			foreach ( $ede as $object ) {
				if ( $numberOfEvents != null && $numberOfEvents > 0 && $currentEvents >= $numberOfEvents ) {
					break;
				}
				$name      = ( ! empty( $object->PublicName ) ? $object->PublicName : $object->ObjectName );
				$spotsLeft = ( $object->MaxParticipantNr - $object->TotalParticipantNr );
				include( 'blocks/event_blockA.php' );
				$currentEvents++;
			}
		?>
    </div><!-- /eventlist -->
<?php
	$out = ob_get_clean();
	return $out;