<div class="account-fields">
    <form name="loginform" id="loginform" method="post" class="userform" action="">
        <div class="user-login">
            <label for="user_login"><?php _e( 'Логин или email', "user-profile" ) ?></label>
            <input type="text" name="log" id="user_login">
        </div>
        <div class="user-pass">
            <label for="user_pass"><?php _e( 'Пароль', "user-profile" ) ?></label>
            <input type="password" name="pwd" id="user_pass">
        </div>

        <input class="button button-primary" type="submit" value="<?php _e( "Войти", "user-profile" ); ?>">
        <input type="hidden" name="redirect_to" value="<?php echo home_url( 'profile' ) ?>">
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('login_me_nonce'); ?>">
        <input type="hidden" name="action" value="login_me">
        <div class="response"></div>
        <label class="rememberme"><input name="rememberme" type="checkbox" value="forever"> <?php _e( 'Запомнить меня', "user-profile" ) ?></label>
        <a href="<?php echo home_url( '/lost-password/' ) ?>" class="login-lost-password"><?php _e( 'Забыли пароль?', "user-profile" ); ?></a>
    </form>

    <div class="social-login">
        <span>Facebook</span>
        <span>Instagram</span>
        <span>Twitter</span>
    </div>
    <div class="clearfix"></div>
</div>