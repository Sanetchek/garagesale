<h3><?php _e( "Дополнительная информация", "user-profile" ); ?></h3>

<?php
global $user_ID;
$userdata = get_user_by( 'id', $user_ID );
?>

<table class="form-table">
    <!-- Установить пол пользователя или компания -->
    <tr>
        <th><label for="gender"><?php _e( "Пользователь", "user-profile" ); ?></label></th>
        <td><?php $gender = get_the_author_meta('gender', $user->ID ); ?>
            <ul>
                <li><label><input id="gender-man" value="male" name="gender"<?php if ($gender == 'male' or !$gender ) { ?> checked="checked"<?php } ?> type="radio" /> <?php _e( "мужчина", "user-profile" ); ?></label></li>
                <li><label><input id="gender-woman" value="female"  name="gender"<?php if ($gender == 'female') { ?> checked="checked"<?php } ?> type="radio" /> <?php _e( "женщина", "user-profile" ); ?></label></li>
                <li><label><input id="gender-company" value="company"  name="gender"<?php if ($gender == 'company') { ?> checked="checked"<?php } ?> type="radio" /> <?php _e( "компания", "user-profile" ); ?></label></li>
            </ul>
        </td>
    </tr>
    <!-- Установить название организации -->
    <tr>
        <th><label for="organization"><?php _e( "Название организации", "user-profile" ); ?></label></th>
        <td>
            <input id="organization" value="<?php echo esc_attr(get_the_author_meta('organization',$user->ID));?>" name="organization" type="text" placeholder="<?php _e( "Введите название организации", "user-profile" ); ?>" />
        </td>
    </tr>
    <!-- Установить телефон -->
    <tr>
        <th><label for="phone1"><?php _e( "Номер телефона", "user-profile" ); ?></label></th>
        <td><?php $gender = get_the_author_meta('gender', $user->ID ); ?>
            <input class="phone" value="<?php echo esc_attr(get_the_author_meta('phone1',$user->ID));?>" name="phone1" type="tel" placeholder="<?php _e( "Введите номер телефона", "user-profile" ); ?>" />
            <input class="phone" value="<?php echo esc_attr(get_the_author_meta('phone2',$user->ID));?>" name="phone2" type="tel" placeholder="<?php _e( "Введите номер телефона", "user-profile" ); ?>" />
            <input class="phone" value="<?php echo esc_attr(get_the_author_meta('phone3',$user->ID));?>" name="phone3" type="tel" placeholder="<?php _e( "Введите номер телефона", "user-profile" ); ?>" />

        </td>
    </tr>
    <!-- Загрузить / Удалить Аватар -->
    <tr>
        <th><?php _e( "Аватар", "user-profile" ); ?></th>
        <td>
            <div class="image-container">
                <div class="profile-picture" style="background-image: url(<?php echo changeGenderImage( $user->ID ) ?>)">
                    <img id="profile-picture-preview" src="<?php echo esc_attr(get_the_author_meta('avatar',$user->ID));?>">
                </div>
            </div>
            <input type="button" value="<?php _e( "Выбрать Картинку", "user-profile" ); ?>" id="upload-button" class="button button-secondary">
            <input type="button" value="<?php _e( "Удалить", "user-profile" ); ?>" id="delete-button" class="button button-cancel">
            <input type="hidden" name="avatar" id="avatar" value="<?php echo esc_attr(get_the_author_meta('avatar',$user->ID));?>" /><br />
        </td>
    </tr>
    <!-- Ввод местоположения с помощью Google Maps Autocomplete -->
    <tr>
        <th><label for="city_search"><?php _e( "Местоположение", "user-profile" ); ?></label></th>
        <td>
            <div id="locationField">
                <input id="autocomplete" class="city-search" name="city_search" placeholder="<?php _e( "Введите адрес", "user-profile" ); ?>" type="text" value="<?php echo esc_attr(get_the_author_meta('city_search',$user->ID));?>" autocomplete="off" >
                <input class="field" id="street_number" type="hidden" disabled="true" value="">
                <input class="field" id="route" type="hidden" disabled="true" value="">
                <input class="field" id="locality" type="hidden" disabled="true" value="">
                <input class="field" id="administrative_area_level_1" type="hidden" disabled="true" value="">
                <input class="field" id="postal_code" type="hidden" disabled="true" value="">
                <input class="field" id="country" type="hidden" disabled="true" value="">
            </div>
        </td>
    </tr>
    <!-- Область / Индекс -->
    <tr>
        <td class="label"><label for="admin_area"><?php _e( "Область", "user-profile" ); ?></label></td>
        <td class="wideField" colspan="3">
            <input id="admin_area" type="text" name="admin_area" value="<?php echo esc_attr(get_the_author_meta('admin_area',$user->ID)); ?>" >
        </td>
        <td class="label"><label for="post_code"><?php _e( "Индекс", "user-profile" ); ?></td>
        <td class="slimField">
            <input id="post_code" type="text" name="post_code" value="<?php echo esc_attr(get_the_author_meta('post_code',$user->ID)); ?>" >
        </td>
    </tr>
    <!-- Адрес / Дом -->
    <tr>
        <td class="label"><label for="city_route"><?php _e( "Адрес", "user-profile" ); ?></label></td>
        <td class="wideField" colspan="3">
            <input id="city_route" type="text" name="city_route" value="<?php echo esc_attr(get_the_author_meta('city_route',$user->ID)); ?>" >
        </td>
        <td class="label"><label for="street_num"><?php _e( "Дом", "user-profile" ); ?></label></td>
        <td class="slimField">
            <input id="street_num" type="text" name="street_num" value="<?php echo esc_attr(get_the_author_meta('street_num',$user->ID)); ?>" >
        </td>
    </tr>
    <!-- Город / Страна -->
    <tr>
        <td class="label"><label for="local"><?php _e( "Город", "user-profile" ); ?></label></td>
        <td class="wideField" colspan="2">
            <input id="local" type="text" name="local" value="<?php echo esc_attr(get_the_author_meta('local',$user->ID)); ?>" >
        </td>
        <td class="label"><label for="country_name"><?php _e( "Страна", "user-profile" ); ?></label></td>
        <td class="wideField" colspan="2">
            <input id="country_name" type="text" name="country_name" value="<?php echo esc_attr(get_the_author_meta('country_name',$user->ID)); ?>" >
        </td>
    </tr>

    <!-- Социальные кнопки -->
    <tr>
        <th class="label"><?php _e( "Facebook", "user-profile" ); ?></th>
        <td>
            <div id="fb-root"></div>
            <div class="fb-login-button" data-max-rows="1" data-size="medium" data-button-type="continue_with" data-show-faces="false" data-auto-logout-link="false" data-use-continue-as="true"></div>
        </td>
    </tr>
    <tr>
        <th class="label"><?php _e( "Instagram", "user-profile" ); ?></th>
        <td>
            
        </td>
    </tr>
    <tr>
        <th class="label"><?php _e( "Twitter", "user-profile" ); ?></th>
        <td>

        </td>
    </tr>

</table>