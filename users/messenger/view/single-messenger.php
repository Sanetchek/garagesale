<?php get_header();

if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		?>
        <article>
            <header class="post-header">
                <h1>Messenger</h1>
            </header><!-- .entry-header -->

			<?php
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
			?>

        </article><!-- #post-## -->

		<?php
	endwhile;
endif;

get_footer(); ?>