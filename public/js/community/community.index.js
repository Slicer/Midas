$('a.createCommunity').click(function()
{
  if(json.global.logged)
    {
    loadDialog("createCommunity","/community/create");
    showDialog(json.community.createCommunity,false);
    }
  else
    {
    createNotive(json.community.contentCreateLogin,4000)
    $("div.TopDynamicBar").show('blind');
    loadAjaxDynamicBar('login','/user/login');
    }

});
