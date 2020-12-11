<?php

/*
===================================================================
          Register Scripts and Css
===================================================================
*/
function customize_form_scripts() {
    wp_enqueue_style( 'contact-form', get_template_directory_uri() . '/contact-form/css/contact-form.min.css' );

    wp_enqueue_script( 'contact-form', get_template_directory_uri() . '/contact-form/js/contact-form.min.js', false, null, true );
}
add_action('wp_enqueue_scripts', 'customize_form_scripts');


/*
===================================================================
          Add Customize Menu
===================================================================
*/
require_once( 'inc/admin-menu.php' );
require_once( 'inc/contact-form-post-type.php' );
require_once( 'inc/ajax.php' );
