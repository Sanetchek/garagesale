<?php get_header(); ?>

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

    <article class="article">
        <div class="raw">
            <?php
            $shopPostTypes = get_shop_goods();

            $args = array(
	            'post_type' => $shopPostTypes
            );
            $the_query = new WP_Query( $args );

            if ( $the_query->have_posts() ) :
                while ( $the_query->have_posts() ) : $the_query->the_post();

                    get_template_part( 'template-parts/page/content', 'main' );

                endwhile;

            endif; ?>
        </div>
        <div class="clearfix"></div>
    </article>

<?php get_sidebar(); ?>
<?php get_footer(); ?>