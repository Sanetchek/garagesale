<?php
/*
===================================================================
          Lost password page handler
===================================================================
*/
add_action('wp_ajax_nopriv_lost_password', 'lost_password');  // вешаем хук на аякс запрос от незалогиненного юзера с параметром action=lost_password, это означает что юзер запросил восстановление пароля
function lost_password(){ // запускается эта ф-я
    $thirdPartyMessage = __( 'Данные присланные со сторонней страницы ', 'user-profile' );
    $emptyFieldMessage = __( 'Вы не заполнили поле', 'user-profile' );
    $checkEmailMessage = __( 'Пользователя с таким email не существует.', 'user-profile' );
    $activationErrorMessage = __( 'Пользователь еще не активирован.', 'user-profile' );
    $passwordErrorMessage = __( 'Сброс пароля запрещен. Пожалуйста свяжитесь с администратором сайта.', 'user-profile' );
    $emailResetPasswordMessage = __( 'Письмо со ссылкой на страницу изменения пароля отправлено на адрес, указанный при регистрации. Если вы не получили письмо, проверьте папку "Спам" или попробуйте еще раз.', 'user-profile' );

    $nonce = $_POST['nonce'];
    if (!wp_verify_nonce($nonce, 'lost_password')) wp_send_json_error(array('message' => $thirdPartyMessage, 'redirect' => false)); // сначала проверим что форма отправлена откуда надо

    $user_login = $_POST['user_login']; // запишем данные в переменные
    $redirect_to = $_POST['redirect_to'];

    if (!$user_login) wp_send_json_error(array('message' => $emptyFieldMessage, 'redirect' => false)); // если не заполнили

    global $wpdb, $current_site; // это надо заглобалить

    if (strpos($user_login,'@')) { // если передали email
        $user = get_user_by('email',trim($user_login)); // пробуеми получить юзера по мылу
    } else { // инапче передали логин
        $user = get_user_by('login', trim($user_login)); // пробуем получить юзера по логину
    }

    if (!$user) wp_send_json_error(array('message' => $checkEmailMessage, 'redirect' => false)); // если юера не нашли то ошибка
    if (get_user_meta( $user->ID, 'has_to_be_activated', true ) != false) wp_send_json_error(array('message' => $activationErrorMessage, 'redirect' => false)); // если юзер еще не активировался, расскомментить если используется активация, см. след. статьи


    do_action('lostpassword_post'); // чтобы работали всякие другие хуки

    $user_login = $user->user_login; // запишим данные которые достали
    $user_email = $user->user_email;

    do_action('retrieve_password', $user_login); // чтобы работали всякие другие хуки

    $allow = apply_filters('allow_password_reset', true, $user->ID); // проверим возможность сброса пароля

    if (!$allow) wp_send_json_error(array('message' => $passwordErrorMessage, 'redirect' => false)); // значит нельзя
    else if (is_wp_error($allow)) wp_send_json_error(array('message' => $allow->get_error_message(), 'redirect' => false)); // если какая либо другая ошибка

    $key = wp_generate_password(20, false); // генерируем уникальный строку-ключ

    do_action('retrieve_password_key', $user_login, $key); // чтобы работали всякие другие хуки

    if ( empty( $wp_hasher ) ) {
        require_once ABSPATH . WPINC . '/class-phpass.php'; // подключим спец.либу для создания хэшей для сброса
        $wp_hasher = new PasswordHash( 8, true ); // создаем экзепляр класса
    }

//создаем хэш
//$hashed = $wp_hasher->HashPassword($key); // создание хэша для версий ниже 4.3
    $hashed = time() . ':' . $wp_hasher->HashPassword( $key ); // создание хэша для версий выше 4.3

    $wpdb->update( $wpdb->users, array('user_activation_key' => $hashed), array('user_login' => $user_login)); // пишим в базу что для данный юзер запросил смену пароля

//отправляем письмо с сылкой на сброс пароля
    $reset_link = home_url().'/reset-password/?key='.$key.'&login='.rawurlencode($user_login).'&redirect_to='.esc_attr($redirect_to); // создадаем ссылку на сброс пароля, подразумевается что на странице с урлом /reset-password/ у вас будет форма залания нового пароля
    $txt = '<h3>' . _e( "Привет.", 'user-profile' ) . '</h3>
			<p>' . _e( "Кто-то запросил сброс пароля на сайте:", 'user-profile' ) . ' '.home_url().' ' . __( ", чтобы сбросить пароль перейдите по ссылке:", 'user-profile' ) . '
			<a style="display:block;width:60%;height:50px;background:#264467;color:#fff;" href="'.$reset_link.'">' . _e( "Сбросить пароль", 'user-profile' ) . '</a> 
			' . _e( "либо проигнорируйте это письмо.", 'user-profile' ) . '</p>'; // это текст письма
    add_filter( 'wp_mail_content_type', 'set_html_content_type' ); // включаем формат письма в хтмл
    wp_mail( $user_email, _e( "Сброс пароля пользователя ", 'user-profile' ).$user_login, $txt ); // отправляем письмо юзеру
    remove_filter( 'wp_mail_content_type', 'set_html_content_type' ); // выключаем формат письма в хтмл

    wp_send_json_success(array('message' => $emailResetPasswordMessage, 'redirect' => false)); // пишим что все ок

}