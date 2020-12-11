<?php

$contact = get_option ( 'activate_contact' );

if ( @$contact == 1 ) {

    add_action( 'init', 'customize_contact_post_type' ); // создаем свой тип записи и описываем все в функции customize_contact_post_type

    add_filter( 'manage_'.'contact_form_type'.'_posts_columns', 'set_contact_form_columns', 10, 1 ); // меняем колонки в таблице и добавляем нехватающие
    add_action( 'manage_'.'contact_form_type'.'_posts_custom_column', 'contact_custom_column', 10, 2 ); // выводим содержимое колонок

    add_action( 'add_meta_boxes', 'contact_add_meta_box' ); //добавляем мета бокс
    add_action( 'save_post', 'save_contact_email_data' ); // сохраняем мета бокс в наших записях
}

/* Contact CPT */
function customize_contact_post_type() {
    $labels = array(
        'name'                     => __('Сообщения', 'theme_language'),
        'singular_name'         => __('Сообщение', 'theme_language'),
        'add_new'                => __( 'Добавить', 'theme_language' ),
        'menu_name'                => __('Сообщения', 'theme_language'),
        'name_admin_bar'         => __('Сообщение', 'theme_language'),

    );

    $args = array(
        'labels'                 => $labels,
        'show_ui'                 => true,
        'show_in_menu'             => true,
        'capability_type'         => 'post',
        'hierarchical'             => false,
        'menu_position'         => 26,
        'menu_icon'             => 'dashicons-email-alt',
        'supports'                 => array( 'title', 'editor', 'author' )
    );

    register_post_type( 'contact_form_type', $args );

}

function set_contact_form_columns( $columns ) {
    $newColumns = array();

    $newColumns['cb']        = '<input type="checkbox" />';
    $newColumns['title']     = __('Имя', 'theme_language');
    $newColumns['message']   = __('Сообщение', 'theme_language');
    $newColumns['email']     = __('Email', 'theme_language');
    $newColumns['date']      = __('Дата', 'theme_language');

    return $newColumns;
}

function contact_custom_column( $column, $post_id ) {
    switch( $column ) {
        case 'message' :
            echo get_the_excerpt( $post_id );
            break;
        case 'email' :
            $email = get_post_meta( $post_id, '_contact_email_value_key', true );
            echo '<a href="mailto:'. $email .'">'. $email .'</a>';
            break;
    }
}

/* Contact Meta Boxes */
function contact_add_meta_box() {
    add_meta_box( 'contact_email', 'Email', 'contact_email_field_callback', 'contact_form_type', 'side' );
}

function contact_email_field_callback( $post ) { // информация из contact_form_type передается $post
    wp_nonce_field( 'save_contact_email_data', 'contact_email_meta_box_nonce' ); // создаем одноразовое поле с ключем для безопасного сохранения/удаления

    $value = get_post_meta( $post->ID, '_contact_email_value_key', true );

    echo '<label for="contact_email_field">' . __('Email пользователя ', 'theme_language');
    echo '<input type="email" id="contact_email_field" name="contact_email_field" value="'. esc_attr( $value ) .'" size="25" />';
}

function save_contact_email_data( $post_id ) {
    if( ! isset( $_POST['contact_email_meta_box_nonce'] ) ) {
        return;
    }

    if( ! wp_verify_nonce( $_POST['contact_email_meta_box_nonce'], 'save_contact_email_data' ) ) {
        return;
    }

    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if( ! isset( $_POST['contact_email_field'] ) ) {
        return;
    }

    $my_data = sanitize_text_field( $_POST['contact_email_field'] );

    update_post_meta( $post_id, '_contact_email_value_key', $my_data );
}
