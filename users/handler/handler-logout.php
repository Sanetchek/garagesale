<?php
/*
===================================================================
          Logout page handler
===================================================================
*/
//add_action('wp_ajax_nopriv_logout_me', 'logout_me');
add_action('wp_ajax_logout_me', 'logout_me');
function logout_me() { // logout
    $thirdPartyMessage = __( 'Данные присланные со сторонней страницы ', 'user-profile' );
    $authErrorMessage = __( 'Вы не авторизованы.', 'user-profile' );
    $logOutMessage = __( 'Вы вышли.', 'user-profile' );

    $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : ''; // берем строку безопасности
    if (!wp_verify_nonce($nonce, 'logout_me_nonce')) wp_send_json_error(array('message' => $thirdPartyMessage, 'redirect' => false)); // проверяем
    if (!is_user_logged_in()) wp_send_json_error(array('message' => $authErrorMessage, 'redirect' => false)); // если юзер не авторизован то ничо не делаем

    wp_logout(); // выходим.

    wp_send_json_success(array('message' => $logOutMessage, 'redirect' => false)); // пишем что все ок
}