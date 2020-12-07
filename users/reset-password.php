<?php
/*
 * Template name: Reset Password
 *
 * (url - /reset-password)
 */

get_header(); ?>

<div class="account-wrapper">
    <h1>
        <span class="active" ><?php _e( 'Смена пароля', 'user-profile' ) ?></span>
    </h1>

    <div class="account-content">
        <div class="loader"><img src="<?php echo get_template_directory_uri() . '/users/images/spinner.svg' ?>" alt="loader"></div>
        <div class="account-fields">
            <div class="form-wrap">
	            <?php if (!isset($_GET['key']) || !isset($_GET['login']) || is_wp_error(check_password_reset_key($_GET['key'], $_GET['login']))) { // если параметры не передали или ф-я проверки вернула ошибку
		            echo '<p>' . _e( 'Ключ и (или) логин не были переданы, либо не верны.', 'user-profile' ) . '</p>';
		            //resetpass
	            } else { // если все ок показываем форму ?>
                    <form name="resetpassform" id="resetpassform" action="" method="post" class="userform">
                        <div class="pass1">
                            <label for="pass1"><?php _e( 'Новый пароль', 'user-profile' ) ?></label>
                            <input type="password" name="pass1" id="pass1">
                        </div>

                        <div class="pass2">
                            <label for="pass2"><?php _e( 'Повторите новый пароль', 'user-profile' ) ?></label>
                            <input type="password" name="pass2" id="pass2">
                        </div>

                        <input type="hidden" name="key" value="<?php echo esc_attr($_GET['key']); ?>"><!-- переданные параметры сунем в скрытые поля -->
                        <input type="hidden" name="login" value="<?php echo esc_attr($_GET['login']); ?>">
                        <input class="button button-primary" type="submit" value="<?php _e( 'Изменить пароль', 'user-profile' ) ?>">
                        <input type="hidden" name="redirect_to" value="<?php echo isset($_GET['redirect_to']) ? $_GET['redirect_to'] : '/'; ?>">
                        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('reset_password'); ?>">
                        <input type="hidden" name="action" value="reset_password_front">
                        <div class="response"></div>
                    </form>
	            <?php } ?>
            </div>
        </div>

    </div>
</div>
<div class="clearfix"></div>

<?php get_footer(); ?>
