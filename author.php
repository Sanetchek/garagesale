<?php get_header(); ?>

<div id="content" class="narrowcolumn">

	<?php
	if(isset($_GET['author_name'])) :
		$curauth = get_userdatabylogin($author_name);
	else :
		$curauth = get_userdata(intval($author));
	endif;
	?>
     <p><?php create_message(); ?></p>


	<h2>About: <?php echo $curauth->nickname; ?></h2>
	<dl>
		<dt><?php _e('Сайт', 'garage'); ?></dt>
		<dd><a href="<?php echo $curauth->user_url; ?>"><?php echo $curauth->user_url; ?></a></dd>
		<dt><?php _e('Профиль', 'garage'); ?></dt>
		<dd><?php echo $curauth->user_description; ?></dd>
	</dl>

	<h2><?php _e('Запись от ', 'garage'); ?><?php echo $curauth->nickname; ?>:</h2>

	<ul>

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<li>
				<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Постоянная ссылка: ', 'garage'); ?><?php the_title(); ?>">
					<?php the_title(); ?></a>,
				<?php the_time('d M Y'); ?> in <?php the_category('&');?>
			</li>

		<?php endwhile; else: ?>
			<p><?php _e('У этого автора нет товара.', 'garage'); ?></p>

		<?php endif; ?>

	</ul>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?> 