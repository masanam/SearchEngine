<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
Joomla.submitbutton = function(pressbutton) {
	if(pressbutton=='se_urlreward.create'){
		window.location.href = "index.php?option=com_searchengine&controller=se_urlreward&task=create&package_id=<?php echo $this->package_id; ?>";
	}else{
		document.adminForm.task.value=pressbutton;
		submitform(pressbutton);
	}
}	 
</script>

<div id="cj-wrapper">
        <div class="survey-wrapper nospace-left no-space-left no-space-right">
            <div class="row-fluid">
<?php echo $this->loadTemplate('nav');?>

<div id="j-main-container" class="span8">
<form method="post" action="<?php echo JRoute::_('index.php?option=com_searchengine&task=se_urlreward.delete&package_id='.JRequest::getVar('package_id'));?>" name="adminForm" id="adminForm">
<table align="center" border="0" class="table table-hover table-striped">
	<thead>
		<tr style="text-align:center; background-color:#CCCCCC">		
			<th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?></th>
			<th><?php echo JText::_('Rewards rule');?></th>
			<th><?php echo JText::_('Created');?></th>
			<th><?php echo JText::_('Modified');?></th>			
		</tr>
	</thead>
	<?php 
	if( $this->se_urlrewards ) :
	foreach($this->se_urlrewards as $i => $result):?>
	<tr class="row<?php echo $i % 2; ?>">
		<td align="center"><input type="hidden"
			value="<?php echo $item->id;?>" name="setting_id[]"> <?php echo JHtml::_('grid.id', $i, $result->id); ?>
		</td>	
		<td align="center"><a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=se_urlreward&task=se_urlreward.edit&id='.$result->id.'&package_id='.$this->package_id); ?>"><?php echo $result->title;?></td>
		<td align="center"><?php echo strtotime($result->created)>0 ? date('G:i a d M Y',strtotime($result->created)): '-';?></td>
		<td align="center"><?php echo strtotime($result->modified)>0 ? date('G:i a d M Y',strtotime($result->modified)): '-';?></td>
		</td>
	</tr>
	<?php 
	endforeach;
	else:
	?>
		<tr><td colspan="5">No records found</td></tr>
	<?php
	endif;
	 ?>
</table>
	 <table width="100%">
                <tr><td style="text-align:right;">
                <div class="pagination pagination-toolbar">
                  <?php 
                  echo $this->pagination;                  
                ?>
                </div>
                </td></tr>
              </table>
<div><input type="hidden" id="task" name="task" value="se_urlreward.delete" /> 
	<input type="hidden" id="option" name="option" value="com_searchengine" /> 
	<input type="hidden" id="boxchecked" name="boxchecked" value="0" /> 
	<?php echo JHtml::_('form.token'); ?>
</div>


 </div>
        </div>
    </div>

</form>
</div>
