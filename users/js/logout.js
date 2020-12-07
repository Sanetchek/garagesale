jQuery(document).ready(function($) {
    var ajaxgo = false;
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

});