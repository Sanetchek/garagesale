<div class="account-fields">
	<form name="registrationform" id="registrationform" method="post" class="userform" action="">
        <label for="user_login"><?php _e( 'Логин', "user-profile" ) ?></label>
        <input name="user_login" id="user_login">
        <label for="user_email"><?php _e( 'Email', "user-profile" ) ?></label>
		<input type="email" name="user_email" id="user_email">

        <label for="pass1"><?php _e( 'Пароль', "user-profile" ) ?></label>
		<input type="password" name="pass1" id="pass1">
        <label for="pass2"><?php _e( 'Повторите пароль', "user-profile" ) ?></label>
		<input type="password" name="pass2" id="pass2">

		<input class="button button-primary" type="submit" value="<?php _e( 'Регистрация', "user-profile" ) ?>">
		<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('register_me_nonce'); ?>">
		<input type="hidden" name="action" value="register_me">
		<div class="response"></div>
	</form>
    <div class="social-login">
        <span>Facebook</span>
        <span>Instagram</span>
        <span>Twitter</span>
    </div>
    <div class="clearfix"></div>
</div>