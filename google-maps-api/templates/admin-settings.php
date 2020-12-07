<h1>Google Maps APIs</h1>
<form method="post" action="options.php">
    <?php settings_fields( 'google-api-settings-group' ); ?>
	<?php do_settings_sections( 'google_mapsapi' ); ?>
	<?php submit_button(); ?>
</form>