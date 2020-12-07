<?php

/*
   ===================================================================
               Login redirect if not administrator
   ===================================================================
*/

function users_redirect(){
    if ( !(current_user_can('administrator')) && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
        wp_redirect(site_url() . '/404.php');
        die();
    }
}

add_action('admin_init','users_redirect');

/*
 ===================================================================
             Login redirect if not administrator
 ===================================================================
*/

add_filter("login_redirect", "sp_login_redirect", 10, 3);

function sp_login_redirect($redirect_to, $request, $user){
	if(is_array($user->roles))
		if(in_array('administrator', $user->roles) && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) )
			return home_url('/wp-admin/');
	return home_url();
}

/*
===================================================================
          Remove Admin bar
===================================================================
*/
function remove_admin_bar() {
	if (!current_user_can('administrator') && !is_admin()) {
		show_admin_bar(false);
	}
}

add_filter('after_setup_theme', 'remove_admin_bar');

/*
 ===================================================================
             Login redirect if not administrator
 ===================================================================
*/
//function custom_login(){
//	global $pagenow;
//	if( 'wp-login.php' == $pagenow
//	) {
//
//	}
//}
//add_action('init','custom_login');

/*
 ===================================================================
             Remove Sub-menu page
 ===================================================================


function remove_admin_submenu_items() {
    remove_submenu_page( 'index.php', 'update-core.php' );
}

add_action( 'admin_menu', 'remove_admin_submenu_items');
*/

/*
 ===================================================================
             Hide other users' posts in admin panel
 ===================================================================
*/

function posts_for_current_author( $query ) {
    global $pagenow;

    if( 'edit.php' != $pagenow || !$query->is_admin )
        return $query;

    if( !current_user_can( 'edit_others_posts' ) ) {
        global $user_ID;
        $query->set('author', $user_ID );
    }
    return $query;
}
add_filter('pre_get_posts', 'posts_for_current_author');

/*
   ===================================================================
               Limit/Restrict media library for users
   ===================================================================
*/

function ml_restrict_media_library( $wp_query_obj ) {
    global $current_user, $pagenow;
    if( !is_a( $current_user, 'WP_User') )
        return;
    if( 'admin-ajax.php' != $pagenow || $_REQUEST['action'] != 'query-attachments' )
        return;
    if( !current_user_can('manage_media_library') )
        $wp_query_obj->set('author', $current_user->ID );
    return;
}

add_action('pre_get_posts','ml_restrict_media_library');

/*
   ===================================================================
               Password strength
   ===================================================================
*/

function wc_ninja_remove_password_strength() {
    if ( wp_script_is( 'wc-password-strength-meter', 'enqueued' ) ) {
        wp_dequeue_script( 'wc-password-strength-meter' );
    }
}
add_action( 'wp_print_scripts', 'wc_ninja_remove_password_strength', 100 );

/*
   ===================================================================
               Delete image sizes
   ===================================================================
*/

function delete_intermediate_image_sizes( $sizes ){
    return array_diff( $sizes, array(
        'medium_large',
	    'large'             // 'thumbnail', 'medium', 'medium_large', 'large',
    ) );
}

add_filter( 'intermediate_image_sizes', 'delete_intermediate_image_sizes' );

/*
   ===================================================================
               Delete original size of image
   ===================================================================


function delete_fullsize_image( $metadata )
{
	$upload_dir = wp_upload_dir();
	$full_image_path = trailingslashit( $upload_dir['basedir'] ) . $metadata['file'];
	$deleted = unlink( $full_image_path );

	return $metadata;
}

add_filter( 'wp_generate_attachment_metadata', 'delete_fullsize_image' );
*/