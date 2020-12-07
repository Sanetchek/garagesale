<header id="header">
	<div class="wrapper">
		<div class="left">
			<a href="<?php echo get_home_url(); ?>" class="logo">
				<span class="garage garage-logo"></span>
				<h2><?php bloginfo( 'name' ); ?></h2>
			</a>
		</div>
		<div class="right">
			<div class="header-nav">
                <span class="favorites">
                    <span class="garage garage-heart"></span>
                    <span class="favor-count">0</span>
                </span>
				<span class="user-profile">

                    <?php if (!is_user_logged_in()){ ?>
                        <a href="<?php echo home_url() . '/login' ?>"><span class="garage garage-user"></span></a>
                    <?php } else { ?>
                        <span class="head-user-profile">
                            <?php $current_user = wp_get_current_user(); ?>
                            <a href="<?php echo home_url() . '/profile' ?>">
                                <span class="head-user-name">
                                    <?php $gender = get_the_author_meta('gender', $user_ID );
                                    $userdata = get_user_by( 'id', $user_ID );

                                    switch ($gender) {
	                                    case 'company':
		                                    echo esc_attr(get_the_author_meta('organization', $user_ID));
		                                    break;
	                                    case 'male':
	                                    case 'female':
		                                    if( !$userdata->first_name ) {
			                                    echo $userdata->nickname;
		                                    } else {
			                                    echo $userdata->first_name . ' ' . $userdata->last_name;
		                                    }
		                                    break;
                                    }
                                    ?>
                                </span>
			                    <?php if( !get_the_author_meta('avatar',$user_ID) ) : ?>
                                    <img alt="Profile picture" src="<?php echo changeGenderImage( $user_ID ) ?>">
			                    <?php else : ?>
                                    <img alt="Profile picture" src="<?php echo esc_attr(get_the_author_meta('avatar',$user_ID));?>">
			                    <?php endif; ?>

                            </a>
                            <span class="head-profile-logout">
                                <a href="<?php echo home_url() . '/profile' ?>"><?php _e( 'Профиль', 'garage' ) ?></a>
                                <a href="#" class="logout" data-nonce="<?php echo wp_create_nonce('logout_me_nonce'); ?>"><?php _e( 'Выйти', 'garage' ) ?></a>
                            </span>
                        </span>
                    <?php }?>

                </span>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
</header>