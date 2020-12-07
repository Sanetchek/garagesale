<?php

/*
 * Create Post Type                                        -   ON
 */
require_once ( get_template_directory() . '/shop/templates/functions-create-post-type.php' );
/*
===================================================================
          Register Scripts and Css
===================================================================
*/

function garage_shop_scripts( $hook )
{
	if ( $hook != 'toplevel_page_shop' ) { return; }

	// Scripts
	wp_enqueue_script('jquery');
	wp_enqueue_script('shop-scripts', get_template_directory_uri() . '/shop/js/shop-scripts.min.js', 'jquery', null, true);

	// Styles
	wp_enqueue_style('shop-styles', get_template_directory_uri() . '/shop/css/shop-styles.min.css');
}
add_action('admin_enqueue_scripts', 'garage_shop_scripts');
add_action('wp_enqueue_scripts', 'garage_shop_scripts');

/*
   ===================================================================
               Add Category page to admin menu
   ===================================================================
*/

add_action('admin_menu', function(){
     //Generate Shop Admin Page
	add_menu_page( 'Shop', 'Shop', 'manage_options', 'shop', '', 'dashicons-products', 9 );

	add_submenu_page( 'shop', 'Settings', 'Settings', 'manage_options', 'shop', 'settings_shop_callback' );

	//Activate custom settings
	add_action( 'admin_init', 'shop_api_custom_settings' );
} );

function shop_api_custom_settings() {
	register_setting( 'shop-settings-group', 'shop_post_types' );
	register_setting( 'shop-settings-group', 'shop_slug_post_type' );
	register_setting( 'shop-settings-group', 'shop_hierarchy_post_types' );

	add_settings_section( 'shop-page-post-types-options', 'Post type', 'shop_sidebar_options', 'shop' );

	add_settings_field( 'shop-page-post-types', '', '', 'shop', 'shop-page-post-types-options' );
}

function shop_sidebar_options() {
	$shopPostTypes = esc_attr( get_option( 'shop_post_types' ) );
	$shopSlugPostTypes = esc_attr( get_option( 'shop_slug_post_type' ) );
	$shopPagesTypes = esc_attr( get_option( 'shop_pages_types' ) );

	$item = 0;

	echo count( $shopPostTypes ) . ' ';
	var_dump($shopPostTypes);

	foreach ($shopPostTypes as $shopPost) {
		echo $shopPost;
	}

	echo '<div class="add-post-type">';
	echo '<div>' . _e( 'Post type', 'garage-shop') . '</div>';
	echo '<input name="shop_post_types" >';
	echo '<div>' . _e( 'Slug', 'garage-shop') . '</div>';
	echo '<input name="shop_slug_post_type" value="' . $shopSlugPostTypes . '" >';

	$args = array(
		'depth'            => 0,
		'child_of'         => 0,
		'selected'         => $shopPagesTypes,
		'echo'             => 1,
		'name'             => 'shop_pages_types',
		'show_option_none' => __( 'No', 'garage-shop' ),
		'exclude'          => '',
		'exclude_tree'     => '',
		'value_field'      => 'ID', // поле для значения value тега option
	);

	echo '<div>' . _e( 'Page hierarchy', 'garage-shop') . '</div>';
	wp_dropdown_pages( $args );

	echo '</div>';
	

	echo '<div class="loop-post-type">';
	
	echo '</div>';
}

function settings_shop_callback() {
	require_once( get_template_directory() . '/shop/templates/admin-settings.php' );
}

function post_type_sanitize_title($title) {
	$iso9_table = array(
		'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Ѓ' => 'G',
		'Ґ' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Є' => 'YE',
		'Ж' => 'ZH', 'З' => 'Z', 'Ѕ' => 'Z', 'И' => 'I', 'Й' => 'J',
		'Ј' => 'J', 'І' => 'I', 'Ї' => 'YI', 'К' => 'K', 'Ќ' => 'K',
		'Л' => 'L', 'Љ' => 'L', 'М' => 'M', 'Н' => 'N', 'Њ' => 'N',
		'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
		'У' => 'U', 'Ў' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'TS',
		'Ч' => 'CH', 'Џ' => 'DH', 'Ш' => 'SH', 'Щ' => 'SHH', 'Ъ' => '',
		'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA',
		'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'ѓ' => 'g',
		'ґ' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'є' => 'ye',
		'ж' => 'zh', 'з' => 'z', 'ѕ' => 'z', 'и' => 'i', 'й' => 'j',
		'ј' => 'j', 'і' => 'i', 'ї' => 'yi', 'к' => 'k', 'ќ' => 'k',
		'л' => 'l', 'љ' => 'l', 'м' => 'm', 'н' => 'n', 'њ' => 'n',
		'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
		'у' => 'u', 'ў' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts',
		'ч' => 'ch', 'џ' => 'dh', 'ш' => 'sh', 'щ' => 'shh', 'ъ' => '',
		'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya'
	);
	$geo2lat = array(
		'ა' => 'a', 'ბ' => 'b', 'გ' => 'g', 'დ' => 'd', 'ე' => 'e', 'ვ' => 'v',
		'ზ' => 'z', 'თ' => 'th', 'ი' => 'i', 'კ' => 'k', 'ლ' => 'l', 'მ' => 'm',
		'ნ' => 'n', 'ო' => 'o', 'პ' => 'p','ჟ' => 'zh','რ' => 'r','ს' => 's',
		'ტ' => 't','უ' => 'u','ფ' => 'ph','ქ' => 'q','ღ' => 'gh','ყ' => 'qh',
		'შ' => 'sh','ჩ' => 'ch','ც' => 'ts','ძ' => 'dz','წ' => 'ts','ჭ' => 'tch',
		'ხ' => 'kh','ჯ' => 'j','ჰ' => 'h'
	);
	$iso9_table = array_merge($iso9_table, $geo2lat);

	$locale = get_locale();
	switch ( $locale ) {
		case 'bg_BG':
			$iso9_table['Щ'] = 'SHT';
			$iso9_table['щ'] = 'sht';
			$iso9_table['Ъ'] = 'A';
			$iso9_table['ъ'] = 'a';
			break;
		case 'uk':
		case 'uk_ua':
		case 'uk_UA':
			$iso9_table['И'] = 'Y';
			$iso9_table['и'] = 'y';
			break;
	}

	$is_term = false;
	$backtrace = debug_backtrace();
	foreach ( $backtrace as $backtrace_entry ) {
		if ( $backtrace_entry['function'] == 'wp_insert_term' ) {
			$is_term = true;
			break;
		}
	}

	if ( empty($term) ) {
		$title = strtr($title, apply_filters('ctl_table', $iso9_table));
		if (function_exists('iconv')){
			$title = iconv('UTF-8', 'UTF-8//TRANSLIT//IGNORE', $title);
		}
		$title = preg_replace("/[^A-Za-z0-9'_\-\.]/", '-', $title);
		$title = preg_replace('/\-+/', '-', $title);
		$title = preg_replace('/^-+/', '', $title);
		$title = preg_replace('/-+$/', '', $title);
	}

	$title = mb_strtolower( $title );
	return $title;
}