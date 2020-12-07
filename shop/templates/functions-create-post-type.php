<?php
function shop_post_type_array( $postTypes ) {
	$postTypes = str_replace( "; ",";", $postTypes );
	$custom_post_types = explode(";", $postTypes);

	return $custom_post_types;
}

function create_shop_post_types_array ( $postTypes ) {
	$postTypes = shop_post_type_array( $postTypes );
	$postTypesArray = array();

	foreach( $postTypes as $type ) {
		$type = post_type_sanitize_title( $type );
		array_push($postTypesArray, $type);
	}

	return $postTypesArray;
}

function get_shop_goods() {
	$shopPostTypes = esc_attr( get_option( 'shop_post_types' ) );
	$postTypesArray = create_shop_post_types_array($shopPostTypes);

	return $postTypesArray;
}

function create_shop_slug( $type ) {
	$max_lengh = 20;

	if ( strlen( $type ) > $max_lengh ) {
		$text_cut = mb_substr($type, 0, $max_lengh, "UTF-8");
		$text_explode = explode(" ", $text_cut);

		unset($text_explode[count($text_explode) - 1]);

		$text_implode = implode(" ", $text_explode);

		$type =  $text_implode;
	}

	$type = post_type_sanitize_title( $type );
	return $type;
}

$shopPostTypes = esc_attr( get_option( 'shop_post_types' ) );
$shopSlugPostTypes = esc_attr( get_option( 'shop_slug_post_type' ) );
$shopPagesTypes = esc_attr( get_option( 'shop_pages_types' ) );

foreach ( shop_post_type_array( $shopPostTypes ) as $type) {
	$labels = array(
		'name'                  => __( $type, 'garage-shop' ),
		'singular_name'         => __( $type, 'garage-shop' ),
		'add_new'               => __( 'Добавить', 'garage-shop' ),
		'add_new_item'          => __( 'Добавление', 'garage-shop' ),
		'edit_item'             => __( 'Редактировать', 'garage-shop' ),
		'new_item'              => __( 'Новый', 'garage-shop' ),
		'view_item'             => __( 'Просмотр', 'garage-shop' ),
		'search_items'          => __( 'Поиск', 'garage-shop' ),
		'not_found'             => __( 'Не найдено', 'garage-shop' ),
		'not_found_in_trash'    => __( 'Не найдено в корзине', 'garage-shop' ),
	);

	$args = array(
		'labels' => $labels,
		'public'                => true,
		'hierarchical'          => false,
		'supports'              =>
			array(
				'title',
				'editor',
				'excerpt',
				'custom-fields',
				'thumbnail',
				'comments',
				'author'
			),
		'show_in_menu'          => 'shop'
	);

	register_post_type( create_shop_slug( $type ), $args );
}

/*
 *
 */
$shopPagesTypes = esc_attr( get_option( 'shop_pages_types' ) );

$getPageSlug = get_post( $shopPagesTypes ); // находим страницу по ID
$getPageSlug = $getPageSlug->post_name; // сохраняем slug страницы в переменную

$shopPagePostTypes = esc_attr( get_option( 'shop_page_post_types' ) );
foreach ( shop_post_type_array( $shopPagePostTypes ) as $type) {
	$labels = array(
		'name'                  => __( $type, 'garage-shop' ),
		'singular_name'         => __( $type, 'garage-shop' ),
		'add_new'               => __( 'Добавить', 'garage-shop' ),
		'add_new_item'          => __( 'Добавление', 'garage-shop' ),
		'edit_item'             => __( 'Редактировать', 'garage-shop' ),
		'new_item'              => __( 'Новый', 'garage-shop' ),
		'view_item'             => __( 'Просмотр', 'garage-shop' ),
		'search_items'          => __( 'Поиск', 'garage-shop' ),
		'not_found'             => __( 'Не найдено', 'garage-shop' ),
		'not_found_in_trash'    => __( 'Не найдено в корзине', 'garage-shop' ),
	);
	$args = array(
		'labels' => $labels,
		'public'                => true,
		'hierarchical'          => false,
		'supports'              =>
			array(
				'title',
				'editor',
				'excerpt',
				'custom-fields',
				'thumbnail',
				'comments',
				'author'
			),
		'show_in_menu'          => 'shop',
		'has_archive'           => false,
		'rewrite'               => array( 'slug' => $getPageSlug )
	);
	register_post_type( create_shop_slug( $type ), $args );
}

flush_rewrite_rules();