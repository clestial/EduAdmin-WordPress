<h1><?php echo esc_html( $programme['ProgrammeName'] ); ?></h1>
<?php if ( ! empty( $programme['Description'] ) ) : ?>
	<h2><?php esc_html_e( 'Description', 'eduadmin-booking' ); ?></h2>
	<?php echo wp_kses_post( $programme['Description'] ); ?><?php endif; ?>
<?php if ( ! empty( $programme['Prerequisites'] ) ) : ?>
	<h2><?php esc_html_e( 'Prerequisites', 'eduadmin-booking' ); ?></h2>
	<?php echo wp_kses_post( $programme['Prerequisites'] ); ?><?php endif; ?>
<?php if ( ! empty( $programme['TargetGroup'] ) ) : ?>
	<h2><?php esc_html_e( 'Target Group', 'eduadmin-booking' ); ?></h2>
	<?php echo wp_kses_post( $programme['TargetGroup'] ); ?><?php endif; ?>
<?php if ( ! empty( $programme['Courses'] ) ) : ?>
	<h2><?php esc_html_e( 'Modules', 'eduadmin-booking' ); ?></h2>
	<ul>
		<?php
		foreach ( $programme['Courses'] as $module ) {
			// TODO: Add link to course template page
			echo '<li>' . esc_html( $module['CourseName'] ) . '</li>';
		}
		?>
	</ul>
<?php
endif;
?>
<h2><?php esc_html_e( 'Programme starts', 'eduadmin-booking' ); ?></h2>
<?php
if ( ! empty( $programme['ProgrammeStarts'] ) ) {
	include_once 'template/detail-list.php';
} else {
	echo '<i>' . esc_html__( 'No programme starts available', 'eduadmin-booking' ) . '</i>';
}
