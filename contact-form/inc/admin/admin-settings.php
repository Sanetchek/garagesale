<h1><?php _e('Форма для Контактов', 'theme_language'); ?></h1>

<p><?php _e('Используйте этот <strong>шорткод</strong> для вывода Формы Контактов внутри Страниц или Записей', 'theme_language'); ?></p>
<p><code>[contact_form]</code></p>

<form method="post" action="options.php">
    <?php settings_fields( 'customize-contact-group' ); // function-admin-menu => function customize_theme_settings() ?>
    <?php do_settings_sections( 'customize_contact_form_page' ); //имя страницы на которой выводим поля ?>
    <?php submit_button(); ?>
</form>
