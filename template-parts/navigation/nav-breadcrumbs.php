<header class="main-head">
	<div class="main-head-wrap">
		<?php if (function_exists('garage_breadcrumbs')) garage_breadcrumbs(); ?>
		<?php

		  if ( is_home() ) {
			  $blog_page_title = get_the_title( get_option('page_for_posts', true) );
			  echo '<h1>' . $blog_page_title . '</h1>';
		  } elseif ( is_page_template( 'users/profile.php' ) ) {
			  global $user_ID;
			  $userdata = get_user_by( 'id', $user_ID );
			  $gender = get_the_author_meta('gender', $user_ID );

			  switch ($gender) {
                  case 'company':
                      echo '<h1>' . esc_attr(get_the_author_meta('organization', $user_ID)) . '</h1>';
                      break;
                  case 'male':
                  case 'female':
                      if( !$userdata->first_name ) {
	                      echo '<h1>' . $userdata->nickname . '</h1>';
                      } else {
	                      echo '<h1>' . $userdata->first_name . ' ' . $userdata->last_name . '</h1>';
                      }
                      break;
              }
		  } elseif( is_archive() ) {
		      echo 'archive template -> nav -> breadcrumbs';
			  post_type_archive_title( '<h1>', '</h1>' );
          } else {
		      the_title( '<h1>', '</h1>' );
		  }
		  
		?>
	</div>
</header>