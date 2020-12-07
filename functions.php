<?php

/*
===================================================================
          Require all functions from /inc folder
===================================================================
*/

/*
 * Meta fields                                        -   ON
*/
require_once ('users/profile-functions.php');

/*
 * Image multiupload from wp galery                  -   ON

require_once ('inc/functions-multiupload.php');
*/
/*
 * Walker Comments                                    -   ON
*/
require_once ('inc/functions-walker.php');

/*
 * Meta fields                                        -   ON
*/
require_once ('inc/functions-fields.php');

/*
 * Address-autocomplete-using-google-place-api          -   ON
*/
require_once ('google-maps-api/functions-gmapsapi.php');

/*
 * Add Category page to admin menu                      -   ON
 */
require_once ('shop/shop-pages.php');

/*
 * Login redirect if not administrator                  -   ON
 * Remove Sub-menu page                                 -   Off
 * Hide other users' posts in admin panel               -   ON
 * Limit/Restrict media library for users               -   ON
 * Password strength                                    -   ON
 * Delete original size of image                        -   ON
 * Delete image sizes                                   -   ON
 * Delete all image sizes from user profile page        -   ON
 * Delete all image sizes from: post, type_post, page   -   ON
 * Modify user table                                    -   ON
 * 
 **/
require_once ('inc/functions-limits.php');

/*
 * Remove Admin bar                                     -   Off
 * Remove WordPress Meta Generator                      -   ON
 * REMOVE WP EMOJI                                      -   ON
 * Removing WordPress Version from pages, 
   RSS, scripts and styles                              -   ON
 * Change logotype link to site (not to wordpress.org)  -   ON
 * Remove title in logotype "сайт работает на wordpress"-   ON
 * Custom WordPress Footer                              -   ON
 * Remove WordPress Version From The Admin Footer       -   ON
 * 
 * */
require_once('inc/functions-remove.php');

/*
 * Disable Updates
 *
 *
 *
 */
require_once('inc/functions-updates.php');

/*
 * Breadcrumbs                                          -   ON
 * Cyr to lat                                           -   ON
 */
require_once ('inc/functions-plugins.php');


/*
===================================================================
          Add favicon
===================================================================
*/

function my_favicon() {
	echo '<link rel="shortcut Icon" type="image/x-icon"
 href="' . get_template_directory_uri() . '/assets/images/favicon.ico" />';
}
add_action('wp_head', 'my_favicon');

/*
===================================================================
          Switch default core markup for search form, comment form,
          and comments to output valid HTML5.
===================================================================
*/

add_theme_support('html5', array(
    'search-form',
    'comment-form',
    'comment-list',
    'gallery',
    'caption',
));

/*
===================================================================
          Enable support for Post Formats.
===================================================================
*/

add_theme_support('post-formats', array(
    'aside',
    'image',
    'video',
    'quote',
    'link',
    'gallery',
    'status',
    'audio',
    'chat',
));

/*
===================================================================
          Register Scripts and Css
===================================================================
*/

function garage_scripts()
{
    // Styles
    wp_enqueue_style('style', get_template_directory_uri() . '/style.css');
	wp_enqueue_style('main', get_template_directory_uri() . '/assets/css/main.min.css');
	wp_enqueue_style('general', get_template_directory_uri() . '/assets/css/general.min.css');

    // Scripts
	wp_enqueue_script( 'jquery' );
    wp_enqueue_script('script', get_template_directory_uri() . '/assets/js/script.min.js', false, null, true);
}

add_action('wp_enqueue_scripts', 'garage_scripts');

/*
===================================================================
          Register Nav Menu
===================================================================
*/

register_nav_menus(array(
    'primary' => 'Primary Menu',
));


/*
===================================================================
          Register sidebar
===================================================================
*/

function garage_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'garage' ),
		'id' => 'sidebar',
		'description' => __( 'Widgets in this area will be shown on all posts and pages.', 'garage' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>'
	) );
}
add_action( 'widgets_init', 'garage_widgets_init' );