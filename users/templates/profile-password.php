<div class="profile-content">
<?php

global $user_ID;

// если пользователь не авторизован, отправляем его на страницу входа
if( !$user_ID ) {
	header('location:' . site_url() . '/login.php');
	exit;
} else {
	$userdata = get_user_by( 'id', $user_ID );
}

?>

    <form action="<?php echo get_stylesheet_directory_uri() . '/users/profile-update.php' ?>" method="POST">
        <div class="change-password">
            <input type="password" name="pwd1" placeholder="<?php _e( "Старый пароль", "user-profile" ); ?>" />
            <input type="password" name="pwd2" placeholder="<?php _e( "Новый пароль", "user-profile" ); ?>" />
            <input type="password" name="pwd3" placeholder="<?php _e( "Повторите новый пароль", "user-profile" ); ?>" />
        </div>

        <button class="button button-primary"><?php _e( "Сохранить", "user-profile" ); ?></button>
    </form>
</div>