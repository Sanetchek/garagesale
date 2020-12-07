<?php

// Добавляем дополнительное поле
function citySearchBox() {
	add_meta_box(
		'city_search_box', // Идентификатор(id)
		__('Город', 'garage'), // Заголовок области с мета-полями(title)
		'show_city_search_box', // Вызов(callback)
		array('post','children'), // Где будет отображаться наше поле, в нашем случае в Записях
		'normal',
		'high');
}
add_action('add_meta_boxes', 'citySearchBox'); // Запускаем функцию

$meta_fields = array(
	array(
		'label' => __('Текстовое поле', 'garage'),
		'desc'  => __('Описание для поля.', 'garage' ),
		'id'    => 'mytextinput', // даем идентификатор.
		'type'  => 'text'  // Указываем тип поля.
	),
	array(
		'label' => __('Большое текстовое поле', 'garage' ),
		'desc'  => __('Описание для поля.', 'garage' ),
		'id'    => 'mytextarea',  // даем идентификатор.
		'type'  => 'textarea'  // Указываем тип поля.
	),
	array(
		'label' => __('Чекбоксы (флажки)', 'garage' ),
		'desc'  => __('Описание для поля.', 'garage' ),
		'id'    => 'mycheckbox',  // даем идентификатор.
		'type'  => 'checkbox'  // Указываем тип поля.
	),
	array(
		'label' => __('Город', 'garage' ),
		'desc'  => __('Выберите Город.', 'garage' ),
		'id'    => 'city_search_select',
		'type'  => 'select',
		'options' => array (  // Параметры, всплывающие данные
			'ukraine' => array (
				'label' => __('Вся Украина', 'garage' ),  // Название поля
				'value' => __('Вся Украина', 'garage' )  // Значение
			),
			'kyiv' => array (
				'label' => __('Киев', 'garage' ),  // Название поля
				'value' => __('Киев', 'garage' )  // Значение
			),
			'odessa' => array (
				'label' => __('Одесса', 'garage' ),  // Название поля
				'value' => __('Одесса', 'garage' )  // Значение
			)
		)
	)
);

// Вызов метаполей
function show_city_search_box() {
	global $meta_fields; // Обозначим наш массив с полями глобальным
	global $post;  // Глобальный $post для получения id создаваемого/редактируемого поста
// Выводим скрытый input, для верификации. Безопасность прежде всего!
	echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

	// Начинаем выводить таблицу с полями через цикл
	echo '<table class="form-table">';
	foreach ($meta_fields as $field) {
		// Получаем значение если оно есть для этого поля
		$meta = get_post_meta($post->ID, $field['id'], true);
		// Начинаем выводить таблицу
		echo '<tr> 
                <th><label for="'.$field['id'].'">'.$field['label'].'</label></th> 
                <td>';
		switch($field['type']) {
			case 'text':
				echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
        <br /><span class="description">'.$field['desc'].'</span>';
				break;
			case 'textarea':
				echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea> 
        <br /><span class="description">'.$field['desc'].'</span>';
				break;
			case 'checkbox':
				echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/>
        <label for="'.$field['id'].'">'.$field['desc'].'</label>';
				break;
// Всплывающий список
			case 'select':
				echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
				foreach ($field['options'] as $option) {
					echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';
				}
				echo '</select><br /><span class="description">'.$field['desc'].'</span>';
				break;
		}
		echo '</td></tr>';
	}
	echo '</table>';
}

// Пишем функцию для сохранения
function save_my_meta_fields($post_id) {
	global $meta_fields;  // Массив с нашими полями

	// проверяем наш проверочный код
	if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))
		return $post_id;
	// Проверяем авто-сохранение
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return $post_id;
	// Проверяем права доступа
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id))
			return $post_id;
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}

	// Если все отлично, прогоняем массив через foreach
	foreach ($meta_fields as $field) {
		$old = get_post_meta($post_id, $field['id'], true); // Получаем старые данные (если они есть), для сверки
		$new = $_POST[$field['id']];
		if ($new && $new != $old) {  // Если данные новые
			update_post_meta($post_id, $field['id'], $new); // Обновляем данные
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old); // Если данных нету, удаляем мету.
		}
	} // end foreach
}
add_action('save_post', 'save_my_meta_fields'); // Запускаем функцию сохранения