<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
	    <?php get_template_part( 'template-parts/navigation/nav', 'breadcrumbs' ); ?>
        <?php the_title( '<h1>', '</h1>' ); ?>

    </header><!-- .entry-header -->

    <div>

        <?php the_content(); ?>
        <p>
	    <?php echo get_post_meta($post->ID, 'mytextinput', true) ?>
        </p>
        <p>
        <?php echo get_post_meta($post->ID, 'mytextarea', true) ?>
        </p>
        <p>
            <?php echo get_post_meta($post->ID, 'mycheckbox', true) ?>
        </p>
        <p>
        <?php echo get_post_meta($post->ID, 'city_search_select', true) ?>
        </p>
    </div>
</article>
