<?php get_header(); ?>

    <div class="wrapper">
        <header class="page-header">
	        <?php get_template_part( 'template-parts/navigation/nav', 'breadcrumbs' ); ?>

            <section class="search-and-filter">
                <div class="search-wrap">
            <span class="search-filter">
                <span class="garage garage-filter"></span>
	            <?php _e("Фильтр", 'garage'); ?>
            </span>
			        <?php get_search_form(); ?>
                </div>
            </section>
            <div class="clearfix"></div>
        </header><!-- .page-header -->

        <?php if ( have_posts() ) : ?>
        <?php endif; ?>
            <main>
                <article class="article">
                    <div class="raw">
                    <?php if ( have_posts() ) : ?>
                        <?php  while ( have_posts() ) : the_post();

                            get_template_part( 'template-parts/page/content', 'main' );

                        endwhile;

                    else :

                        get_template_part( 'template-parts/post/content', 'none' );

                    endif; ?>

                    </div>
                    <div class="clearfix"></div>
                </article>

            </main>

        <?php get_sidebar(); ?>
    </div>

<?php get_footer();
