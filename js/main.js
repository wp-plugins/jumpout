jQuery(document).ready(function() {


    jQuery('.sync').click(function(event) {
        event.preventDefault();

        jQuery('.sync span').html(jumpout_text.sync_in_progress + '...');
        jQuery('.sync svg').css('display', 'inline');



        jQuery.getJSON('?page=jumpout&action=sync&type=json')
            .done(function( data ) {

                if ('undefined' != typeof(data.status)) {
                    if ('success' == data.status) {
                        location.reload();

                        jQuery('.sync').css('background', '#199701');
                        jQuery('.sync').css('color', '#ffffff');
                        jQuery('.sync svg').css('display', 'none');
                        jQuery('.sync span').html(jumpout_text.sync_finished + '...');
                       
                    } else if ('error' == data.status) {

                        syncError();
                        if ('session token not found' == data.message) {
                            window.location = '?page=jumpout&action=session_token_error';
                        } else if ('not enough params' == data.message) {
                            alert(jumpout_text.sync_wrong_request);
                        }
                    }
                } else {
                    syncError();
                }
                
                //console.log(data);
                //jQuery('.sync span').html('Синхронизировать');
                //jQuery('.sync svg').css('display', 'none');
            })
            // 3.0.2 - just refreshing page if failed, maybe it was just an error when wp returned html instead of json, but sync completed
            .fail(function( jqxhr, textStatus, error ) {
                var err = textStatus + ", " + error;
                console.log( "Request Failed: " + err );
                location.reload();
        });




        jQuery.getJSON('?page=jumpout&action=sync&type=json', function( data ) {


        });

        return false;
    });
});


function syncError() {
    jQuery('.sync').css('background', '#AA0808');
    jQuery('.sync').css('color', '#ffffff');
    jQuery('.sync span').html(jumpout_text.sync_error);
}