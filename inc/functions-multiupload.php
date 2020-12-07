<?php
/*
   ===================================================================
               Загрузка изображений по всему проекту
   ===================================================================
*/

if(is_admin()) {
    wp_enqueue_script('imagefield', get_template_directory_uri().'/assets/js/multiupload.min.js', array('jquery', 'media-upload', 'jquery-ui-core', 'jquery-ui-sortable')); // Пропишите свой путь к файлу!!!
}

function metaimage_meta_box() {
    add_meta_box(
        'metaimage_meta_box', // Идентификатор(id)
        __('Галерея изображений', 'garage' ), // Заголовок области с мета-полями(title)
        'show_my_metaimage_meta_box', // Вызов(callback)
        array('post','children'), // где будет отображаться, post означает в форме стандартного добавления записи
        'normal',
        'high');
}

add_action('add_meta_boxes', 'metaimage_meta_box'); // Запускаем функцию

// Массив с необходимыми полями
$multiupload_fields = array(
    array(
        'label' => __('Галерея', 'garage' ),
        'desc'  => __('Загрузите нужные изображения', 'garage' ),
        'id'    => 'multiupload',
        'type'  => 'multiupload'
    )
);

function show_my_metaimage_meta_box() {
    global $multiupload_fields; // Обозначим наш массив с полями глобальным
    global $post;  // Глобальный $post для получения id создаваемого/редактируемого поста
// Выводим скрытый input, для верификации. Безопасность прежде всего!
    echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

    // Начинаем выводить таблицу с полями через цикл
    echo '<table class="form-table">';
    foreach ($multiupload_fields as $field) {
        // Получаем значение если оно есть для этого поля
        $meta = get_post_meta($post->ID, $field['id'], true);
        // Начинаем выводить таблицу
        echo '<tr>
                <th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
                <td>';
        switch($field['type']) {
            case 'multiupload':
                echo '<a class="repeatable-add button button-secondary" href="#">'. _e('Добавить поле', 'garage' ) .'</a>
                                <ul id="'.$field['id'].'-repeatable" class="custom_repeatable">';
                $i = 0;
                if ($meta) {
                    foreach($meta as $row) {
                        $image = wp_get_attachment_image_src($row, 'medium'); $image = $image[0];
                        if(empty($row)) $row = "http://placehold.it/100x100";
                        echo '<li style="display: inline-block;margin-right: 20px;position:relative;"><img style="width:130px;" class="custom_preview_image sort hndle" src="'.$row.'" />
                                        <div style="position: absolute;bottom: -4px;right: -15px;width: 60%;">
                                        <input name="'.$field['id'].'['.$i.']" id="'.$field['id'].'" type="hidden" class="custom_upload_image" value="'.$row.'" />
                                        <a style="text-decoration: none;background:#fff;border-radius:50%;border: 1px solid #0073aa;" title="'. _e('Добавить изображение', 'garage' ) .'" class="custom_upload_file_button" href="#"><span style="position: relative;left:-0.5px" class="dashicons dashicons-plus"></span></a>
                                        <a style="text-decoration: none;background:#fff;border-radius:50%;border: 1px solid #0073aa;" title="'. _e('Удалить изображение', 'garage' ) .'" class="repeatable-remove" href="#"><span style="line-height: 18px;" class="dashicons dashicons-no-alt"></span></a>
                                        </div>
                                    </li>';
                        $i++;
                    }
                } else {
                    echo
                        '<li style="display: inline-block;margin-right: 20px;position:relative;">
                                <img style="width:130px;" src="http://placehold.it/100x100" class="custom_preview_image sort hndle" alt="" />
                                <div style="position: absolute;bottom: -4px;right: -15px;width: 60%;">
                                <span class="dashicons dashicons-menu"></span>
                                <input name="'.$field['id'].'['.$i.']" id="'.$field['id'].'" type="hidden" class="custom_upload_image" value="" />
                                <a style="text-decoration: none;background:#fff;border-radius:50%;border: 1px solid #0073aa;" title="'. _e('Добавить изображение', 'garage' ) .'" class="custom_upload_file_button" href="#"><span style="position: relative;left:-0.5px" class="dashicons dashicons-plus"></span></a>
                                <a style="text-decoration: none;background:#fff;border-radius:50%;border: 1px solid #0073aa;" title="'. _e('Удалить изображение', 'garage' ) .'" class="repeatable-remove" href="#"><span style="line-height: 18px;" class="dashicons dashicons-no-alt"></span></a>
                                </div>
                            </li>';
                }
                echo '</ul>
                            <span class="description">'.$field['desc'].'</span>';
                break;

        }
        echo '</td></tr>';
    }
    echo '</table>';
}

function save_my_metaimage_meta_box($post_id) {
    global $multiupload_fields;  // Массив с нашими полями

    // проверяем наш проверочный код
    if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))
        return $post_id;
    // Проверяем авто-сохранение
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
    // Проверяем права доступа
    if ('image_meta_box_book' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    // Если все отлично, прогоняем массив через foreach
    foreach ($multiupload_fields as $field) {
        $old = get_post_meta($post_id, $field['id'], true); // Получаем старые данные (если они есть), для сверки
        $image_meta_box = $_POST[$field['id']];
        if($field['type'] == 'multiupload')
            $image_meta_box = array_values($image_meta_box);
        if ($image_meta_box && $image_meta_box != $old) {  // Если данные новые
            update_post_meta($post_id, $field['id'], $image_meta_box); // Обновляем данные
        } elseif ('' == $image_meta_box && $old) {
            delete_post_meta($post_id, $field['id'], $old); // Если данных нету, удаляем мету.
        }
    } // end foreach
}
add_action('save_post', 'save_my_metaimage_meta_box'); // Запускаем функцию сохранения