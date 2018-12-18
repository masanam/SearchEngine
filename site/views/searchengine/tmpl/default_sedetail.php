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

  function giftcode(id,name,qty) {
    alert ('You have received '+qty+' x '+name );

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

<div id="top_table">
<h2><?php echo JText::_('Search Engine Page');?></h2>

<?php $namegc = explode("_",JRequest::getVar('name'));
$urlgrupid = JRequest::getVar('id');
$name = $namegc[0];
$gc = $namegc[1];
$gcid = isset($_GET['gcid']);
if ($gcid > 0){
	$gcid_x = $returnitem->gcid;
  $gcqty_x = $returnitem->gcqty;
  $gcname_x = $returnitem->gcname;


}
$detail = JRequest::getVar('detail');
if ($detail == 1){
?>


<div class="navbar">
	<div class="navbar-inner" style="background-color: #fafafa;">
		<div class="header-container">
			<div class="cq-nav-collapse nav-collapse collapse">
				<ul class="nav">
					<li>
						<button class="btn margin-bottom-10" type="button" onclick=" return false;"><i class="fa fa-times"></i> <?php echo $gc;?></button>
					</li>
					<li>
						<a href="#" onclick="return false;" style="cursor:pointer">
							<?php echo $name;?>
						</a>

					</li>
				</ul>
			</div>
			<div style="float:right;"><button class="btn margin-bottom-10" type="button" onclick=" window.history.back();return false;"><i class="fa fa-times"></i> Back</button></div>
		</div>
	</div>
</div>
<?php
						$ireturnitem=1;
						$model1 =& JModelLegacy::getInstance('searchengine','SearchEngineModel');
					  $return = $model1->get_searchenginesrewardsdetail($ireturnitem);
					             foreach ($return as $item)
					             {

					 if($item->gc>0){
					 $gctype_x = $item->gctype;
					 $url_x = $item->urls;
					 $title_x = $item ->title;
					 $published_x = $item ->published;
					 $keyword = JRequest::getVar('search');
				 }
			 }
			 ?>
<table align="center" border="0" class="table table-hover table-striped">
        <thead>
            <tr style="text-align:center; background-color:#CCCCCC">
				<th><?php echo JText::_('Giftcode ');?></th>
                <th><?php echo JText::_('Item type');?></th>
                <th><?php echo JText::_('Item');?></th>
                <th><?php echo JText::_('Published');?></th>
            </tr>
			</thead>
			<?php
			foreach ($this->rows as $returnitem)
            {
            ?>
			<?php if($returnitem->gcqty > 0){ ?>
            <tr class="row<?php echo $k % 2; ?>"  onmouseleave="funopenurlspopupclose()">
				<td align="center"><?php echo $returnitem->gcqty;  ?></td>
                <td><?php echo $gctype_x; ?></td>
								<td>
												<?php if($gctype_x=="Quiz"){ ?>
													<a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=sedetail&task=searchengine.sedetail&reward=0&detail=0&grupid='.$urlgrupid.'&gcid='.$returnitem->gcid.'&gcqty='.$returnitem->gcqty.'&gcname='.$returnitem->gcname)?>"  data-id="<?php echo $returnitem->id.$ireturnitem; ?>">
																				<?php echo $this->escape( $title_x); ?>
																			</a>
												<?php }elseif($gctype_x=="Survey"){ ?>
                          <a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=sedetail&task=searchengine.sedetail&reward=0&detail=0&grupid='.$urlgrupid.'&gcid='.$returnitem->gcid.'&gcqty='.$returnitem->gcqty.'&gcname='.$returnitem->gcname)?>"  data-id="<?php echo $returnitem->id.$ireturnitem; ?>">
																				<?php echo $this->escape( $title_x); ?>
																			</a>
												<?php }elseif($gctype_x=="Url Group"){ ?>

												<a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=sedetail&task=searchengine.sedetail&reward=0&detail=0&grupid='.$urlgrupid.'&gcid='.$returnitem->gcid.'&gcqty='.$returnitem->gcqty.'&gcname='.$returnitem->gcname)?>"  data-id="<?php echo $returnitem->id.$ireturnitem; ?>">
																				<?php echo $this->escape( $title_x); ?>
																			</a>

												<?php }elseif($gctype_x=="Quiz Group"){ ?>
													<a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=sedetail&task=searchengine.sedetail&reward=0&detail=0&grupid='.$urlgrupid.'&gcid='.$returnitem->gcid.'&gcqty='.$returnitem->gcqty.'&gcname='.$returnitem->gcname)?>"  data-id="<?php echo $returnitem->id.$ireturnitem; ?>">
																				<?php echo $this->escape( $title_x); ?>
																			</a>

												<?php }elseif($gctype_x=="Survey Group"){ ?>
													<a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=sedetail&task=searchengine.sedetail&reward=0&detail=0&grupid='.$urlgrupid.'&gcid='.$returnitem->gcid.'&gcqty='.$returnitem->gcqty.'&gcname='.$returnitem->gcname)?>"  data-id="<?php echo $returnitem->id.$ireturnitem; ?>">
																				<?php echo $this->escape( $title_x); ?>
																			</a>

												<?php }else{ ?>
												<?php echo  $title_x; ?>
												<?php } ?>
											</td>
							<td>
	<?php if($published_x== "1")
	{
		echo "Yes";
	}else{
		echo "No";
	}
	?>
	</td>
			</tr>

		<?php }
} ?>
		</table>

		<?php
  } else {
    $gcid_x = JRequest::getVar('gcid');
    $gcname_x = JRequest::getVar('gcname');
    $gcqty_x = JRequest::getVar('gcqty');
    $model1 =& JModelLegacy::getInstance('searchengine','SearchEngineModel');
    $savetotal = $model1->savegiftcode($this->userId, $gcid_x, $gcid_x,  0);
?>
    <div class="navbar">
    	<div class="navbar-inner" style="background-color: #fafafa;">
    		<div class="header-container">
    			<div class="cq-nav-collapse nav-collapse collapse">
    				<ul class="nav">
    					<li>
    						<button class="btn margin-bottom-10" type="button" onclick=" return false;"><i class="fa fa-times"></i> <?php echo $gcqty_x;?></button>
    					</li>
    					<li>
    						<a href="#" onclick="return false;" style="cursor:pointer">
    							<?php echo $name;?>
    						</a>

    					</li>
    				</ul>
    			</div>
    			<div style="float:right;"><button class="btn margin-bottom-10" type="button" onclick=" window.history.back();return false;"><i class="fa fa-times"></i> Back</button></div>
    		</div>
    	</div>
    </div>
    <?php
    						$ireturnitem=1;
                $grupid = JRequest::getVar('grupid');
    						$model1 =& JModelLegacy::getInstance('searchengine','SearchEngineModel');
                $return = $model1->get_url($grupid);
    					  //$return = $model1->get_searchenginesrewardsdetail($ireturnitem);

    			 ?>
    <table align="center" border="0" class="table table-hover table-striped">
            <thead>
                <tr style="text-align:center; background-color:#CCCCCC">
    				<th><?php echo JText::_('Giftcode');?></th>
                    <th><?php echo JText::_('Item');?></th>
                    <th><?php echo JText::_('Published');?></th>
                </tr>
    			</thead>
    			<?php
    			foreach ($return as $returnitem)
                {
                  if (strpos($returnitem->title, 'http') === false) {
                      // The word was NOT found
                    $urltitle = "http://".$returnitem->title;
                  }
                ?>
    			<?php if($gcqty_x > 0){ ?>
                <tr class="row<?php echo $k % 2; ?>"  onmouseleave="funopenurlspopupclose()">
    				<td align="center"><?php echo $gcqty_x;  ?></td>
                    <td><a onclick="giftcode(<?php echo $gcid_x.",'".$gcname_x."',". $gcqty_x; ?>);" target="_blank" href="<?php echo $returnitem->title; ?>"><?php echo $returnitem->title; ?></a></td>

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

    		<?php }
    } ?>
    		</table>
        <?php
  }
		foreach ($this->rows as $returnitem)
					{
					?>
		<?php if($returnitem->gcqty > 0){ ?>
			<div style="display:none;position: absolute;" id="divurls<?php echo $returnitem->id.$ireturnitem ?>" class="divgroups">

			<?php
			$urls = explode(",",$url_x);
			?>


			<table width="50%" align="center" border="0" class="table table-hover table-striped " style="position:absolute; background-color: #ffffff;
			margin-left: 100px;margin-top:-25px;
			z-index: 10000;">
			<thead>
			<tr style="text-align:center; background-color:#CCCCCC">
			<th><?php echo JText::_('Giftcode ');?></th>
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
			//   $x = 1;
			// while($x <= $returnitem->gcqty) {
			//   $savetotal = $model1->savegiftcode($this->userId, $returnitem->gcid, $returnitem->gcid,  0);
			//     $x++;
			// }

			if (strpos($urltitle, 'http') === false) {
			// The word was NOT found
			$urltitle = "http://".$urltitle;
			}
			?>
			<tr class="row<?php echo $k % 2; ?>" >
			<td align="center"><?php echo $returnitem->gcqty;  ?></td>
				<td>
					<a target="_blank"   href="<?php echo $urltitle;?>">
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
<?php }
} ?>
