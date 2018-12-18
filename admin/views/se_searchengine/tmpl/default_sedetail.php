<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
?>


<div id="top_table">
<h2><?php echo JText::_('Search Engine Page');?></h2>

<?php $namegc = explode("_",JRequest::getVar('name'));
$name = $namegc[0];
$gc = $namegc[1];
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

<table align="center" border="0" class="table table-hover table-striped">
        <thead>
            <tr style="text-align:center; background-color:#CCCCCC">
				<th><?php echo JText::_('Giftcode');?></th>
                <th><?php echo JText::_('Item type');?></th>
                <th><?php echo JText::_('Item');?></th>
                <th><?php echo JText::_('Published');?></th>
            </tr>
			</thead>
            <?php
			if( $this->items ){
				$i=0;
            foreach ($this->items as $returnitem)
            {
            ?>
			<?php if($returnitem->gctype=="Survey Group" || $returnitem->gctype=="Quiz Group" || $returnitem->gctype=="Url Group"){ ?>
            <tr class="row<?php echo $k % 2; ?>"  onmouseleave="funopenurlspopupclose()">
				<td align="center"><?php echo $returnitem->gc;  ?></td>			
                <td><?php echo $returnitem->gctype; ?></td>
                <td>
				<?php if($returnitem->gctype=="Quiz"){ ?>
				<a target="_blank" href="<?php echo JRoute::_('index.php?option=com_searchengine&view=sedetail&task=searchengine.sedetailurlclicked&urlid='.$returnitem->id.'&uid='.JRequest::getVar('uid').'&urltype=quiz'.'&url='.urlencode(JRoute::_('index.php?option=com_awardpackageampersandview=responseampersandtask=response.quiz_introampersandid='.$returnitem->id)));?>">
												<?php echo $this->escape($returnitem->title); ?>
											</a>
				<?php }elseif($returnitem->gctype=="Survey"){ ?>
				<a target="_blank" href="<?php echo JRoute::_('index.php?option=com_searchengine&view=sedetail&task=searchengine.sedetailurlclicked&urlid='.$returnitem->id.'&uid='.JRequest::getVar('uid').'&urltype=survey'.'&url='.urlencode(JRoute::_('index.php?option=com_awardpackageampersandview=responsezsampersandtask=responsezs.survey_introampersandid='.$returnitem->id.'ampersandskey='.$returnitem->survey_key)));?>">
												<?php echo $this->escape($returnitem->title); ?>
											</a>
				<?php }elseif($returnitem->gctype=="Url Group"){ ?>
				<a href="#" onmouseover="funopenurlspopup('<?php echo $returnitem->id.$ireturnitem ?>')" data-id="<?php echo $returnitem->id.$ireturnitem ?>">
												<?php echo $this->escape($returnitem->title); ?>
											</a>
											
				<?php }elseif($returnitem->gctype=="Quiz Group"){ ?>
				<a href="#" onmouseover="funopenquizpopup('<?php echo $returnitem->id.$ireturnitem ?>')" data-id="<?php echo $returnitem->id.$ireturnitem ?>">
												<?php echo $this->escape($returnitem->title); ?>
											</a>
											
				<?php }elseif($returnitem->gctype=="Survey Group"){ ?>
				<a href="#" onmouseover="funopensurveypopup('<?php echo $returnitem->id.$ireturnitem ?>')" data-id="<?php echo $returnitem->id.$ireturnitem ?>">
												<?php echo $this->escape($returnitem->title); ?>
											</a>
											
				<?php }else{ ?>				
				<?php echo $returnitem->title; ?>
				<?php } ?>
				<div style="display:none;position: absolute;" id="divurls<?php echo $returnitem->id.$ireturnitem ?>" class="divgroups">
				
<?php 
$urls = explode(",",$returnitem->urls);
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

if (strpos($urltitle, 'http') === false) {
    // The word was NOT found
	$urltitle = "http://".$urltitle;
}
			?>
			<tr class="row<?php echo $k % 2; ?>" >
				<td align="center"><?php echo $returnitem->gc;  ?></td>
                <td><a target="_blank" href="<?php echo JRoute::_('index.php?option=com_searchengine&view=sedetail&task=searchengine.sedetailurlclicked&urlid='.$urlid.'&uid='.JRequest::getVar('uid').'&urltype=url'.'&url='.$urltitle);?>">
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
				<th><?php echo JText::_('Giftcode');?></th>
                <th><?php echo JText::_('Item');?></th>
                <th><?php echo JText::_('Published');?></th>
            </tr>
			</thead>
			
			<?php
            foreach ($this->items as $returnitemquiz)
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
            foreach ($this->items as $returnitemsurvey)
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
		else{
		?>
			<tr><td colspan="5">No records found</td></tr>
		<?php
		}
		 ?>
        
    </table>

</div>
<script>
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
</script>

