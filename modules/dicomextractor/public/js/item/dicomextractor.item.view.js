/*global $*/
/*global document*/
/*global ajaxWebApi*/
/*global json*/
/*global window*/
var midas = midas || {};
midas.dicomextractor = midas.dicomextractor || {};

midas.dicomextractor.extractAction = function () {
    'use strict';
    ajaxWebApi.ajax({
        method: 'midas.dicomextractor.extract',
        args: 'item=' + json.item.item_id,
        success: function (retVal) {
            midas.createNotice('Metadata extraced successfully', 3000);
            window.location.reload();
        },
        error: function (retVal) {
            midas.createNotice(retVal.msg, 3000, 'error');
        },
        complete: function () {
        }
    });
};

$(document).ready(function () {
    'use strict';
    $('#dicomExtractAction').click(midas.dicomextractor.extractAction);
});