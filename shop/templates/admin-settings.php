<h1>Shop Settings</h1>
<form method="post" action="options.php">
    <?php settings_fields( 'shop-settings-group' ); ?>
	<?php do_settings_sections( 'shop' ); ?>
	<?php submit_button(); ?>
</form>