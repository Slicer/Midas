// MIDAS Server. Copyright Kitware SAS. Licensed under the Apache License 2.0.

$(document).ready(function () {
    $('img.killInstance').qtip({
        content: 'Stop and delete this instance'
    }).click(function () {
        var id = $(this).attr('key');
        $.ajax({
            type: 'DELETE',
            url: json.global.webroot + '/pvw/paraview/instance/' + id,
            dataType: 'json',
            data: {},
            success: function (resp) {
                midas.createNotice(resp.message, 3000, resp.status);
                if (resp.status == 'ok') {
                    $('table.instances tr[key=' + id + ']').remove();
                }
            }
        })
    });
});
