<?php
/*
===================================================================
          Login page handler
===================================================================
*/
add_action('wp_ajax_nopriv_login_me', 'login_me');
//add_action('wp_ajax_login_me', 'login_me');
function login_me(){
    $thirdPartyMessage = __( 'Данные присланные со сторонней страницы ', 'user-profile'  );
    $authMessage = __( 'Вы уже авторизованы.', 'user-profile' );
    $loginEmptyMessage = __( 'Поле логин или email не заполнено', 'user-profile' );
    $passEmptyMessage = __( 'Поле пароль не заполнено', 'user-profile' );
    $loginPassErrorMessage = __( 'Ошибка. Проверте поля: логин/email или пароль.', 'user-profile' );
    $userActivateErrorMessage = __( 'Пользователь еще не активирован.', 'user-profile' );

    $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : ''; // сначала возьмем строку безопасности
    if (!wp_verify_nonce($nonce, 'login_me_nonce')) wp_send_json_error(array('message' => $thirdPartyMessage, 'redirect' => false)); // проверим её специальной функцией, а если строки не совпадут отправляем json ответ с ошибкой, функция wp_send_json_error сама прекратит работу скрипта

    if (is_user_logged_in()) wp_send_json_error(array('message' => $authMessage, 'redirect' => false)); // теперь проверим не залогинен ли уже юзер, если да, то ошибка

    $log = isset($_POST['log']) ? $_POST['log'] : false; // получаем данные с формы
    $pwd = isset($_POST['pwd']) ? $_POST['pwd'] : false;
    $redirect_to = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : false;
    $rememberme = isset($_POST['rememberme']) ? $_POST['rememberme'] : false;

    if (!$log) wp_send_json_error(array('message' => $loginEmptyMessage, 'redirect' => false)); // если что то из полей пусто - ошибка
    if (!$pwd) wp_send_json_error(array('message' => $passEmptyMessage, 'redirect' => false));

    $user = get_user_by( 'login', $log ); // саначала попробуем найти юзера по логину
    if (!$user) $user = get_user_by( 'email', $log ); // если там пусто, значит попробуем получить юзера по мылу

    if (!$user) wp_send_json_error(array('message' => $loginPassErrorMessage, 'redirect' => false)); // если в обоих случаях пустота, значит такого юзера нет - возвращаем ошибку и умираем
    if (get_user_meta( $user->ID, 'has_to_be_activated', true ) != false) wp_send_json_error(array('message' => $userActivateErrorMessage, 'redirect' => false)); // закомментить если не используется активация, см. след. статьи

    $log = $user->user_login; // если скрипт работает значит юзер есть - достанем логин

    $creds = array( // создаем массив с данными для логина
        'user_login' => $log,
        'user_password' => $pwd,
        'remember' => $rememberme
    );
    $user = wp_signon( $creds, false ); // пробуем залогинется
    if (is_wp_error($user)) wp_send_json_error(array('message' => $loginPassErrorMessage, 'redirect' => false)); // если вернулся объект с ошибкой  - умираем и пешем ошибку, уточнять не будем
    else wp_send_json_success(array('message' => '', 'redirect' => $redirect_to)); // иначе все прошло ок и юзера залогинили пишем что все ок и отпраляем
}