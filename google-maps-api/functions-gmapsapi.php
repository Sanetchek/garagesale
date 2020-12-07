<?php
/*
===================================================================
          Google Maps APIs
===================================================================
*/

// Enqueue scripts
function garage_google_api_scripts( $hook )
{
	// Styles
	if ( 'google_mapsapi' != $hook ){
		wp_enqueue_style( 'gmaps-style', get_template_directory_uri() . '/google-maps-api/css/gmaps.min.css');
	}

	// Scripts
	wp_enqueue_script('gmapsapi', get_template_directory_uri() . '/google-maps-api/js/gmapsapi.min.js', false, null, true);

	$apiKey = esc_attr( get_option( 'google_api_key' ) );
	wp_enqueue_script('googel-autocomplete', 'https://maps.googleapis.com/maps/api/js?key='  . $apiKey.'&libraries=places&language=ru', false, null, true);

}
add_action('admin_enqueue_scripts', 'garage_google_api_scripts');
add_action('wp_enqueue_scripts', 'garage_google_api_scripts');


// Add submenu and settings
function garage_google_autocomplete_tab() {
	//Add submenu
	add_submenu_page('options-general.php', 'Google Maps API','Google Maps API', 'manage_options','google_mapsapi','garage_google_autocomplete_settings_tab'	);

	//Activate custom settings
    add_action( 'admin_init', 'google_api_custom_settings' );
}
add_action( 'admin_menu', 'garage_google_autocomplete_tab' );

function google_api_custom_settings() {
    register_setting( 'google-api-settings-group', 'google_api_key' );
	add_settings_section( 'google-api-sidebar-options', 'Options', 'profile_sidebar_options', 'google_mapsapi' );
	add_settings_field( 'api-key', 'Google API key', 'option_api_key', 'google_mapsapi', 'google-api-sidebar-options' );
}

function profile_sidebar_options() {
	echo __( 'Чтобы установки заработали, нужно добавить id="autocomplete" к полю input' );
}

function option_api_key() {
	$apiKey = esc_attr( get_option( 'google_api_key' ) );
	echo '<input type="text" name="google_api_key" class="google-api-key" value="' . $apiKey . '" placeholder="Google Maps JavaScript API" />';
	echo '<div><b>Note: </b><a href="https://developers.google.com/places/web-service/get-api-key?hl=ru" target="_blank">get key</a></div>';
}

function garage_google_autocomplete_settings_tab(){
	require_once( get_template_directory() . '/google-maps-api/templates/admin-settings.php' );
}