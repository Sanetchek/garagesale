<?php
/*
===================================================================
          Add Customize Menu
===================================================================
*/
function customize_add_admin_page () {
    $siteName = strval( get_bloginfo( 'name' ) );
    // Создаем меню в админке
    add_submenu_page(
        'options-general.php',
        __('Форма Контактов', 'theme_language'),
        __('Форма Контактов', 'theme_language'),
        'edit_pages',
        'contact_form_page',
        'contact_form_create_page'
    );

    // Включить пользовательские настройки
    add_action( 'admin_init', 'customize_contact_form_settings' );
}
add_action( 'admin_menu', 'customize_add_admin_page' );


/* Admin Banner settings and custom fields */
function customize_contact_form_settings() {
    // Contact form Option
    register_setting( 'customize-contact-group', 'activate_contact' );
    register_setting( 'customize-contact-group', 'contact_email_to' );

    add_settings_section( 'customize-contact-section', '', 'customize_contact_form_section', 'customize_contact_form_page' );

    add_settings_field( 'activate-form', __( 'Включить форму', 'theme_language'), 'customize_contact_form_activete', 'customize_contact_form_page', 'customize-contact-section' );

    $options = get_option( 'activate_contact' );
    if( $options == 1 ) {
        add_settings_field( 'contact-email-to', __( 'Email', 'theme_language'), 'customize_contact_form_email_to', 'customize_contact_form_page', 'customize-contact-section' );
    }
}

function customize_contact_form_email_to() {
    $contactEmailTo = get_option( 'contact_email_to' );
    echo '<input type="text" name="contact_email_to" placeholder="'. __( 'email', 'theme_language') .'" value="'. $contactEmailTo .'" >
    <p>'. __( 'Введите email на который должы приходить сообщения', 'theme_language') .'</p>
    ';
}

function customize_contact_form_activete() {
    $options = get_option( 'activate_contact' );
    $checked = ( @$options == 1 ? 'checked' : '' );
    echo '<label><input type="checkbox" id="activate_contact" name="activate_contact" value="1" '. $checked .' /></label>';
}

function customize_contact_form_section() {
    echo __( 'Включите или выключите настройки Формы Контактов', 'theme_language');
}


function contact_form_create_page () {
    // Генерация Админ Страницы
    require_once('admin/admin-settings.php');
}

/*
===================================================================
          Shortcode
===================================================================
*/
function contact_form_shortcode() {
    // [contact_form]

    $atts = shortcode_atts(
        array(),
        'contact_form'
    );

    ob_start();
    include 'templates/contact-form.php';
    return ob_get_clean();
}
add_shortcode( 'contact_form', 'contact_form_shortcode' );
