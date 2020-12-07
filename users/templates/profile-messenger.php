<div class="profile-content">
	<?php
	$current_user = wp_get_current_user();
	$current_user_id = $current_user->ID;

	$args = array(
		'post_type' => array( 'messenger' ),
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key' => 'recipient',
				'value' => $current_user_id
			),
			array(
				'key' => 'sender',
				'value' => $current_user_id
			)
        )
	);
	$the_query = new WP_Query( $args );

	if ( $the_query->have_posts() ) :
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$sender = get_post_meta( get_the_ID(), 'sender', 1);
			$recipient = get_post_meta( get_the_ID(), 'recipient', 1);
	?>

		<article>
			<header class="messenger-header">

				<div class="messenger-title">
                    
					<a href="<?php the_permalink(); ?>">
                        <div class="messenger-profile">

                            <?php  if ( $current_user_id == $sender ) : ?>
                                <img src="<?php echo message_author_photo( $recipient ) ?>" alt="">
                                <span><?php echo message_author_name( $recipient ); ?></span>
                            <?php  else : ?>
                                <img src="<?php echo message_author_photo( $sender ) ?>" alt="">
                                <span><?php echo message_author_name( $sender ); ?></span>   
                            <?php  endif; ?>

                        </div>
                    </a>

                </div>

			</header>
		</article>

	<?php
		endwhile;
	endif;
	?>

</div>