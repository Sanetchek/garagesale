<?php
/*
 * Template name: Login page
 *
 * (url - /login)
 */

get_header(); ?>

<div class="account-wrapper">
    <h1>
        <span class="login-fields active" data-page="login" ><?php _e( 'Логин', 'user-profile' ) ?></span>
        <span class="login-separator">/</span>
        <span class="register-fields" data-page="register" ><?php _e( 'Регистрация', 'user-profile' ) ?></span>
    </h1>

    <div class="account-content">
        <div class="loader"><img src="<?php echo get_template_directory_uri() . '/users/images/spinner.svg' ?>" alt="loader"></div>
	    <?php

	    get_template_part( 'users/templates/profile', 'login' );

	    ?>
    </div>
</div>
<?php get_footer(); ?>