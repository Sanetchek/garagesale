<?php
/*
 * Template name: Lost Password
 *
 * (url - /lost-password)
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
                    <form name="lostpasswordform" id="lostpasswordform" method="post" class="userform">
                        <label for="user_login"><?php _e( 'Логин или email', 'user-profile' ) ?></label>
                        <input type="text" name="user_login" id="user_login">

                        <input class="button button-primary" type="submit" value="<?php _e( 'Сбросить', 'user-profile' ) ?>">
                        <input type="hidden" name="redirect_to" value="/">
                        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('lost_password'); ?>">
                        <input type="hidden" name="action" value="lost_password">
                        <div class="response"></div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <div class="clearfix"></div>

<?php get_footer(); ?>