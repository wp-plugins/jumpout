jQuery(document).ready(function() {


    jQuery('.sync').click(function(event) {
        event.preventDefault();

        jQuery('.sync span').html('Подождите, идет синхронизация...');
        jQuery('.sync svg').css('display', 'inline');

        jQuery.getJSON('?page=jumpout&action=sync&type=json', function( data ) {

            if ('undefined' != typeof(data.status)) {
                if ('success' == data.status) {
                    location.reload();

                    jQuery('.sync').css('background', '#199701');
                    jQuery('.sync').css('color', '#ffffff');
                    jQuery('.sync svg').css('display', 'none');
                    jQuery('.sync span').html('Готово! Перезагрузка страницы...');
                   
                } else if ('error' == data.status) {

                    syncError();
                    if ('session token not found' == data.message) {
                        window.location = '?page=jumpout&action=session_token_error';
                    } else if ('not enough params' == data.message) {
                        alert('Похоже при синхронизации плагин отправил неверный запрос. Попробуйте обновить плагин или напишите в техподдержку.');
                    }
                }
            } else {
                syncError();
            }
            
            console.log(data);


            //jQuery('.sync span').html('Синхронизировать');
            //jQuery('.sync svg').css('display', 'none');

        });

        return false;
    });
});


function syncError() {
    jQuery('.sync').css('background', '#AA0808');
    jQuery('.sync').css('color', '#ffffff');
    jQuery('.sync span').html('Ошибка! Попробуйте еще раз или обратитесь в техподдержку!');
}