<?php
/* Define these, So that WP functions work inside this file */
define('WP_USE_THEMES', false);
require_once( dirname(__FILE__) . "/../../../../../wp-load.php" );
?>
<?php
if(isset($_POST['send']) == '1') {
	$post_author = $_POST['senderID'];
	$recipient = $_POST['recipientID'];
	$sender = get_user_by('id', $post_author);

	$senderName = get_user_by( 'id', $post_author );
	$recipientName = get_user_by( 'id', $recipient );

	$post_title = 'msg-' . $senderName->nickname . '-to-' . $recipientName->nickname;

	$new_post = array(
		'ID' => '',
		'post_type'         => 'messenger',
		'post_author'       => $post_author,
		'meta_input' => array(
			'recipient'     => $recipient,
			'sender'        => $post_author
		),
		'post_title'        => $post_title,
		'post_status'       => 'publish'
	);

	$post_id = wp_insert_post($new_post);

	// This will redirect you to the newly created post
	$post = get_post($post_id);
	wp_redirect($post->guid);
}
?>