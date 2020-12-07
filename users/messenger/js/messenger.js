jQuery(document).ready(function($) {
    /*
     =====================================================
               Ajax Chat Messenger
     =====================================================
    */

    $(document).on('click', '#chat-send', function(e){  // click on button with class = ispy-popup
        e.preventDefault();

        var chatTextArea = $( '#chat-area' ),
            chatMessageBox = $( '#chat-message' ).val(),
            chatSubmitButton = $( this ),
            ajaxurl = ajax_var.url;

        if ( !chatMessageBox ) {
            return;
        }

        chatTextArea.append( chatMessageBox + ';' );

        var text = chatTextArea.val();
        var result = text.split( ";" );
        var strResult = JSON.stringify( result );
        $("#chat-list").html( strResult );
        
    });
});