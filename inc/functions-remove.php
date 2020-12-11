<?php

/*
===================================================================
          Remove Admin bar
===================================================================
*/

// add_filter('show_admin_bar', '__return_false');

/*
===================================================================
          Remove WordPress Meta Generator
===================================================================
*/

remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10);
remove_action('wp_head', 'parent_post_rel_link', 10);
remove_action('wp_head', 'wp_shortlink_wp_head', 10);
remove_action('wp_head', 'adjacent_posts_rel_link', 10);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);

/*
===================================================================
          REMOVE WP EMOJI
===================================================================
*/

remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');

/*
===================================================================
          Removing WordPress Version from pages, RSS, scripts and styles
===================================================================
*/
add_filter('the_generator', '__return_empty_string');
function rem_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'rem_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'rem_wp_ver_css_js', 9999 );

/*
===================================================================
           Custom WordPress Footer
===================================================================
*/

function remove_footer_admin () {
    echo '&copy; - Aleksandr Gryshko Theme';
}
add_filter('admin_footer_text', 'remove_footer_admin');

/*
===================================================================
           Remove WordPress Version From The Admin Footer
===================================================================
*/

function remove_wordpress_version() {
    remove_filter( 'update_footer', 'core_update_footer' );
}
add_action( 'admin_menu', 'remove_wordpress_version' );