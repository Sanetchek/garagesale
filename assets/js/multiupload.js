jQuery(function(jQuery) {
    jQuery('.custom_upload_file_button').click(function() {
        formfield = jQuery(this).siblings('.custom_upload_image');
        preview = jQuery(this).siblings().parent("div").siblings();
        console.log(preview);
        tb_show('', 'media-upload.php?type=file&TB_iframe=true');
        window.send_to_editor = function(html) {
            fileUrl = jQuery(html).attr('src');
            fileName = jQuery(html).text();
            formfield.val(fileUrl);
            preview.attr('src', fileUrl);
            tb_remove();
        };
        return false;
    });

    jQuery('.repeatable-add').click(function() {
        field = jQuery(this).closest('td').find('.custom_repeatable li:last').clone(true);
        fieldLocation = jQuery(this).closest('td').find('.custom_repeatable li:last');
        jQuery('img', field).attr('src', "http://placehold.it/100x100");
        console.log(field);
        jQuery('input', field).val('').attr('name', function(index, name) {
            return name.replace(/(\d+)/, function(fullMatch, n) {
                return Number(n) + 1;
            });
        });
        field.insertAfter(fieldLocation, jQuery(this).closest('td'))
        return false;
    });

    jQuery('.repeatable-remove').click(function() {
        jQuery(this).parent().parent().remove();
        return false;
    });

    jQuery('.custom_repeatable').sortable({
        cursor: 'move',
        handle: '.sort'
    });
    jQuery('.custom_repeatable').disableSelection();

});


jQuery(document).ready(function($) {
    var custom_uploader;
    $('#upload_image_button').click(function(e) {
        e.preventDefault();
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: true
        });
        custom_uploader.on('select', function() {
            var selection = custom_uploader.state().get('selection');
            selection.map(function(attachment) {
                attachment = attachment.toJSON();
                $("#obal").after("<img src=" + attachment.url + ">");
            });
        });
        custom_uploader.open();
    });
});