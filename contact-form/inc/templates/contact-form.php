<form id="contactForm" class="contact-form-wrap" action="#" method="post" data-url="<?php echo admin_url('admin-ajax.php'); ?>">
    <img loading="lazy" class="paper-plane" src="<?php echo get_template_directory_uri(). '/contact-form/images/paper-plane.png' ?>" alt="<?php _e( 'Paper Plane', 'theme_language' ); ?>">
   <div class="contact-form-group">
        <input type="text" id="name" class="content-form-control" name="name" placeholder="<?php _e('Имя', 'theme_language') ?>"  >
       <small class="text-danger control-msg"><?php _e( 'Поле должно быть заполнено', 'theme_language' ); ?></small>
    </div>
    <div class="contact-form-group">
        <input type="email" id="email" class="content-form-control" name="email" placeholder="<?php _e('Email', 'theme_language') ?>"  >
        <small class="text-danger control-msg"><?php _e( 'Поле должно быть заполнено', 'theme_language' ); ?></small>
    </div>
    <div class="contact-form-group">
        <textarea id="message" class="content-form-control" name="message" placeholder="<?php _e('Сообщение', 'theme_language') ?>"  ></textarea>
        <small class="text-danger control-msg"><?php _e( 'Поле должно быть заполнено', 'theme_language' ); ?></small>
    </div>

   <div class="text-center">
        <button type="submit" class="contact-form-btn btn-secondary"><?php _e('Отправить', 'theme_language') ?></button>
        <small class="submit-info control-msg"><?php _e( 'Отправка сообщения . . .', 'theme_language' ); ?></small>
        <small class="submit-success control-msg"><?php _e( 'Сообщение отправлено.', 'theme_language' ); ?></small>
        <small class="submit-error control-msg"><?php _e( 'Сообщение не удалось отправить, попробуйте еще раз!', 'theme_language' ); ?></small>
    </div>
</form>
