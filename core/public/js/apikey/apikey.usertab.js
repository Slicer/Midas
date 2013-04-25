var midas = midas || {};
midas.apikey = midas.apikey || {};

midas.apikey.validateApiConfig = function (formData, jqForm, options) {
}

midas.apikey.successApiConfig = function (responseText, statusText, xhr, form) {
  try {
      var jsonResponse = jQuery.parseJSON(responseText);
  } catch (e) {
      midas.createNotice("An error occured. Please check the logs.", 4000, 'error');
      return false;
  }
  if(jsonResponse == null) {
      midas.createNotice('Error', 4000, 'error');
      return;
  }
  if(jsonResponse[0]) {
      midas.createNotice(jsonResponse[1], 4000);
      $('#tabsSettings').tabs('load', $('#tabsSettings').tabs('option', 'selected')); //reload tab
  }
  else {
      midas.createNotice(jsonResponse[1], 4000, 'error');
  }
}

$(document).ready(function() {
    $('#generateKeyForm').ajaxForm({
        beforeSubmit: midas.apikey.validateApiConfig,
        success: midas.apikey.successApiConfig} );

    $('a.deleteApiKeyLink').click(function () {
        var obj = $(this);
        $.post(json.global.webroot+'/apikey/usertab', {
            deleteAPIKey: true,
            element: $(this).attr('element'),
            userId: $('#apiUserId').val()
        },
            function(data) {
                var jsonResponse = jQuery.parseJSON(data);
                if(jsonResponse == null) {
                    midas.createNotice('Error', 4000, 'error');
                    return;
                }
                if(jsonResponse[0]) {
                    midas.createNotice(jsonResponse[1], 2000);
                    obj.parents('tr').remove();
                }
                else {
                    midas.createNotice(jsonResponse[1], 4000, 'error');
                }
            }
        );
   });
});
