<?php
/*
===================================================================
          Register page handler
===================================================================
*/
add_action('wp_ajax_nopriv_register_me', 'register_me');
function register_me() {
    $authorizeSuccessMessage = __( 'Вы уже авторизованы!', 'user-profile' );
    $thirdPartyMessage = __( 'Данные присланные со сторонней страницы ', 'user-profile' );
    $registerStopsMessage = __( 'Регистрация пользователей временно недоступна.', 'user-profile' );
    $emailPriorMessage = __( 'Email - обязательное поле.', 'user-profile' );
    $emailErrorFormatMessage = __( 'Ошибочный формат email', 'user-profile' );
    $loginPriorMessage = __( 'Логин - обязательное поле.', 'user-profile' );
    $passwordPriorMessage = __( 'Пароль - обязательное поле.', 'user-profile' );
    $passwordOnceMoreMessage = __( 'Повторите пароль', 'user-profile' );
    $passwordsIncorrectMessage = __( 'Пароли не совпадают', 'user-profile' );
    $passwordShortMessage = __( 'Слишком короткий пароль', 'user-profile' );
    $passwordBackSlashErrorMessage = __( 'Пароль не может содержать обратные слеши "\\"', 'user-profile' );
    $emailExistMessage = __( 'Пользователь с таким email уже существует.', 'user-profile' );
    $loginExistMessage = __( 'Пользователь с таким логином уже существует.', 'user-profile' );
    $loginLatMessage = __( 'Логин только латиницей.', 'user-profile' );
    $activationSuccessMessage = __( 'Все прошло отлично. Вы зарегистрировались. На вашу почту отправлено письмо с ссылкой на активацию.', 'user-profile' );

    $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : ''; // сначала возьмем скрытое поле nonce
    if (!wp_verify_nonce($nonce, 'register_me_nonce')) wp_send_json_error(array('message' => $thirdPartyMessage, 'redirect' => false)); // проверим его, и если вернулся фолс - используем wp_send_json_error и умираем

    if (is_user_logged_in()) wp_send_json_error(array('message' => $authorizeSuccessMessage, 'redirect' => false)); // далее проверим залогинен ли уже юзер, если да - то делать ничего не надо

    if (!get_option('users_can_register')) wp_send_json_error(array('message' => $registerStopsMessage, 'redirect' => false)); // если регистрацию выключат в админке - то же не будем ничего делать

// теперь возьмем все поля и рассуем по переменным
    $user_login = isset($_POST['user_login']) ? $_POST['user_login'] : '';
    $user_email = isset($_POST['user_email']) ? $_POST['user_email'] : '';
    $pass1 = isset($_POST['pass1']) ? $_POST['pass1'] : '';
    $pass2 = isset($_POST['pass2']) ? $_POST['pass2'] : '';

    $redirect_to = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : false;

// теперь проверим нужные поля на заполненность и валидность
    if (!$user_email) wp_send_json_error(array('message' => $emailPriorMessage, 'redirect' => false));
    if (!preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $user_email)) wp_send_json_error(array('message' => $emailErrorFormatMessage, 'redirect' => false));
    if (!$user_login) wp_send_json_error(array('message' => $loginPriorMessage, 'redirect' => false));
    if (!$pass1) wp_send_json_error(array('message' => $passwordPriorMessage, 'redirect' => false));
    if (!$pass2) wp_send_json_error(array('message' => $passwordOnceMoreMessage, 'redirect' => false));

// теперь проверим все ли ок с паролями
    if ($pass1 != $pass2) wp_send_json_error(array('message' => $passwordsIncorrectMessage, 'redirect' => false));
    if (strlen($pass1) < 4) wp_send_json_error(array('message' => $passwordShortMessage, 'redirect' => false));
    if (false !== strpos(wp_unslash($pass1), "\\" ) ) wp_send_json_error(array('message' => $passwordBackSlashErrorMessage, 'redirect' => false));

    $user_id = wp_create_user($user_login,$pass1,$user_email); // пробуем создать пользователя с переданными данными

// если есть ошибки
    if (is_wp_error($user_id) && $user_id->get_error_code() == 'existing_user_email') wp_send_json_error(array('message' => $emailExistMessage, 'redirect' => false));
    elseif (is_wp_error($user_id) && $user_id->get_error_code() == 'existing_user_login') wp_send_json_error(array('message' => $loginExistMessage, 'redirect' => false));
    elseif (is_wp_error($user_id) && $user_id->get_error_code() == 'empty_user_login') wp_send_json_error(array('message' => $loginLatMessage, 'redirect' => false));
    elseif (is_wp_error($user_id)) wp_send_json_error(array('message' => $user_id->get_error_code(), 'redirect' => false));

// активация, если вам не нужна просто закомментите этот кусок
    $code = sha1($user_id . time()); // сгенерим случайную строку
    $activation_link = home_url().'/activate/?key='.$code.'&user='.$user_id; // создадим ссылку на активацию, подразумевается что на странице с урлом /activate/ у вас сработает механизм активации
    add_user_meta( $user_id, 'has_to_be_activated', $code, true ); // теперь запишем эту случайную строку в мета поля юзера, если это поле не пустое - значит пользователь еще не активировался
    $txt = '<h3>' . _e( "Привет.", 'user-profile' ) . '</h3>
			<p>' . _e( "Для активации пользователя на сайте", 'user-profile' ) . ' '.home_url().' ' . _e( "перейдите по ссылке: ", 'user-profile' ) . '
			<a style="display:block;width:60%;height:50px;background:#264467;color:#fff;" href="'.$activation_link.'">' . _e( "Активировать", 'user-profile' ) . '</a>
			</p>'; // это текст письма
    add_filter( 'wp_mail_content_type', 'set_html_content_type' ); // включаем формат письма в хтмл
    wp_mail( $user_email, _e( "Активация пользователя.", 'user-profile' ), $txt ); // отправляем письмо юзеру
    remove_filter( 'wp_mail_content_type', 'set_html_content_type' ); // выключаем формат письма в хтмл
// активация конец

    wp_send_json_success(array('message' => $activationSuccessMessage, 'redirect' => false)); // говорим что все прошло ок, если нужен редирект то вместо false поставьте $redirect_to

}

function set_html_content_type() { // эта ф-я пригодится нам чтоб слать письма в формате html
    return 'text/html';
}