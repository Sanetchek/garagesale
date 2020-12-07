<?php get_header(); ?>

	<a href="<?php echo home_url( 'shop' ) ?>"><span class="button"><?php _e('Shop', 'garage'); ?></span></a>
    <?php if ( have_posts() ) :
        while ( have_posts() ) : the_post();

            get_template_part( 'template-parts/page/content', 'page' );

        endwhile;

    else :

        get_template_part( 'template-parts/page/content', 'none' );

    endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>

<?php

$text = '';

echo strip_tags($text);

?>
