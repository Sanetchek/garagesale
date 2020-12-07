<div class="profile-sidebar">
    <div class="image-container">
        <div class="profile-picture" style="background-image: url(<?php echo changeGenderImage( $user_ID ) ?>)">
            <?php if ( esc_attr(get_the_author_meta('avatar',$user_ID)) ): ?>
                <img id="profile-picture-preview" src="<?php echo esc_attr(get_the_author_meta('avatar',$user_ID));?>">
            <?php endif; ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="button-product">
        <button class="button button-secondary"><?php _e( "Добавить товар", "user-profile" ); ?></button>
    </div>
    <div>
        <ul class="profile-menu">
            <li class="profile-info active"><span class="pr-info" data-page="1" data-url="<?php echo admin_url('admin-ajax.php'); ?>"><?php _e( "Профиль", "user-profile" ); ?></span></li>
            <li class="profile-info"><span class="pr-password" data-page="2" data-url="<?php echo admin_url('admin-ajax.php'); ?>"><?php _e( "Сменить пароля", "user-profile" ); ?></span></li>
            <li class="profile-info"><span class="pr-message" data-page="3" data-url="<?php echo admin_url('admin-ajax.php'); ?>"><?php _e( "Сообщения", "user-profile" ); ?></span></li>
            <li class="profile-info"><span class="pr-article" data-page="4" data-url="<?php echo admin_url('admin-ajax.php'); ?>"><?php _e( "Статьи", "user-profile" ); ?></span></li>
            <li class="profile-info"><span class="pr-product" data-page="5 data-url="<?php echo admin_url('admin-ajax.php'); ?>"><?php _e( "Товары", "user-profile" ); ?></span></li>
            <li class="profile-info"><span class="pr-advancement" data-page="6" data-url="<?php echo admin_url('admin-ajax.php'); ?>"><?php _e( "Продвижение", "user-profile" ); ?></span></li>
            <li class="profile-info"><span class="pr-statistic" data-page="7" data-url="<?php echo admin_url('admin-ajax.php'); ?>"><?php _e( "Статистика", "user-profile" ); ?></span></li>
        </ul>
    </div>
</div>