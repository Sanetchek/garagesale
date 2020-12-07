<?php

/*
===================================================================
          Register Scripts and Css
===================================================================
*/

function messenger_profile_scripts()
{
	global $pagenow;
	// Scripts
	wp_enqueue_script('jquery');

	wp_localize_script( 'jquery', 'ajax_var', // добавим объект с глобальными JS переменными
		array(
			'url' => admin_url('admin-ajax.php'), // и сунем в него путь до AJAX обработчика
		)
	);

	$post_type = get_post_type();
	$profile = 'users/profile.php';

	if ( 'messenger' != $pagenow &&
	     'messenger' != $post_type &&
	     is_page_template() != $profile
	)
	{ return; }

	// Styles
	wp_enqueue_style('messenger-style', get_template_directory_uri() . '/users/messenger/css/messenger.min.css');

	// Scripts
	wp_enqueue_script('messenger-script', get_template_directory_uri() . '/users/messenger/js/messenger.min.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'messenger_profile_scripts');
add_action('wp_enqueue_scripts', 'messenger_profile_scripts');

/*
   ===================================================================
               Register post type
   ===================================================================
*/
$args = array(
	'labels'	=>	array(
		'all_items'         => 	__( 'Messenger', 'user-profile' ),
		'menu_name'	        =>	__( 'Messenger', 'user-profile' ),
		'singular_name'     =>	__( 'Message', 'user-profile' )
	),
	'supports'              =>	array( 'title', 'comments', 'author' ),
	'show_in_menu'          =>	'users.php',
	'public'		        =>	true,
	'publicly_queryable'    =>  true
);
register_post_type( 'messenger', $args );

flush_rewrite_rules();

/*
   ===================================================================
               Robots noidex
   ===================================================================
*/
function robots_noindex_nofolow() {
	if ( is_singular( 'messenger') ) {
		echo "<meta name='robots' content='noindex,nofollow'>";
	}
}
add_action( 'wp_head', 'robots_noindex_nofolow' );

/*
   ===================================================================
               Recipient meta box
   ===================================================================
*/
add_action('add_meta_boxes', 'recipient_name_by_id', 1);

function recipient_name_by_id() {
	add_meta_box( 'recipient_name', __( 'Получатель', 'user-profile' ), 'extra_fields_box_func', 'messenger', 'normal', 'high'  );
}

function extra_fields_box_func( $post ){
	$sender = get_post_meta($post->ID, 'sender', 1);
	$recipient = get_post_meta($post->ID, 'recipient', 1);
	$textArea = get_post_meta($post->ID, 'chat-area', 1);

	function messenger_text_area_array( $postTypes ) {
		$custom_post_types = explode(";", $postTypes);

		return $custom_post_types;
	}

	?>

    <div id="chat-list"></div>

    <div class="chat-fields">
        <ol>
        <?php
        $messages = messenger_text_area_array($textArea);

            foreach ( $messages as $message ) {
                echo "<li>" . $message . "</li>";
            }

        ?>
        </ol>
        <div>
            <label><input name="extra[chat-message]" id="chat-message" /></label>
            <input type="button" id="chat-send" class="button button-secondary" value="<?php _e( "Отправить", 'user-profile' ) ?>" >
        </div>
    </div>

    <table class="messenger-profile-table">
        <tr>
            <td colspan="2" class="messenger-user-id">
                <p><label for="recipient"><?php _e( 'Получатель', 'user-profile' ); ?></label><input id="recipient" name="extra[recipient]" value="<?php echo $recipient ?>" /></p>
            </td>
            <td colspan="2" class="messenger-user-id">
                <p><label for="sender"><?php _e( 'Отправитель', 'user-profile' ); ?></label><input id="sender" name="extra[sender]" value="<?php echo $sender ?>" /></p>
            </td>
        </tr>
        <tr>
            <th class="messenger-header-photo"><?php _e( 'Фото', 'user-profile' ) ?></th>
            <th class="messenger-header-name"><?php _e( 'Имя', 'user-profile' ) ?></th>
            <th class="messenger-header-photo"><?php _e( 'Фото', 'user-profile' ) ?></th>
            <th class="messenger-header-name"><?php _e( 'Имя', 'user-profile' ) ?></th>
        </tr>
        <tr>
            <td class="messenger-user-image"><img src="<?php echo message_author_photo( $recipient ) ?>" alt=""></td>
            <td class="messenger-user-name"><?php echo message_author_name( $recipient ); ?></td>
            <td class="messenger-user-image"><img src="<?php echo message_author_photo( $sender ) ?>" alt=""></td>
            <td class="messenger-user-name"><?php echo message_author_name( $sender ); ?></td>
        </tr>
    </table>

    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" />
	<?php
}

add_action('save_post', 'recipient_name_field_update', 0);

/* Сохраняем данные, при сохранении поста */
function recipient_name_field_update( $post_id ){
	if ( ! wp_verify_nonce($_POST['extra_fields_nonce'], __FILE__) ) return false; // проверка
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE  ) return false; // выходим если это автосохранение
	if ( !current_user_can('edit_post', $post_id) ) return false; // выходим если юзер не имеет право редактировать запись

	if( !isset($_POST['extra']) ) return false; // выходим если данных нет

	// Все ОК! Теперь, нужно сохранить/удалить данные
	$_POST['extra'] = array_map('trim', $_POST['extra']); // чистим все данные от пробелов по краям
	foreach( $_POST['extra'] as $key=>$value ){
		if( empty($value) ){
			delete_post_meta($post_id, $key); // удаляем поле если значение пустое
			continue;
		}

		update_post_meta($post_id, $key, $value); // add_post_meta() работает автоматически
	}
	return $post_id;
}

function message_author_name( $recipient ) {
	$recipientID = get_user_by( 'id', $recipient );
	$recipientGender = get_the_author_meta( 'gender', $recipient );
	$first_name = get_the_author_meta( 'first_name', $recipient );
	$last_name = get_the_author_meta( 'last_name', $recipient );
	$company = get_the_author_meta( 'organization', $recipient );

	switch ($recipientGender) {
		case 'company':
			if ( $company ) {
				$recipientName = $company;
			} else {
				$recipientName = $recipientID->nickname;
			}
			break;
		case 'male':
        case 'female':
			if ( $first_name || $last_name ) {
				$recipientName = $first_name . ' ' . $last_name;
			} else {
				$recipientName = $recipientID->nickname;
			}
			break;
		default: $recipientName = $recipientID->nickname;
	}

	return $recipientName;
}

function message_author_photo( $recipient ) {
	$recipientPhoto = get_the_author_meta( 'avatar', $recipient );
	$recipientGender = get_the_author_meta( 'gender', $recipient );

	switch ($recipientGender) {
		case 'company':
			if ( !$recipientPhoto ) {
				$recipientPhoto = get_template_directory_uri() . '/users/images/company.svg';
			}
			break;
		case 'male':
			if ( !$recipientPhoto ) {
				$recipientPhoto = get_template_directory_uri() . '/users/images/male.svg';
			}
			break;
		case 'female':
			if ( !$recipientPhoto ) {
				$recipientPhoto = get_template_directory_uri() . '/users/images/female.svg';
			}
			break;
	}

	return $recipientPhoto;
}

/*
   ===================================================================
               Create button for message
   ===================================================================
*/
function create_message() {
	if ( !is_singular( 'messenger') && is_user_logged_in() || is_author() && is_user_logged_in() ) { 
		$current_user  = wp_get_current_user(); // get current logged in user
		$sender        = $current_user->ID;  // get current logged in user id
		$recipient     = get_the_author_meta( 'ID' );  // get post_author or author id

        if ( is_author() ) {
	        global $wp;
	        $pieces = explode("/", $wp->request);
	        $recipient = get_user_by( 'slug', $pieces[1] );
	        $recipient = $recipient->ID;
        }
		$recipientName = get_user_by( 'id', $recipient );  // get post_author or author name

        
        if ( $sender == $recipient ) {
            return;
        }

		$post_name_sender = 'msg-' . $current_user->nickname . '-to-' . $recipientName->nickname; // get message title
		$post_name_recipient = 'msg-' . $recipientName->nickname . '-to-' . $current_user->nickname; // get message title
		$post_sender = get_page_by_title( $post_name_sender, OBJECT, 'messenger' ); // get id of post by message title
		$post_recipient = get_page_by_title( $post_name_recipient, OBJECT, 'messenger' ); // get id of post by message title


        if( !$post_sender && !$post_recipient ) :
    ?>
            <form method="post" action="<?php echo get_template_directory_uri() . '/users/messenger/handler.php' ?>" class="form-horizontal">
                <input name="senderID" type="hidden" value="<?php echo $sender ?>">
                <input name="recipientID" type="hidden" value="<?php echo $recipient ?>">
                <button id="send" name="send" class="button button-secondary button-message"><?php _e( 'Отправить', 'user-profile' ) ?></button>
            </form>
		<?php elseif( !$post_sender ) : ?>
            <a href="<?php echo get_permalink( $post_recipient ) ?>" class="button button-secondary button-message"><?php _e( 'Отправить', 'user-profile' ) ?></a>
        <?php else : ?>
            <a href="<?php echo get_permalink( $post_sender ) ?>" class="button button-secondary button-message"><?php _e( 'Отправить', 'user-profile' ) ?></a>
        <?php endif;
	} else {
	    return;
    }
}