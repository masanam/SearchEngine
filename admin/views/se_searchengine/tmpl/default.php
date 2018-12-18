<?php
defined('_JEXEC') or die();
// JHtml::_('behavior.tooltip');
// JHTML::_('behavior.modal');
?>
<script type="text/javascript">
  function submitForm(action) {
    var form = document.getElementById('adminForm');
    form.action = action;
    form.submit();
  }

  function CksubmitForm(action) {
    var checkLength = 0;
    var x = $(".z:checked").length;
     var boxes = document.getElementById("cb").getElementsByTagName("input");
     for (var i = 0; i < boxes.length; i++)
     {
         boxes[i].checked ? checkLength++ : null;
     }
     if (x > 5){
     alert (" Maximum 5 boxes are checked." );
     return false;
   }else {
    var form = document.getElementById('adminForm');
    form.action = action;
    form.submit();
  }
  }
</script>
  <div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
    <div class="row-fluid">
      <form action="<?php echo JRoute::_('index.php?option=com_searchengine&view=se_searchengine');?>" method="post" name="adminForm" id="adminForm" class="validate" enctype='multipart/form-data'>

    <div id="mainSplitter">
        <div style="overflow: auto;">
            <div  style="padding:10px;">
            		<?php //$current_url = JUri::getInstance();
if ( ($_POST['history']) || ($this->checkHistory=='1')){
$checkedH=" checked='checked'  value='0' ";
}
else {
$checkedH = " value='1' ";
}
 ?>
 <table class="table table-hover table-striped table-bordered">
	<thead>
    <tr><td colspan="5">
<ul id="navlist">
				<li><input type="radio" name="search_cat" <?php if($this->search_cat=='web') echo 'checked'; ?> value="web" onclick="submitForm('<?php echo JRoute::_('index.php?option=com_searchengine&view=se_searchengine'); ?>')"> All </li>
				<li><input type="radio" name="search_cat" <?php if($this->search_cat=='video') echo 'checked'; ?> value="video" onclick="submitForm('<?php echo JRoute::_('index.php?option=com_searchengine&view=se_searchengine'); ?>')"> Video</li>
				<li><input type="radio" name="search_cat" <?php if($this->search_cat=='image') echo 'checked'; ?> value="image" onclick="submitForm('<?php echo JRoute::_('index.php?option=com_searchengine&view=se_searchengine'); ?>')"> Image</li>
				<li><input type="radio" name="search_cat" <?php if($this->search_cat=='news') echo 'checked'; ?> value="news" onclick="submitForm('<?php echo JRoute::_('index.php?option=com_searchengine&view=se_searchengine'); ?>')"> News</li>
			</ul>
</td><td>
search input history<input type="checkbox" id="history" name="history" <?php  echo $checkedH; ?> onchange="submitForm('<?php echo JRoute::_('index.php?option=com_searchengine&view=se_searchengine'); ?>')" />
</td>
</tr>

		<tr style="text-align:center; background-color:#CCCCCC">
        			<th><?php echo JText::_('Id');?></th>
			<th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?>
			</th>
			<th><?php echo JText::_('Search Type');?></th>
			<th><?php echo JText::_('Search Input');?></th>
			<th><?php echo JText::_('First Input DateTime');?></th>
			<th><?php echo JText::_('Input IP Address');?></th>
		</tr>
	</thead>
	<tbody>
    	<?php
        $selected = $_POST['cid'];
	   foreach($this->list_data as $i=>$item){
       $i++;
       $audit = $item->search_type.'|'.$item->keyword.'|'.$item->id;
       if (isset($selected)){
              if(in_array($audit, $selected)) {
                     $checkedI="checked='checked'";
              }else{
                 $checkedI = '';
              }
            }
		?>

        <tr>
           	<td align="center"><?php echo $i;?></td>
   			<td align="center"><?php
			//echo '<input type="checkbox" id="cb'.$i.'" name="cid[]" value="'.$item->search_type.'|'.$item->keyword.'|'.$item->id.'" onClick="submit();" '.$checkedI.' />';
      echo '<input type="hidden" id="search_id" name="search_id" value="'.$item->id.'" />';
			 ?>
       <input type="checkbox" class="z" value="<?php echo $item->search_type.'|'.$item->keyword.'|'.$item->id;?>" id="cb" name="cid[]" <?php  echo $checkedI; ?> onchange="CksubmitForm('<?php echo JRoute::_('index.php?option=com_searchengine&view=se_searchengine'); ?>')" />
             </td>
    		<td><?php echo $item->search_type.'<input type="hidden" id="search_type" name="search_type" value="'.$item->search_type.'" >';
			?></td>
    		<td><?php echo $item->keyword.'<input type="hidden" id="search_keyword" name="search_keyword" value="'.$item->keyword.'" >';
			?></td>
    		<td><?php echo $item->date_time;?></td>
    		<td><?php echo $item->user_ip;?></td>
         </tr>
    <?php
	}
	?>
    </tbody>
			</table>
            </div>
            <div class="pagination pagination-toolbar">
               <?php
       if ($this->pageNav->pagesTotal > 0) {
        echo $this->pageNav->getListFooter();
       } ?>
            </div>
          </form>

        </div>
              <div style="overflow: auto;">

            <div id="rightSplitter">
           <div  style="padding:10px;"> <h4>Search Engine Result</h4>
               							<div class="well">
  <?php
	 if( $this->search_results ){
		$date=	date("d M Y");
		echo '<ul id="navlist" style="padding:10px;">';
    foreach(array_unique($this->type) as $type){
		if($type=='web'){
		echo '<li style="border:1px solid #333;padding:10px 20px;margin:10px;">All</li>';
		}elseif($type=='video'){
		echo '<li style="border:1px solid #333;padding:10px;margin:10px;">Video</li>';
		}elseif($type=='image'){
		echo '<li style="border:1px solid #333;padding:10px;margin:10px;">Image</li>';
		}elseif($type=='news'){
		echo '<li style="border:1px solid #333;padding:10px;margin:10px;">News</li>';
		}
  }
        echo '</ul>';
		echo "<div class='search-detail'><span class='type-text'>".$this->searchData['type']."</span>&nbsp;->&nbsp;<span class='keyword-text'>".$this->searchData['keyword']."</span>&nbsp;->&nbsp;<span class='date-text'>".$date."</span>&nbsp;->&nbsp;<span class='ip-text'>".$this->searchData['userip']."</span></div><br>";
		//All Results
		if ($this->searchData['type'] === 'web'){
			foreach ($this->search_results as $search_result){
				echo "<p><a href='".$search_result['link']."'>".$search_result['title']."</a><br />";
				echo "".$search_result['meta_desc']."<br />";
				echo $search_result['descr']."</p>";
			}
		}

		//Type Image
		if ($this->searchData['type'] === 'image'){
			foreach ($this->search_results as $search_result){
				echo '<a href="'.$search_result['link'].'">'.'<img src="'.$search_result['thumb_img'].'"></a>'."<br /><br />";
			}
		}

		//Videos result
		if ($this->searchData['type'] === 'video'){
			foreach ($this->search_results as $search_result){
				echo "<a href='".$search_result['img_thumb_link']."'><img src='".$search_result['thumb_img']."' /></a>";
				echo "<p>".$search_result['title']."<br />";
				echo "<a href='".$search_result['link']."'>".$search_result['link']."</a><br />";
				echo $search_result['descr']."</p>";
			}
		}

		// News Search Results
		if ($this->searchData['type'] === 'news'){
			foreach ($this->search_results as $search_result){
				echo "<a href='".$search_result['img_thumb_link']."'><img src='".$search_result['thumb_img']."'  /></a>";
				echo "<p><a href='".$search_result['link']."'>".$search_result['title']."</a><br />";
				echo "".$search_result['meta_desc']."<br/>";
				echo $search_result['descr']."</p>";
			}
		}
	}
	?>
			</div>
            </div>
        </div>

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

    var left_column = (document_width/100) * 30;
    var document_width = document_width - 50;
    var rest_column =  document_width - left_column;


            $('#mainSplitter').jqxSplitter({ width: document_width, height: document_height, panels: [{ size: left_column }] });
            $('#rightSplitter').jqxSplitter({ height: '100%', orientation: 'horizontal', panels: [{ size: '80%', collapsible: false }, { size: '20%' }] });
});

// $(function($) {
//
//     var col1=$('#column1');
//     var col2= $('#column2');
//
//     col1.load(function(){
//       col1.contents().find('.search_btn').click(function(){
//         jQuery('.loading_box_center').show();
//         var target= col1.contents().find('#target_column1');
//         var frm = col1.contents().find('#usergorupForm');
//         var target_url = target.val() +'&usergorup_from='+ frm.find('#usergorup_from').val()+'&usergorup_to='+ frm.find('#usergorup_to').val();
//         console.log(target_url);
//         col2.attr("src",target_url );
//       });
//     });
//
// });
// function dotdotdot(cursor, times, string) {
//   return Array(times - Math.abs(cursor % (times * 2) - times) + 1).join(string);
// }
//
// function get_checked ( $id ) {
//     if ( isset($_POST) && isset($_POST['checked'][$id]) ) {
//         return $_POST['checked'][$id];
//     }
//     return false;
// }
// var cursor = 0;
// setInterval(function () {
//   jQuery('.serching').text(dotdotdot(cursor++, 5, '.'));
// }, 100);

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
    width: 300px;
    color: white;
}
#navlist li
{
display: inline;
list-style-type: none;
padding-right: 10px;

}
</style>
