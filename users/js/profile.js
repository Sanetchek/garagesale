jQuery(document).ready(function($) {

    profile_info_callback();
    acount_ajax_handler();

    /*
     =====================================================
               Ajax Sidebar page switcher
     =====================================================
     */
    $(document).on('click', '.pr-info', function(e){  // click on button with class = ispy-popup
        e.preventDefault();
        ajax_load_profile_page( $(this) );
    });

    $(document).on('click', '.pr-password', function(e){  // click on button with class = ispy-popup
        e.preventDefault();
        ajax_load_profile_page( $(this) );
    });

    $(document).on('click', '.pr-message', function(e){  // click on button with class = ispy-popup
        e.preventDefault();
        ajax_load_profile_page( $(this) );
    });
    
    function ajax_load_profile_page( pageClass ) {
        var mainContent = $( '.profile-wrap' ),
            switchContent = $( '.profile-content' ),
            preloader = $( '.profile-wrap > .loader' ),
            ajaxurl = ajax_var.url,
            page = pageClass.data('page');

        profile_sidebar_menu_activation( pageClass );
        
        preloader.show();
        
        $.ajax({
            url : ajaxurl, // var ajaxurl
            type : 'post', // method = get/post
            data : {
                page : page, // var page
                action : "profile_info_callback" //callback function
            },
            error : function( response ) {
                console.log( 'error - ' + response ); // if error consol.log error
            },
            success : function( response ){
                //Remove current page
                switchContent.remove();
                preloader.hide();
                //Add new page
                mainContent.append( response ); // if success append everything what contain ajax.php to post-ID

                if( page === 1 ) {
                    profile_info_callback();
                }
            }
        });
    }

    function profile_sidebar_menu_activation( pageClass ) {
        $( '.profile-menu' ).find( '.active' ).removeClass( 'active' );
        pageClass.parent().addClass( 'active' );
    }

    /*
     =====================================================
               Ajax login/register page switcher
     =====================================================
     */
    $(document).on('click', '.login-fields', function(e){  // click on button with class = ispy-popup
        e.preventDefault();
        ajax_login_register_page( $(this) );
    });

    $(document).on('click', '.register-fields', function(e){  // click on button with class = ispy-popup
        e.preventDefault();
        ajax_login_register_page( $(this) );
    });

    function ajax_login_register_page( pageClass ) {
        var mainContent = $( '.account-content' ),
            switchContent = $( '.account-fields' ),
            preloader = $( '.account-content > .loader' ),
            ajaxurl = ajax_var.url,
            page = pageClass.data('page');

        preloader.show();

        account_sidebar_menu_activation( pageClass );

        $.ajax({
            url : ajaxurl, // var ajaxurl
            type : 'post', // method = get/post
            data : {
                page : page, // var page
                action : "account_info_callback" //callback function
            },
            error : function( response ) {
                console.log( 'error - ' + response ); // if error consol.log error
            },
            success : function( response ){
                //Remove current page
                switchContent.remove();
                preloader.hide();
                //Add new page
                mainContent.append( response ); // if success append everything what contain ajax.php to post-ID

                acount_ajax_handler();
            }
        });
    }

    function account_sidebar_menu_activation( pageClass ) {
        $( '.account-wrapper' ).find( '.active' ).removeClass( 'active' );
        pageClass.addClass( 'active' );
    }

    /*
      ===================================================================
                Global function
      ===================================================================
      */
    function profile_info_callback() {
        /*
       ===================================================================
                 Profile Image Uploader
       ===================================================================
       */
        var mediaUploader;

        $( '#upload-button' ).on('click', function(e) {
            e.preventDefault();

            if( mediaUploader ) {
                mediaUploader.open();
                return;
            }

            mediaUploader = wp.media.frames.file_frame = wp.media({
                library: {
                    type: 'image' // limits the frame to show only images
                },
                mimes: 'jpeg',
                multiple: false,
            });

            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();

                $( '#avatar' ).val(attachment.sizes.thumbnail.url); //attach value with size (thumbnail, medium, full, etc.)
                $( '#profile-picture-preview' ).attr('src', attachment.sizes.thumbnail.url);
            });

            mediaUploader.open();
        });

        $( '#delete-button' ).on('click', function(e) {
            e.preventDefault();

            $( '#avatar' ).val("");
            $( '#profile-picture-preview' ).attr('src', "");
        });


        /*
        ===================================================================
                  Profile change image selecting on gender
        ===================================================================
        */
        var image = garage.templateUrl + '/users/images/';
        var genderMan = $('#gender-man'),
            genderWoman = $('#gender-woman'),
            genderCompany = $('#gender-company'),
            profileFio = $('.profile-fio'),
            profileOrganization = $('.profile-organization');
        
        genderMan.click(function() {
            if( $( this ).is(':checked')) {
                $( '.profile-picture' ).attr('style', 'background-image: url(' + image + 'male.svg)');
                profileOrganization.hide();
                profileFio.show();
            }
        });
        genderWoman.click(function() {
            if( $( this ).is(':checked')) {
                $( '.profile-picture' ).attr('style', 'background-image: url(' + image + 'female.svg)');
                profileOrganization.hide();
                profileFio.show();
            }
        });
        genderCompany.click(function() {
            if( $( this ).is(':checked')) {
                $( '.profile-picture' ).attr('style', 'background-image: url(' + image + 'company.svg)');
                profileOrganization.show();
                profileFio.hide();
            }
        });
          
        /*
       ===================================================================
                 Profile change image selecting on gender
       ===================================================================
       */

        if( genderCompany.is(':checked') ) {
            profileOrganization.show();
        } else {
            profileFio.show();
        }
    }


    /*
     =====================================================
               Ajax handler
     =====================================================
     */
    function acount_ajax_handler() {
        var ajaxgo = false; // глобальная переменная, чтобы проверять обрабатывается ли в данный момент другой запрос
        // после загрузки DOM
        var userform = $('.userform'); // пишем в переменную все формы с классом userform
        function req_go(data, form, options) { // ф-я срабатывающая перед отправкой
            if (ajaxgo) { // если какой либо запрос уже был отправлен
                form.find('.response').addClass('response-block');
                form.find('.response').html('<p class="error"><?php _e( "Waite...") ?></p>'); // в див для ответов напишем ошибку
                return false; // и ничего не будет делать
            }
            form.find('input[type="submit"]').attr('disabled', 'disabled'); // выключаем кнопку и пишем чтоб подождали
            form.find('.response').html(''); // опусташаем див с ответом
            ajaxgo = true; // записываем в переменную что аякс запрос ушел
        }
        function req_come(data, statusText, xhr, form)  { // ф-я срабатывающая после того как пришел ответ от сервера, внутри data будет json объект с ответом
            console.log(arguments); // это для дебага
            var response = '';
            if (data.success) { // если все хорошо и ошибок нет
                response = '<p class="success">' + data.data.message + '</p>'; // пишем ответ в <p> с классом success
            } else {  // если есть ошибка
                response = '<p class="error">' + data.data.message + '</p>'; // пишем ответ в <p> с классом error
                form.find('.response').addClass('response-block');
                form.find('.response').html(response); // выводим ответ
            }
            
            if (data.data.redirect) window.location.href = data.data.redirect; // если передан redirect, делаем перенаправление
            ajaxgo = false; // аякс запрос выполнен можно выполнять следующий
        }

        var args = { // аргументы чтобы прикрепить аякс отправку к форме
            dataType:  'json', // ответ будем ждать в json формате
            beforeSubmit: req_go, // ф-я которая сработает перед отправкой
            success: req_come, // ф-я которая сработает после того как придет ответ от сервера
            error: function(data) { // для дебага
                console.log(arguments);
            },
            url: ajax_var.url  // куда отправляем, задается в wp_localize_script
        };
        userform.ajaxForm(args); // крепим аякс к формам

        $('.logout').click(function(e){ // ловим клик по ссылке "выйти"
            e.preventDefault(); // выключаем стандартное поведение
            if (ajaxgo) return false; // если в данный момент обрабатывается другой запрос то ничего не делаем
            var lnk = $(this); // запишем ссылку в переменную
            $.ajax({ // инициализируем аякс
                type: 'POST', // шлем постом
                url: ajax_var.url, // куда шлем
                dataType: 'json', // ответ ждем в json
                data: 'action=logout_me&nonce=' + $(this).data('nonce'), // что отправляем
                beforeSend: function(data) { // перед отправкой
                    ajaxgo = true; // аякс отпраляется
                },
                success: function(data){ // после того как ответ пришел
                    if (data.success) { // если ошибок нет
                        window.location.reload(true); // и обновляемся
                    } else { // если ошибки есть
                        alert(data.data.message); // просто покажим их алертом
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) { // для дебага
                    console.log(arguments);
                },
                complete: function(data) { // при любом исходе
                    ajaxgo = false; // аякс больше не выполняется
                }
            });
        });
    }
    
    
    /*
    ===================================================================
             Facebook login button
    ===================================================================
    */
    // window.fbAsyncInit = function() {
    //     FB.init({
    //         appId      : '396569764123907',
    //         cookie     : true,
    //         xfbml      : true,
    //         version    : 'v2.12'
    //     });
    //
    //     FB.AppEvents.logPageView();
    //
    // };
    //
    // (function(d, s, id){
    //     var js, fjs = d.getElementsByTagName(s)[0];
    //     if (d.getElementById(id)) {return;}
    //     js = d.createElement(s); js.id = id;
    //     js.src = "https://connect.facebook.net/en_US/sdk.js";
    //     fjs.parentNode.insertBefore(js, fjs);
    // }(document, 'script', 'facebook-jssdk'));
    //
    // FB.getLoginStatus(function(response) {
    //     statusChangeCallback(response);
    // });
    //
    // function checkLoginState() {
    //     FB.getLoginStatus(function(response) {
    //         statusChangeCallback(response);
    //     });
    // }
});