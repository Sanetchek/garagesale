<div class="profile-content">
<?php

global $user_ID;

// если пользователь не авторизован, отправляем его на страницу входа
if( !$user_ID ) {
    header('location:' . site_url() . '/login/');
    exit;
} else {
    $userdata = get_user_by( 'id', $user_ID );
}

?>

	<form action="<?php echo get_template_directory_uri() . '/users/profile-update.php' ?>" method="POST">
        <div class="profile-gender"><!-- Установить пол пользователя или компания -->
            <label class="label-head" for="gender"><?php _e( "Пользователь", "user-profile" ); ?></label>
            <?php $gender = get_the_author_meta('gender', $user_ID ); ?>
            <ul>
                <li><label><input id="gender-man" value="male" name="gender"<?php if ($gender == 'male' or !$gender ) { ?> checked="checked"<?php } ?> type="radio" /> <?php _e( "мужчина", "user-profile" ); ?></label></li>
                <li><label><input id="gender-woman" value="female"  name="gender"<?php if ($gender == 'female') { ?> checked="checked"<?php } ?> type="radio" /> <?php _e( "женщина", "user-profile" ); ?></label></li>
                <li><label><input id="gender-company" value="company"  name="gender"<?php if ($gender == 'company') { ?> checked="checked"<?php } ?> type="radio" /> <?php _e( "компания", "user-profile" ); ?></label></li>
            </ul>
        </div> <!-- Установить пол пользователя или компания -->

        <div class="profile-organization"> <!-- Название организации -->
            <label class="label-head" for="organization"><?php _e( "Название организации", "user-profile" ); ?></label>
            <input id="organization" value="<?php echo esc_attr(get_the_author_meta('organization',$user_ID));?>" name="organization" type="text" placeholder="<?php _e( "Введите название организации", "user-profile" ); ?>" />
        </div> <!-- Название организации -->

        <div class="profile-fio"> <!-- ФИО -->
            <div>
                <label class="label-head" for="first_name"><?php _e( "Имя", "user-profile" ); ?></label>
                <input id="first_name" type="text" name="first_name" placeholder="<?php _e( "Введите Имя", "user-profile" ); ?>" value="<?php echo $userdata->first_name ?>" />
            </div>
            <div>
                <label class="label-head" for="last_name"><?php _e( "Фамилия", "user-profile" ); ?></label>
                <input id="last_name" type="text" name="last_name" placeholder="<?php _e( "Введите Фамилия", "user-profile" ); ?>" value="<?php echo $userdata->last_name ?>" />
            </div>
        </div>  <!-- ФИО -->

        <div class="profile-email"> <!-- Электронный адрес -->
            <label class="label-head" for="email"><?php _e( "Электронный адрес", "user-profile" ); ?></label>
            <input type="email" name="email" placeholder="e-mail" value="<?php echo $userdata->user_email ?>" />
        </div><!-- Электронный адрес -->

        <div class="profile-phones"> <!-- Номер телефона -->
            <label class="label-head phone" for="phone1"><?php _e( "Номер телефона", "user-profile"); ?></label>
            <input id="phone1" value="<?php echo esc_attr(get_the_author_meta('phone1',$user_ID));?>" name="phone1" type="tel" placeholder="<?php _e( "Введите номер телефона", "user-profile" ); ?>" />
            <input id="phone2" value="<?php echo esc_attr(get_the_author_meta('phone2',$user_ID));?>" name="phone2" type="tel" placeholder="<?php _e( "Введите номер телефона", "user-profile" ); ?>" />
            <input id="phone3" value="<?php echo esc_attr(get_the_author_meta('phone3',$user_ID));?>" name="phone3" type="tel" placeholder="<?php _e( "Введите номер телефона", "user-profile" ); ?>" />
        </div>  <!-- Номер телефона -->

        <div class="profile-avatar"> <!-- Загрузить / Удалить Аватар -->
            <label class="label-head"><?php _e( "Изображение профиля", "user-profile" ); ?></label>
            <input type="button" value="<?php _e( "Выбрать Изображение", "user-profile" ); ?>" id="upload-button" class="button button-secondary">
            <input type="button" value="<?php _e( "Удалить", "user-profile" ); ?>" id="delete-button" class="button button-cancel">
            <input type="hidden" name="avatar" id="avatar" value="<?php echo esc_attr(get_the_author_meta('avatar', $user_ID ));?>" /><br />
        </div> <!-- Загрузить / Удалить Аватар -->

        <div class="profile-location"> <!-- Местоположение -->

            <!-- Ввод местоположения с помощью Google Maps Autocomplete -->
            <label class="label-head" for="city_search"><?php _e( "Местоположение", "user-profile" ); ?></label>
            <div id="locationField">
                <input id="autocomplete" class="city-search" name="city_search" placeholder="<?php _e( "Введите адрес", "user-profile" ); ?>" type="text" value="<?php echo esc_attr(get_the_author_meta('city_search',$user_ID));?>" autocomplete="off" >
                <input class="field" id="street_number" type="hidden" disabled="true">
                <input class="field" id="route" type="hidden" disabled="true">
                <input class="field" id="locality" type="hidden" disabled="true">
                <input class="field" id="administrative_area_level_1" type="hidden" disabled="true">
                <input class="field" id="postal_code" type="hidden" disabled="true">
                <input class="field" id="country" type="hidden" disabled="true">
            </div>

            <!-- Область / Индекс -->
            <div class="profile-admin-area">
                <label class="label-secondary" for="admin_area"><?php _e( "Область", "user-profile" ); ?></label>
                <input id="admin_area" type="text" name="admin_area" value="<?php echo esc_attr(get_the_author_meta('admin_area',$user_ID)); ?>" >
                <label class="label-secondary" for="post_code"><?php _e( "Индекс", "user-profile" ); ?></label>
                <input id="post_code" type="text" name="post_code" value="<?php echo esc_attr(get_the_author_meta('post_code',$user_ID)); ?>" >
            </div>

            <!-- Адрес / Дом -->
            <div class="profile-city-route">
                <label class="label-secondary" for="city_route"><?php _e( "Адрес", "user-profile" ); ?></label>
                <input id="city_route" type="text" name="city_route" value="<?php echo esc_attr(get_the_author_meta('city_route',$user_ID)); ?>" >
                <label class="label-secondary" for="street_num"><?php _e( "Дом", "user-profile" ); ?></label>
                <input id="street_num" type="text" name="street_num" value="<?php echo esc_attr(get_the_author_meta('street_num',$user_ID)); ?>" >
            </div>

            <!-- Город / Страна -->
            <div class="profile-local">
                <label class="label-secondary" for="local"><?php _e( "Город", "user-profile" ); ?></label>
                <input id="local" type="text" name="local" value="<?php echo esc_attr(get_the_author_meta('local',$user_ID)); ?>" >
                <label class="label-secondary" for="country_name"><?php _e( "Страна", "user-profile" ); ?></label>
                <input id="country_name" type="text" name="country_name" value="<?php echo esc_attr(get_the_author_meta('country_name',$user_ID)); ?>" >
                </div>
        </div>  <!-- Местоположение -->

        <!-- Социальные кнопки -->
        <div class="profile-social">
            <div class="profile-fb">
                <label class="label-head" for="facebook"><?php _e( "Facebook", "user-profile" ); ?></label>
                <input id="facebook" type="text" name="facebook" value="<?php echo esc_attr(get_the_author_meta('facebook',$user_ID)); ?>" >
            </div>

            <div class="profile-insta">
                <label class="label-head" for="instagram"><?php _e( "Instagram", "user-profile" ); ?></label>
                <input id="instagram" type="text" name="instagram" value="<?php echo esc_attr(get_the_author_meta('instagram',$user_ID)); ?>" >
            </div>

            <div class="profile-twitt">
                <label class="label-head" for="twitter"><?php _e( "Twitter", "user-profile" ); ?></label>
                <input id="twitter" type="text" name="twitter" value="<?php echo esc_attr(get_the_author_meta('twitter',$user_ID)); ?>" >
            </div>
        </div>

		<button class="button button-primary"><?php _e( "Сохранить", "user-profile" ); ?></button>
	</form>
</div>