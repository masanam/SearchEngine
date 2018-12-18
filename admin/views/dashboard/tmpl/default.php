<?php 
defined('_JEXEC') or die();


?>
<form
                    action="<?php echo JRoute::_('index.php?option=com_searchengine'); ?>"
                    method="post" name="adminForm" id="adminForm" class="validate" enctype='multipart/form-data'>
<div id="cj-wrapper">
  <div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
    <div class="row-fluid">   
        
       
            <div id="splitter">
            <div>
                <div id="nested2">
                    <div>                    
                    <iframe  src="index.php?option=com_searchengine&view=search&package_id=<?php echo $this->package_id;?>&tmpl=component" width="100%" id="column1" scrolling="no"></iframe></div>
                    <div><div class="loading_box_center"> Searching<span class="serching"></span> </div><iframe src="" width="100%" id="column2" scrolling="yes" width="100%"></iframe></div>
                </div>            
            </div>

            <div>
                <div id="nested1">
                    <div><iframe src="" width="100%" id="column3" scrolling="yes" width="100%"></iframe></div>
                   
                </div>
            </div>
            </div>
       


    </div>
  </div>
</div>      
 <input type="hidden" name="task" value="" id="task" />
 <input type="hidden" value="<?php echo $this->package_id; ?>" name="package_id">
 <input type="hidden" name="boxchecked" value="0">
 <?php echo JHtml::_('form.token'); ?> 
</form>
<script type="text/javascript">


$(document).ready(function () {

    var document_width = $( document ).width();
    var document_height = $('body').innerHeight();
    $('#column1,#column2,#column3').css('height',document_height-20);

    var left_column = (document_width/100) * 24;
    var rest_column =  document_width - left_column;
    var midel_column = rest_column/2;
    var right_column = midel_column;

    $('#splitter').jqxSplitter({
        width: document_width-50,
        height: document_height-10,
        orientation: 'vertical',
        panels: [
           { size: "65%", min: "10%", collapsible: false },
           { size: '35%', min: "5%"}]
    });

    $('#nested2').jqxSplitter({ width: "100%", height: "100%", panels: [
        { size: left_column}]
    });
});

$(function($) {
    

    var col1=$('#column1');
    var col2= $('#column2');
    var col3= $('#column3');

    col1.load(function(){
      col1.contents().find('.search_btn').click(function(){
        //jQuery('.loading_box_center').show();
	
		var activetab="ip";
		
		if ( $( col1.contents().find('#ip') ).hasClass( "active" ) ) {   
			activetab="ip";
		}
		else if ( $( col1.contents().find('#keyword') ).hasClass( "active" ) ) {   
			activetab="keyword";
		}
		else if ( $( col1.contents().find('#url') ).hasClass( "active" ) ) {   
			activetab="url";
		}
		
        var target= col1.contents().find('#target_column1'); 
        var frm = col1.contents().find('#usergroupForm'); 
        target_url = 'index.php?option=com_searchengine&searchview='+activetab+'&task=search.summary&tmpl=component&package_id=<?php echo $this->package_id;?>'; 
        col2.attr("src",target_url );

        target= col1.contents().find('#target_column2');       
        target_url = 'index.php?option=com_searchengine&searchview='+activetab+'&task=search.list_list&tmpl=component&package_id=<?php echo $this->package_id;?>'; 

        col3.attr("src",target_url );
      });
    });
	
});
function dotdotdot(cursor, times, string) {
  return Array(times - Math.abs(cursor % (times * 2) - times) + 1).join(string);
}

var cursor = 0;
setInterval(function () {
  jQuery('.serching').text(dotdotdot(cursor++, 5, '.'));
}, 100);




</script>
<style type="text/css">
/*.zui-splitter-separator{z-index: 10;}*/
iframe{border: none;}
.loading_box_center{
    position: absolute;
    font-weight: bold;
    padding: 5px;
    text-align: lfet;
    display: none;
    background-color: #B6B6B6;
    font-size: 18px;
    width: 200px;
    color: white;

}
</style>