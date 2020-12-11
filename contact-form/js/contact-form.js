jQuery(document).ready(function($){
    $('#contactForm').on('submit', function(e) {
        e.preventDefault();

        $( '.has-error' ).removeClass('has-error');
        $( '.submit-info' ).removeClass('js-show-feedback');

        var form = $(this);

        var name = form.find('#name').val(),
            email = form.find('#email').val(),
            message = form.find('#message').val(),
            ajaxUrl = form.data('url');

        $('#name').on('focus', function() {
            $( '.has-error' ).removeClass('has-error');
            $( '.js-show-feedback' ).removeClass('js-show-feedback');
        });

        $('#email').on('focus', function() {
            $( '.has-error' ).removeClass('has-error');
            $( '.js-show-feedback' ).removeClass('js-show-feedback');
        });

        $('#message').on('focus', function() {
            $( '.has-error' ).removeClass('has-error');
            $( '.js-show-feedback' ).removeClass('js-show-feedback');
        });

        if( name === '' ) {
            $( '#name' ).parent( '.contact-form-group' ).addClass('has-error');
            return;
        }

        if( email === '' ) {
            $( '#email' ).parent( '.contact-form-group' ).addClass('has-error');
            return;
        }

        if( message === '' ) {
            $( '#message' ).parent( '.contact-form-group' ).addClass('has-error');
            return;
        }

        form.find('input, textarea, button').attr('disabled', 'disabled');
        $('.submit-info').addClass('js-show-feedback');

        $.ajax({
            url: ajaxUrl,
            type: 'post',
            data: {
                name: name,
                email: email,
                message: message,
                action: 'save_user_contact_form'
            },
            error: function(response) {
                $( '.submit-info' ).removeClass('js-show-feedback');
                $( '.submit-error' ).addClass('js-show-feedback');
                form.find('input, textarea, button').removeAttr('disabled');
            },
            success: function(response) {
                if( response == 0 ) {
                    setTimeout(function() {
                        $( '.submit-info' ).removeClass('js-show-feedback');
                        $( '.submit-error' ).addClass('js-show-feedback');
                        form.find('input, textarea, button').removeAttr('disabled');
                    }, 700);

                } else {
                    setTimeout(function() {
                        $( '.submit-info' ).removeClass('js-show-feedback');
                        $( '.submit-success' ).addClass('js-show-feedback');
                        form.find('input, textarea, button').removeAttr('disabled').val('');
                    }, 700);
                }
            },
            clearForm: true
        });

    });
});
