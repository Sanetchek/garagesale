<?php
/*
 * Template name: Profile page
 *
 * (url - /profile)
 */
get_header();
?>

<?php

get_template_part( 'template-parts/navigation/nav', 'breadcrumbs' );
get_template_part( 'users/templates/profile', 'sidebar' );

?>

    <div class="profile-wrap">
        <div class="loader"><img src="<?php echo get_template_directory_uri() . '/users/images/spinner.svg' ?>" alt="loader"></div>
        <?php

        get_template_part( 'users/templates/profile', 'page' );

        ?>
    </div>

<div class="clearfix"></div>
<?php get_footer(); ?>