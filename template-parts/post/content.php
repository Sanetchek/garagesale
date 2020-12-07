<article>
    <header class="post-header">
	    <?php get_template_part( 'template-parts/navigation/nav', 'breadcrumbs' ); ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>">
	            <?php
		            the_post_thumbnail('large');
                ?>
            </a>
        </div><!-- .post-thumbnail -->

        <?php if ( is_single() ) {
                the_title( '<h1>', '</h1>' );
            } elseif ( is_front_page() && is_home() ) {
                the_title( '<h2><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
            } else {
                the_title( '<h2><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
            }
        ?>

    </header><!-- .entry-header -->

    <main>
        <p><?php create_message(); ?></p>
        <?php the_content(); ?>

        <?php $songs = get_post_meta($post->ID,'songs',false);

        echo "<br>";
        $keys = array_keys($songs);
        for($i = 0; $i < count($songs); $i++) {
	        foreach($songs[$keys[$i]] as $key => $value) {
		        foreach($value as $key_value) {
			        echo $key_value . " - ";
		        }
		        echo "<br>";
	        }
        }
        ?>
    </main>

    <?php
    if ( comments_open() || get_comments_number() ) :
	    comments_template();
    endif;
    ?>

	<?php


	the_post_navigation( array(
		'prev_text' => '<span>' . _e( 'Предыдущая страница: ', 'garage' ) . '</span>'.  '<span class="post-title">%title</span>',
		'next_text' => '<span>' . _e( 'Следующая страница: ', 'garage' ) . '</span>'.  '<span class="post-title">%title</span>',
		'before_page_number' => '<span>' . _e( 'Страница: ', 'garage' ) . ' </span>',
	) );
    ?>

</article><!-- #post-## -->