  $(document).ready(function() {
    $('a.moveCopyLink').click(function()
      {
        loadDialog("movecopy","/browse/movecopy/?move=false&items="+json.item.item_id);
        showDialog(json.item.message.movecopy);
      });
   
     $('a#itemDeleteLink').click(function()
    {
      var html='';
      html+=json.item.message['deleteMessage'];
      html+='<br/>';
      html+='<br/>';
      html+='<br/>';
      html+='<input style="margin-left:140px;" class="globalButton deleteItemYes" element="'+$(this).attr('element')+'" type="button" value="'+json.global.Yes+'"/>';
      html+='<input style="margin-left:50px;" class="globalButton deleteItemNo" type="button" value="'+json.global.No+'"/>';
      
      showDialogWithContent(json.item.message['delete'],html,false);
      
      $('input.deleteItemYes').unbind('click').click(function()
        { 
          location.replace(json.global.webroot+'/item/delete?itemId='+json.item.item_id);
        });
      $('input.deleteItemNo').unbind('click').click(function()
        {
           $( "div.MainDialog" ).dialog('close');
        });         
      
    });
  });
  