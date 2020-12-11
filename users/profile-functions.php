<?php

/*
 * Messenger
 */
// require_once('messenger/messenger.php');

/*
===================================================================
          Подключение обработчиков
===================================================================
*/
require_once('handler/handler-login.php');
require_once('handler/handler-logout.php');
require_once('handler/handler-lost-password.php');
require_once('handler/handler-register.php');
require_once('handler/handler-reset-password.php');

/*
===================================================================
          Register Scripts and Css
===================================================================
*/

function garage_profile_scripts( $hook )
{
	// Scripts
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-form');

	wp_localize_script( 'jquery', 'ajax_var', // добавим объект с глобальными JS переменными
		array(
			'url' => admin_url('admin-ajax.php'), // и сунем в него путь до AJAX обработчика
		)
	);

	wp_enqueue_script('logout', get_template_directory_uri() . '/users/js/logout.min.js', array('jquery', 'jquery-form'), null, true);

	$profile = 'users/profile.php';
	$login = 'users/login.php';

	if ( 'profile.php' != $hook &&
	     'user-edit.php' != $hook &&
	     'users.php' != $hook &&
	     is_page_template() != $profile &&
	     is_page_template() != $login

	)
	{ return; }
	
	// Styles
	wp_enqueue_style('user-profile', get_template_directory_uri() . '/users/css/profile.min.css');

	// Scripts
	wp_enqueue_media();

	wp_enqueue_script('profile', get_template_directory_uri() . '/users/js/profile.min.js', array('jquery', 'jquery-form'), null, true);

	$templateUrlArray = array( 'templateUrl' => get_template_directory_uri() );
	wp_localize_script( 'profile', 'garage', $templateUrlArray );
	// Enqueued script with localized data.
	wp_enqueue_script( 'profile' );
}
add_action('admin_enqueue_scripts', 'garage_profile_scripts');
add_action('wp_enqueue_scripts', 'garage_profile_scripts');

/*
===================================================================
          Profile fields
===================================================================
*/

function show_profile_fields( $user ) {
	require_once( 'templates/admin/profile-fields.php' );
}
add_action( 'show_user_profile', 'show_profile_fields' );
add_action( 'edit_user_profile', 'show_profile_fields' );

// Функция сохранения произвольных полей
function save_profile_fields( $user_id ) {
	$fields = array( 'gender', 'organization', 'avatar', 'phone1', 'phone2', 'phone3', 'city_search', 'admin_area', 'post_code', 'city_route', 'street_num', 'local', 'country_name'  );

	foreach( $fields as $field ) {
		update_user_meta( $user_id, $field, $_POST[$field] );
	}
}

add_action( 'personal_options_update', 'save_profile_fields' );
add_action( 'edit_user_profile_update', 'save_profile_fields' );

/*
===================================================================
          Change mime type
===================================================================
*/

function get_icon_by_file_extension() {
	$mimeTypes = array(
		'jpg|jpeg|jpe' => 'image/jpeg',
		'png' => 'image/png',
	);

	// Get a list of allowed mime types.
	$mimes = get_allowed_mime_types();

	// Loop through and find the file extension icon.
	foreach ( $mimes as $type => $mime ) {
		if ( false !== strpos( $type, $mimeTypes ) ) {
			return wp_mime_type_icon( $mime );
		}
	}
}

/*
===================================================================
          Modify user table
===================================================================
*/

function modify_user_table( $column ) {
	$column['avatar'] = __( 'Аватар', 'user-profile' );
	//$column['xyz'] = 'XYZ';
	return $column;
}
add_filter( 'manage_users_columns', 'modify_user_table' );

function modify_user_table_row( $val, $column_name, $user_id ) {
	switch ($column_name) {
		case 'avatar' :
			return '
			<div class="profile-picture" style="background-image: url(' . changeGenderImage( $user_id ) . ')"><img id="profile-picture-preview" src="' . esc_attr(get_the_author_meta('avatar', $user_id)) . '" alt=""></div>';
			break;
		//case 'xyz' :  //соответствует значению $column
			//return '';
			//break;
		default:
	}
	return $val;
}
add_filter( 'manage_users_custom_column', 'modify_user_table_row', 10, 3 );

/*
===================================================================
          Custom settings via gender role
===================================================================
*/

function changeGenderImage ( $user_id ) {
	$profileImageLink = get_template_directory_uri() . '/users/images/';
	$genderImage = '';
	$genderRole = get_the_author_meta( 'gender', $user_id );

	switch ( $genderRole ) {
		case 'female':
			$genderImage = 'female.svg';
			break;
		case 'company':
			$genderImage = 'company.svg';
			break;
		default: $genderImage = 'male.svg';
	}

	return $profileImageLink .= $genderImage;
}

/*
===================================================================
          Ajax load profile page
===================================================================
*/

add_action( 'wp_ajax_nopriv_profile_info_callback', 'profile_info_callback' );
add_action( 'wp_ajax_profile_info_callback', 'profile_info_callback' );

function profile_info_callback(){
	$pages = $_POST["page"];

	switch( $pages ) {
		case 1:
			get_template_part( 'users/templates/profile', 'page' );
			break;
		case 2:
			get_template_part( 'users/templates/profile', 'password' );
			break;
		case 3:
			get_template_part( 'users/templates/profile', 'messenger' );
			break;
	}


	wp_die();
}

add_action( 'wp_ajax_nopriv_account_info_callback', 'account_info_callback' );
add_action( 'wp_ajax_account_info_callback', 'account_info_callback' );

function account_info_callback(){
	$pages = $_POST["page"];

	switch( $pages ) {
		case 'login':
			get_template_part( 'users/templates/profile', 'login' );
			break;
		case 'register':
			get_template_part( 'users/templates/profile', 'register' );
			break;
	}


	wp_die();
}

/*
===================================================================
          Redirect to login or user profile page
===================================================================
*/

function garage_template_redirect () {
	if ( is_page( 'login' ) && is_user_logged_in() ) {
		wp_redirect( home_url( '/profile/' ) );
		exit();
	}

	if ( is_page( 'profile' ) && !is_user_logged_in() ) {
		wp_redirect( home_url( '/login/' ) );
		exit();
	}
}
add_action( 'template_redirect', 'garage_template_redirect' );

function custom_login(){
	global $pagenow;
	if( 'wp-login.php' == $pagenow &&
	    !is_user_logged_in()
	) {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		get_template_part( 404 ); exit();
		// wp_redirect( home_url( '/404.php' ) ); exit();
	}
}
add_action('init','custom_login');

function custom_logout(){
	global $pagenow;
	if( 'wp-login.php' == $pagenow &&
	    $_GET['action']!="logout"
	) {
		wp_redirect( home_url() ); exit();
	}
}
add_action('init','custom_logout');

function message_visibility(){
	$post_type = get_post_type();
	$current_user = wp_get_current_user(); // get current logged in user
	$current_user_id = $current_user->ID;  // get current logged in user id
	$current_recipient_id = get_post_meta( get_the_ID(), 'recipient', true );

	$post_tmp = get_post(get_the_ID());
	$current_author_id = $post_tmp->post_author;

	if ( 'messenger' == $post_type ) {
		if ( is_user_logged_in() &&
		     $current_user_id == $current_author_id ||
		     is_user_logged_in() &&
		     $current_user_id == $current_recipient_id
		) {
			return;
		} else {
			global $wp_query;
			$wp_query->set_404();
			status_header( 404 );
			get_template_part( 404 );
			exit();
		}
	}
}
add_action('loop_start','message_visibility');

add_action("template_redirect", 'user_profile_redirect');
function user_profile_redirect() {
	global $wp;

	//A Specific Custom Post Type
	if ($wp->query_vars["post_type"] == 'messenger') {
		$templatefilename = 'single-messenger.php';
		if (file_exists(get_template_directory() . '/' . $templatefilename)) {
			$return_template = get_template_directory() . '/' . $templatefilename;
		} else {
			$return_template = get_template_directory() . '/users/messenger/view/' . $templatefilename;
		}
		do_theme_redirect($return_template);
	}
}

function do_theme_redirect($url) {
	global $post, $wp_query;
	if (have_posts()) {
		include($url);
		die();
	} else {
		$wp_query->is_404 = true;
	}
}