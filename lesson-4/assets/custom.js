// Ajax запрос
var jqXHR = jQuery.post(
    myPlugin.ajaxurl,
    {
        action: 'show_anything',
        nonce: myPlugin.nonce
    }
);

// Обработка успешного запроса
jqXHR.done(function (responce) {
    sweetAlert('Успех', responce, 'success');
});

// Обработка запроса с ошибкой
jqXHR.fail(function (responce) {
    sweetAlert('Ошибка', responce.responseText, 'error');
});
