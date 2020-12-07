<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <input type="search"
           class="search-field"
           placeholder="<?php echo esc_attr_x( 'Что найти &hellip; ?', 'placeholder', 'garage' ); ?>"
           value="<?php echo get_search_query(); ?>"
           name="s" />
    <input id="autocomplete" class="city-search" type="text"
           placeholder="<?php echo esc_attr_x( 'Где найти &hellip; ?', 'placeholder', 'garage' ); ?>"/>
    <button type="submit" class="search-submit"><span class="search-text"><?php echo _x( 'Найти', 'submit button', 'garage' ); ?></span></button>
</form>