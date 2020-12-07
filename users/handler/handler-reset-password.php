<?php
/*
===================================================================
          Reset password page handler
===================================================================
*/
add_action('wp_ajax_nopriv_reset_password_front', 'reset_password_front');  // вешаем хук на аякс запрос от незалогиненного юзера с параметром action=reset_password, это означает что юзер отправил саму форму с восстановлением пароля
function reset_password_front(){ // запускается эта ф-я
    $thirdPartyMessage = __( 'Данные присланные со сторонней страницы ', 'user-profile' );
    $changePasswordParamMessage = __( 'Параметры изменения пароля отсутствуют.', 'user-profile' );
    $fillPasswordFieldMessage = __( 'Заполните поля с паролями.', 'user-profile' );
    $oldSecurityKeyMessage = __( 'Ключ безопасности устарел, запросите смену пароля повторно.', 'user-profile' );
    $wrongSecurityKeyMessage = __( 'Ключ безопасности не верный, запросите смену пароля повторно.', 'user-profile' );
    $passwordsIncorrectMessage = __( 'Пароли не совпадают', 'user-profile' );
    $changePasswordsSuccessMessage = __( 'Вы удачно изменили пароль.', 'user-profile' );

    $nonce = $_POST['nonce'];
    if (!wp_verify_nonce($nonce, 'reset_password')) wp_send_json_error(array('message' => $thirdPartyMessage, 'redirect' => false)); // проверим соль

    $redirect_to = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : ''; // пишим в переменные
    $rp_key = isset($_POST['key']) ? $_POST['key'] : '';
    $rp_login = isset($_POST['login']) ? $_POST['login'] : '';
    $pass1 = isset($_POST['pass1']) ? $_POST['pass1'] : '';
    $pass2 = isset($_POST['pass2']) ? $_POST['pass2'] : '';

    if (!$rp_key || !$rp_login) { // теперь параметры сброса пароля
        wp_send_json_error(array('message' => $changePasswordParamMessage, 'redirect' => false));
    }

    if (!$pass1 || !$pass2) { // теперь проверим что поля с паролями заполнилои
        wp_send_json_error(array('message' => $fillPasswordFieldMessage, 'redirect' => false));
    }

    $user = check_password_reset_key($rp_key, $rp_login); // это стандартная ф-я проверки ключа для сброса, если все ок вернется объект с пользователем, если нет, то объект с ошибкой

    if (!$user || is_wp_error($user)) { // если что-то не так пошло
        if ($user && $user->get_error_code() === 'expired_key' ) wp_send_json_error(array('message' => $oldSecurityKeyMessage, 'redirect' => false));
        else wp_send_json_error(array('message' => $wrongSecurityKeyMessage, 'redirect' => false));
    } // пишим всякие ошибки

    if ($pass1 != $pass2) wp_send_json_error(array('message' => $passwordsIncorrectMessage, 'redirect' => false)); // теперь проверим что пароли совпадают

    do_action('validate_password_reset', new WP_Error(), $user); // чтобы работали другие хуки

    reset_password($user, $pass1); // ставим новый пас

    wp_send_json_success(array('message' => $changePasswordsSuccessMessage, 'redirect' => $redirect_to ? $redirect_to : '/')); // все ок

}