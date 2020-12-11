<?php

add_action( 'wp_ajax_nopriv_save_user_contact_form', 'save_contact_form' );
add_action( 'wp_ajax_save_user_contact_form', 'save_contact_form' );

function save_contact_form() {
    // wp_strip_all_tags() убирает все html php тэги отправленные пользователем
    $name = wp_strip_all_tags($_POST["name"]);
    $email = wp_strip_all_tags($_POST["email"]);
    $message = wp_strip_all_tags($_POST["message"]);

    $args = array(
        'post_title' => $name,
        'post_content' => $message,
        'post_author' => 1,
        'post_status' => 'publish',
        'post_type' => 'contact_form_type',
        'meta_input' => array(
            '_contact_email_value_key' => $email
        )
    );

    $postID = wp_insert_post( $args );

    if($postID !== 0) {

        $contactEmailTo = get_option( 'contact_email_to' );
        if( $contactEmailTo ) {
            $to = $contactEmailTo;
        } else {
            $to = get_bloginfo('admin_email');

        }

        $siteName = get_bloginfo('name');
        $subject = $name . ' send message from - ' . $siteName ;
        echo $to;
        $headers[] = 'From: ' . $siteName . ' <' . $to . '>';
        $headers[] = 'Reply-to: ' . $name . ' <' . $email . '>';
        $headers[] = 'Content-Type: text/html: charset=UTF-8';

        wp_mail($to, $subject, $message, $headers);

        echo $postID;
    } else {
        echo 0;
    }

    die();
}
