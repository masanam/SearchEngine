<?php 
defined('_JEXEC') or die();
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
?>
<div id="cj-wrapper">
  <div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
    <div class="row-fluid">   
      <div id="splitter">
      <div id='jqxWidget'>
        <div id="mainSplitter">
            <div class="splitter-panel">
                 <iframe 
            src="index.php?option=com_searchengine&view=usersearch&package_id=<?php echo $this->package_id;?>&criteria=1&task=usersearch.searchgorup&tmpl=component" 
            width="100%" id="column1" scrolling="no"></iframe> </div>
            <div class="splitter-panel">
               <div class="loading_box_center"> Searching<span class="serching"></span> </div> 
            <iframe src="" width="100%" id="column2" scrolling="no" width="100%"></iframe>    
        </div> </div>
        </div>
    </div>
    </div>
  </div>
</div>      

<script type="text/javascript">

 $(document).ready(function () {
    var document_width = $( document ).width();
    var document_height = $('body').innerHeight();
    $('#column1,#column2').css('height',document_height-10);

    var left_column = (document_width/100) * 24;
    var document_width = document_width - 50;
    var rest_column =  document_width - left_column; 


    $('#mainSplitter').jqxSplitter({ width: document_width, height: document_height, panels: [{ size: left_column }] });
});

$(function($) {

    var col1=$('#column1');
    var col2= $('#column2');
    
    col1.load(function(){
      col1.contents().find('.search_btn').click(function(){
        jQuery('.loading_box_center').show();
        var target= col1.contents().find('#target_column1'); 
        var frm = col1.contents().find('#usergorupForm'); 
        var target_url = target.val() +'&usergorup_from='+ frm.find('#usergorup_from').val()+'&usergorup_to='+ frm.find('#usergorup_to').val(); 
        console.log(target_url);
        col2.attr("src",target_url );
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