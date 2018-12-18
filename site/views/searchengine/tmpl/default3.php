<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
?>


<script type="text/javascript">
  function submitForm(action) {
    var form = document.getElementById('adminForm');
    form.action = action;
    form.submit();
  }

function giftcode(userid,id,name,qty){
  alert('You have received '+ qty +' x '+name);
}
function funopenurlspopup(id){
	jQuery(".divurls").hide();
	jQuery("#divurls"+id).show();
}
function funopenquizpopup(id){
	jQuery(".divquiz").hide();
	jQuery("#divquiz"+id).show();
}
function funopensurveypopup(id){
	jQuery(".divsurvey").hide();
	jQuery("#divsurvey"+id).show();
}


function funopenurlspopupclose(){
	jQuery(".divgroups").hide();
}


function funopenitemspopup(id){
	jQuery(".tblrewarditempopup").hide();
	jQuery("#tblrewarditempopup"+id).show();
}
function funopenitemspopupclose(){
	jQuery(".tblrewarditempopup").hide();
}
</script>

<script>
function hndlr(response) {
for (var i = 0; i < response.items.length; i++) {
  var item = response.items[i];
  // in production code, item.htmlTitle should have the HTML entities escaped.
  document.getElementById("content").innerHTML += "<br>" + item.htmlTitle + "<br>" + item.link+ "<br>" ;
}
}
</script>
    <script src="https://www.googleapis.com/customsearch/v1?key=AIzaSyCUHhGsgkaG5f8lUQ5GVpqAYMCThoGo0kM&cx=001223335659592135423:s4rypv0t30q&q=cars&alt=json&num=10&limitstart=0&amp;callback=hndlr">
    </script>

 <div id="content">
   <script>
     (function() {
       var cx = '014309647146149634510:pymiq0s3p6o';
       var gcse = document.createElement('script');
       gcse.type = 'text/javascript';
       gcse.async = true;
       gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
       var s = document.getElementsByTagName('script')[0];
       s.parentNode.insertBefore(gcse, s);
     })();
   </script>
   <gcse:searchresults-only></gcse:searchresults-only>
 </div>
<form
    action="<?php echo JRoute::_('index.php?option=com_searchengine'); ?>" method="post" name="adminForm" id="adminForm" class="validate" enctype='multipart/form-data'>

<div style="padding:20px;">
  <div class="row">
      <div class="pull-left"><input type="text" name="keyword" class="form-control text" value="<?php echo JRequest::getVar('search');?>"></div>
<?php
if($this->userId>0 && $this->packageId > 0){ ?>
  &nbsp;&nbsp;&nbsp;<button class="btn btn-primary" onclick="submitForm('<?php echo JRoute::_('index.php?option=com_searchengine&reward=0'); ?>')">Search</button>
  <div class="pull-right">
  <a class="btn" href="<?php echo JRoute::_('index.php?option=com_searchengine&view=dashboard');?>">Search engine rewards</a>

</div>
<?php } else { ?>
  &nbsp;&nbsp;&nbsp;<button class="btn btn-primary" onclick="submitForm('<?php echo JRoute::_('index.php?option=com_searchengine&reward=1'); ?>')">Search</button>

<?php }?>
  </div>
	<?php
	if( $this->searchData ){
		$current_url = JUri::getInstance();
    $reward = (($this->userId>0 && $this->packageId>0) ? '0':'1');
    ?>
		<div class="row">
			<ul class="menu">
        <li><input type="radio" name="search_type" <?php if($this->searchData['type']=='web') echo 'checked'; ?> value="web" onclick="submitForm('<?php echo JRoute::_('index.php?option=com_searchengine&reward='.$reward.''); ?>')"> All <!-- <a href="<?php echo $current_url; ?>&type=web">All</a> --> </li>
				<li><input type="radio" name="search_type" <?php if($this->searchData['type']=='video') echo 'checked'; ?> value="video" onclick="submitForm('<?php echo JRoute::_('index.php?option=com_searchengine&reward='.$reward.''); ?>')"> Video<!-- <a href="<?php echo $current_url; ?>&type=video">Video</a> --> </li>
				<li><input type="radio" name="search_type" <?php if($this->searchData['type']=='image') echo 'checked'; ?> value="image" onclick="submitForm('<?php echo JRoute::_('index.php?option=com_searchengine&reward='.$reward.''); ?>')"> Image<!-- <a href="<?php echo $current_url; ?>&type=image">Images</a> --> </li>
				<li><input type="radio" name="search_type" <?php if($this->searchData['type']=='news') echo 'checked'; ?> value="news" onclick="submitForm('<?php echo JRoute::_('index.php?option=com_searchengine&reward='.$reward.''); ?>')"> News<!-- <a href="<?php echo $current_url; ?>&type=news">News</a> --> </li>
			</ul>
		</div>
	<?php } ?>

    <div class="row" style="margin-top:20px;">
					<div class="row-fluid">
						<div class="span12">
							<div class="well">
<div id="result"></div>
  <?php

  //print_r($this->listData);
  if ($this->reward == 0){
    $ireturnitem=1;
    $model1 =& JModelLegacy::getInstance('searchengine','SearchEngineModel');
    $return = $model1->get_searchenginesrewardsdetail($ireturnitem);
               if( $this->rows ){
               foreach ($return as $item)
               {

                 if($item->gc>0){
                 $gctype_x = $item->gctype;
                 $url_x = $item->urls;
                 $title_x = $item ->title;
                 $published_x = $item ->published;
                 $keyword = JRequest::getVar('search');
                 $urls = explode(",",$url_x);
                 $urlid = $urls[0];
                 ?>

            <div class="media clearfix"  onmouseleave="funopenitemspopupclose()" style="position:relative;overflow:visible;" >



  								<div class="pull-left hidden-phone thumbnail num-box" style="background: #ffffff;text-align: center;">
  									<h2 class="num-header">
  									<?php
  										$totalgitcodes = $item->gc;//$item->quizgifcodesum+$item->surveygifcodesum+($item->urlrewardlistgifcodesum*$item->urlgroupsinurlrewardlistcount);
  										echo $totalgitcodes;
  									 ?>

                                      </h2>
  									<span class="muted"><?php echo $item->responses == 1 ? JText::_('Giftcodes') : JText::_('Giftcodes');?></span>
  								</div>
  								<div class="media-body">



  									<div class="clearfix">
                      <h4 style="line-height: 20px;">

  											<?php if($this->userId>0 && $this->packageId>0){ ?>
                          <a  onmouseover="funopenitemspopup('<?php echo $item->id ?>')" href="<?php echo JRoute::_('index.php?option=com_searchengine&Itemid='.JRequest::getVar('Itemid').'&view=sedetail&task=searchengine.sedetail&search='.JRequest::getVar('search').'&reward=0&detail=1&id='.$item->id.'&name='.urlencode($item->title."_".$totalgitcodes).'&urls='.$urlid.'&uid='.JRequest::getVar('uid'));?>">
  												<?php echo $this->escape($item->title); ?>
  											</a>
  											<?php }else{ ?>
  											<a href="javascript:void(0);" onclick="alert('Please login to continue')">
  												<?php echo $this->escape($item->title); ?>
  											</a>
  											<?php } ?>

  <?php

  ?>
  										</h4>

  <table align="center" border="0" class="table table-hover table-striped tblrewarditempopup" id="tblrewarditempopup<?php echo $item->id;?>" style="display:none;position:absolute; background-color: #ffffff;
      margin-left: 100px;
      width: 86%;
      z-index: 1000;">
          <thead>
              <tr style="text-align:center; background-color:#CCCCCC">
  				<th><?php echo JText::_('Giftcode');?></th>
                  <th><?php echo JText::_('Item type');?></th>
                  <th><?php echo JText::_('Item');?></th>
                  <th><?php echo JText::_('Published');?></th>
              </tr>
  			</thead>
              <?php
              if( $return ){
                    foreach ($this->rows as $returnitem)
                    {
                    ?>
        			<?php if($returnitem->gcqty > 0){ ?>
                    <tr class="row<?php echo $k % 2; ?>"  onmouseleave="funopenurlspopupclose()">
        				<td align="center"><?php echo $returnitem->gcqty ;  ?></td>
                        <td><?php echo $gctype_x; ?></td>
                        <td>
        				<?php if($gctype_x=="Quiz"){ ?>
        				<a target="_blank" href="<?php echo JRoute::_('index.php?option=com_searchengine&view=sedetail&task=searchengine.sedetailurlclicked&urlid='.$returnitem->id.'&uid='.JRequest::getVar('uid').'&urltype=quiz'.'&url='.urlencode(JRoute::_('index.php?option=com_awardpackageampersandview=responseampersandtask=response.quiz_introampersandid='.$returnitem->id)));?>">
        												<?php echo $this->escape($title_x); ?>
        											</a>
        				<?php }elseif($gctype_x=="Survey"){ ?>
        				<a target="_blank" href="<?php echo JRoute::_('index.php?option=com_searchengine&view=sedetail&task=searchengine.sedetailurlclicked&urlid='.$returnitem->id.'&uid='.JRequest::getVar('uid').'&urltype=survey'.'&url='.urlencode(JRoute::_('index.php?option=com_awardpackageampersandview=responsezsampersandtask=responsezs.survey_introampersandid='.$returnitem->id.'ampersandskey='.$returnitem->survey_key)));?>">
        												<?php echo $this->escape($title_x); ?>
        											</a>
        				<?php }elseif($gctype_x=="Url Group"){ ?>
        				<a href="#" onmouseover="funopenurlspopup('<?php echo $returnitem->id.$ireturnitem ?>')" data-id="<?php echo $returnitem->id.$ireturnitem ?>">
        												<?php echo $this->escape($title_x); ?>
        											</a>

        				<?php }elseif($gctype_x=="Quiz Group"){ ?>
        				<a href="#" onmouseover="funopenquizpopup('<?php echo $returnitem->id.$ireturnitem ?>')" data-id="<?php echo $returnitem->id.$ireturnitem ?>">
        												<?php echo $this->escape($returnitem->title_x); ?>
        											</a>

        				<?php }elseif($gctype_x=="Survey Group"){ ?>
        				<a href="#" onmouseover="funopensurveypopup('<?php echo $returnitem->id.$ireturnitem ?>')" data-id="<?php echo $returnitem->id.$ireturnitem ?>">
        												<?php echo $this->escape($title_x); ?>
        											</a>

        				<?php }else{ ?>
        				<?php echo $returnitem->title; ?>
        				<?php } ?>
        				<div style="display:none;position: absolute;" id="divurls<?php echo $returnitem->id.$ireturnitem ?>" class="divgroups">

        <?php
        $urls = explode(",",$url_x);
        $urlid = $urls[0];
        ?>


  <table align="center" border="0" class="table table-hover table-striped " style="position:absolute; background-color: #ffffff;
      margin-left: 100px;margin-top:-25px;
      z-index: 10000;">
          <thead>
              <tr style="text-align:center; background-color:#CCCCCC">
  				<th><?php echo JText::_('Giftcode');?></th>
                  <th><?php echo JText::_('Item');?></th>
                  <th><?php echo JText::_('Published');?></th>
              </tr>
  			</thead>
  			<?php foreach($urls as $url){
  $urls1 = explode("-",$url);
  $urlid = $urls1[0];
  $urltitle = $urls1[1];
  $gcid_x = $returnitem->gcid;
  $gcqty_x = $returnitem->gcqty;
  $gcname_x = $returnitem->gcname;


  if (strpos($urltitle, 'http') === false) {
      // The word was NOT found
  	$urltitle = "http://".$urltitle;
  }
  			?>
  			<tr class="row<?php echo $k % 2; ?>" >
          <td align="center"><?php echo $returnitem->gcqty;  ?></td>
                  <td><a onclick="giftcode(<?php echo $this->userId.",".$gcid_x.",'".$gcname_x."',". $gcqty_x; ?>);" target="_blank"  href="<?php echo $urltitle;?>">
  												<?php echo $urltitle; ?>
  											</a>

  				</td>
                  <td>
  				<?php if($returnitem->published == "1")
  				{
  					echo "Yes";
  				}else{
  					echo "No";
  				}
  				?>
  				</td>
  			</tr>
  			<?php } ?>
  </table>
  				</div>
  				<div style="display:none;position: absolute;" id="divquiz<?php echo $returnitem->id.$ireturnitem ?>" class="divgroups">


  <table align="center" border="0" class="table table-hover table-striped " style="position:absolute; background-color: #ffffff;
      margin-left: 100px;margin-top:-25px;
      z-index: 10000;">
          <thead>
              <tr style="text-align:center; background-color:#CCCCCC">
  				<th><?php echo JText::_('Giftcode ');?></th>
                  <th><?php echo JText::_('Item');?></th>
                  <th><?php echo JText::_('Published');?></th>
              </tr>
  			</thead>

  			<?php
              foreach ($return as $returnitemquiz)
              {
  				if($returnitemquiz->gctype=="Quiz"){
              ?>
  			<tr class="row<?php echo $k % 2; ?>" >
  				<td align="center"><?php echo $returnitemquiz->gc;  ?></td>
  				<td align="center">
  				<a target="_blank" href="<?php echo JRoute::_('index.php?option=com_searchengine&view=sedetail&task=searchengine.sedetailurlclicked&urlid='.$returnitemquiz->id.'&uid='.JRequest::getVar('uid').'&urltype=quiz'.'&url='.urlencode(JRoute::_('index.php?option=com_awardpackageampersandview=responseampersandtask=response.quiz_introampersandid='.$returnitemquiz->id)));?>">
  												<?php echo $this->escape($returnitemquiz->title); ?>
  											</a>
  				</td>
                  <td>
  				<?php if($returnitemquiz->published == "1")
  				{
  					echo "Yes";
  				}else{
  					echo "No";
  				}
  				?>
  				</td>
  			</tr>
  				<?php } ?>
  			<?php } ?>

  </table>
  				</div>
  				<div style="display:none;position: absolute;" id="divsurvey<?php echo $returnitem->id.$ireturnitem ?>" class="divgroups">
  <table align="center" border="0" class="table table-hover table-striped " style="position:absolute; background-color: #ffffff;
      margin-left: 100px;margin-top:-25px;
      z-index: 10000;">
          <thead>
              <tr style="text-align:center; background-color:#CCCCCC">
  				<th><?php echo JText::_('Giftcode');?></th>
                  <th><?php echo JText::_('Item');?></th>
                  <th><?php echo JText::_('Published');?></th>
              </tr>
  			</thead>

  			<?php
              foreach ($return as $returnitemsurvey)
              {
  				if($returnitemsurvey->gctype=="Survey"){
              ?>
  			<tr class="row<?php echo $k % 2; ?>" >
  				<td align="center"><?php echo $returnitemsurvey->gc;  ?></td>
  				<td align="center">
  				<a target="_blank" href="<?php echo JRoute::_('index.php?option=com_searchengine&view=sedetail&task=searchengine.sedetailurlclicked&urlid='.$returnitemsurvey->id.'&uid='.JRequest::getVar('uid').'&urltype=survey'.'&url='.urlencode(JRoute::_('index.php?option=com_awardpackageampersandview=responsezsampersandtask=responsezs.survey_introampersandid='.$returnitemsurvey->id.'ampersandskey='.$returnitemsurvey->survey_key)));?>">
  												<?php echo $this->escape($returnitemsurvey->title); ?>
  											</a>
  				</td>
                  <td>
  				<?php if($returnitemsurvey->published == "1")
  				{
  					echo "Yes";
  				}else{
  					echo "No";
  				}
  				?>
  				</td>
  			</tr>
  				<?php } ?>
  			<?php } ?>

  </table>

  				</div>
        				</td>
                  <td>
  		<?php if($returnitem->published == "1")
  		{
  			echo "Yes";
  		}else{
  			echo "No";
  		}
  		?>
  		</td>
              </tr>
              <?php

  		$ireturnitem++;
  			}
  			}
  			}
 ?>

      </table>


  									</div>
  								</div>
  							</div>

              <?php
  		$i++;
  			}
  			}
  }
}
  		 //
//echo "default <pre>";print_r($this->search_results);
	 if( $this->searchData ){
		// include('default_html_dom.php');

		//echo $this->searchData['keyword']. "<br>";
		//echo $this->searchData['url'];
		$date=	date("d M Y");
		echo "<div class='search-detail'><span class='type-text'>".$this->searchData['type']."</span>&nbsp;->&nbsp;<span class='keyword-text'>".$this->searchData['keyword']."</span>&nbsp;->&nbsp;<span class='date-text'>".$date."</span>&nbsp;->&nbsp;<span class='ip-text'>".$this->searchData['userip']."</span></div><br>";

		//All Results
		if ($this->searchData['type'] === 'web'){
			foreach ($this->search_results as $search_result){
				//echo "<a href='".$search_result['img_thumb_link']."'><img src='".$search_result['thumb_img']."' /></a>";
				echo "<p><a href='".$search_result['link']."'>".$search_result['title']."</a><br />";
				echo "".$search_result['meta_desc']."<br />";
				echo $search_result['descr']."</p>";
			}
		}

		//Type Image
		if ($this->searchData['type'] === 'image'){
			foreach ($this->search_results as $search_result){
				echo '<a href="'.$search_result['link'].'">'.'<img src="'.$search_result['img_src'].'" width="200px"></a>'."<br /><br />";
			}
		}

		//Videos result
		if ($this->searchData['type'] === 'video'){
			foreach ($this->search_results as $search_result){
				echo "<a href='".$search_result['img_thumb_link']."'><img src='".$search_result['thumb_img']."' width='200px'/></a>";
				echo "<p>".$search_result['title']."<br />";
				echo "<a href='".$search_result['link']."'>".$search_result['link']."</a><br />";
				echo $search_result['descr']."</p>";
			}
		}

		// News Search Results
		if ($this->searchData['type'] === 'news'){
			foreach ($this->search_results as $search_result){
				echo "<a href='".$search_result['img_thumb_link']."'><img src='".$search_result['thumb_img']."' width='200px'/></a>";
				echo "<p><a href='".$search_result['link']."'>".$search_result['title']."</a><br />";
				//echo "<a href='".$search_result['link']."'>".$search_result['link']."</a><br />";
				echo "".$search_result['meta_desc']."<br/>";
				echo $search_result['descr']."</p>";
			}
		}
	}
/**/
?>
<table width="100%">
           <tr><td style="text-align:right;">
           <div class="pagination pagination-toolbar">
              <?php
      if ($this->pageNav->pagesTotal > 0) {
       echo $this->pageNav->getListFooter();
      }

?>
           </div>
           </td></tr>
         </table>



    </div></div></div></div>


</div>
<input type="hidden" name="task" value="searchengine.search" id="task" />
 <input type="hidden" value="<?php echo $this->package_id; ?>" name="package_id">
 <input type="hidden" name="boxchecked" value="0">
 <?php echo JHtml::_('form.token'); ?>
</form>

<div id="giftcodeModalWindow" class="modal hide fade" style="width: 300px;min-height: 300px;left: 78%">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3><?php echo JText::_('You Have Received');?></h3>
    </div>
    <div style="padding: 5%">
        <?php
        $giftcodReceived .= '<h4>'.$gcqty_x .' x '. $gcname_x.'</h4>';
      echo $giftcodReceived;
    ?>

        <button type="button" class="btn btn-default" id="closeGiftcode" style="margin-left: 40%">Close</button>
    </div>
</div>
