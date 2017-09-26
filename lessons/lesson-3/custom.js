jQuery(document).ready(function () {
    var data = {
        action: 'hello',
        name: myPlugin.name
    };

    jQuery.get(myPlugin.ajaxurl, data, function (response) {
        alert('Получено с сервера: ' + response);
    });
});
