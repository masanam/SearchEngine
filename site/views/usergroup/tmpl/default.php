<?php
defined('_JEXEC') or die('Restricted access');
if (!@$this->population) {
	$this->population = 100;
}
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$app = JFactory::getApplication();
$filter_order = $app->getUserStateFromRequest('com_awardpackage.usergroup.filter_order', 'filter_order', '', 'cmd');
$filter_order_Dir = $app->getUserStateFromRequest('com_awardpackage.usergroup.filter_order_dir', 'filter_order_Dir', 'DESC', 'word');
?>
<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript">
    jQuery(function($) {
        var index = 'qpsstats-active-tab';
        //  Define friendly data store name
        var dataStore = window.sessionStorage;
        var oldIndex = 0;
        //  Start magic!
        try {
            // getter: Fetch previous value
            oldIndex = dataStore.getItem(index);
        } catch(e) {}

        $( "#tabs" ).tabs({
            active: oldIndex,
            activate: function(event, ui) {
                //  Get future value
                var newIndex = ui.newTab.parent().children().index(ui.newTab);
                //  Set future value
                try {
                    dataStore.setItem( index, newIndex );
                } catch(e) {}
            }
        });
    });

function clickA(){
alert('You cannot add, edit or delete a user group setting when the award package is published.  \r\nAll user group settings must be completed BEFORE publishing the award package');
}

function save_edit(){
	jQuery('#task').val('save_usergroup');
	jQuery('form#adminForm').submit();	
}

	function isNumberKey(evt)
	{
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
			return true;
	 }
	 
	 function validNumber(req){
	 	switch(req){
			case'age':
				var from_age = document.getElementById('jform_from_age');
				var to_age = document.getElementById('jform_to_age');
				if(isNaN(from_age.value)){
					alert('From age number only');
					from_age.focus();
					return false;
				}
				if(isNaN(to_age.value)){
					alert('To Age number only');
					to_age.focus();
					return false;
				}
			break;
			case'loc':
				var post_code = document.getElementById('jform_post_code');
				if(isNaN(post_code.value)){
					alert('Post code number only');
					post_code.value='';
					post_code.focus();
					return false;
				}
			break;
		}
	 }
	 function openModalUserEmail(){
	 	jQuery('#userEmailModalWindow').modal('show');
	 }
	 function openModalUserName(){
		 jQuery('#userNameModalWindow').modal('show');
	 }
	 function onSelectUserEmail(){
		var user_selected = "";
		jQuery('#task').val('addNewUserEmail');
		$('input:checkbox[name=emailaccountid]').each(function(){    
		    if($(this).is(':checked'))
		      user_selected += $(this).val() + ",";
		});
		jQuery('#user_selected').val(user_selected);
		jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&controller=usergroup');
		jQuery('form#adminForm').submit();	
	 }
	 function onSelectUserName(){
		var user_selected = "";
		jQuery('#task').val('addNewUserName');
		$('input:checkbox[name=nameaccountid]').each(function(){    
		    if($(this).is(':checked'))
		      user_selected += $(this).val() + ",";
		});
		jQuery('#user_selected').val(user_selected);
		jQuery('form#adminForm').attr('action', 'index.php?option=com_awardpackage&controller=usergroup');
		jQuery('form#adminForm').submit();
	 }
	 
	 function onLoadAlert() {
	 var tuser = jQuery("#total_user").val();  
	 var tsymbol = jQuery("#total_queue").val();  
	 
	 if (tuser > tsymbol ) {
        alert("Add Symbol Queue first");	 
	 	}
	}

$(document).ready(onLoadAlert);
</script>


<?php $command = JRequest::getVar('command'); ?>
<?php
$presentationGroups = $this->ug->getNameUserGroupPresentation($this->package_id, 'name',JRequest::getVar('criteria_id'));
$presentationGroup = null;
if(!empty($presentationGroups)){
	$presentationGroup = $presentationGroups[0];
}

//$parents = $this->ug->getParentUserGroup($this->package_id,'name');
$par = null;
if(!empty($parents)){
	$par = $parents[0];
}
?>
<div class="span12">
	<?php if ($command == '-1') { ?>
	<div class="clearfix">
		<label><?php echo JText::_('Parent User Group');?></label>
		<select name="parentUserGroup">
			<?php foreach ($parents as $parent) { ?>
			<option value="<?php echo $parent->criteria_id ?>" <?php echo ($parent->criteria_id == $this->parent_usergroup ? "selected=selected" : ""); ?>>
				<?php 
					//echo $parent->group_name;
					echo $parent->criteria_id; 
				?>
			</option>
			<?php } ?>
		</select>
	</div>
	<br/>
	<?php } ?>
	<?php if ($command == '1') { ?>
	<div class="clearfix">
		<label><?php echo JText::_('Group Name');?></label>
		<input name="title" onchange=<?php echo ($this->task == 'edit' ? "save_edit();": ''); ?> id="title" type="text" value="<?php echo $this->groupname;?>" placeholder="<?php echo JText::_('Enter a group name');?>" required="" aria-required="true">
	</div>		
	<?php } ?>
</div>

<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">			
			<div class="span6">
				<div id="tabs">
					<ul>
						<li <?php if ($this->field == 'name') echo $this->class; ?>><a
							href="#tabs-0">Name</a></li>
						<li <?php if ($this->field == 'email') echo $this->class; ?>><a
							href="#tabs-1">E-mail</a></li>
						<li <?php if ($this->field == 'age') echo $this->class; ?>><a
							href="#tabs-2">Age</a></li>
						<li <?php if ($this->field == 'gender') echo $this->class; ?>><a
							href="#tabs-3">Gender</a></li>
						<li <?php if ($this->field == 'location') echo $this->class; ?>><a
							href="#tabs-4">Location</a></li>
					</ul>
					<div id="tabs-0"><?PHP echo $this->loadTemplate('name');?></div>
					<div id="tabs-1"><?PHP echo $this->loadTemplate('email');?></div>
					<div id="tabs-2"><?PHP echo $this->loadTemplate('age');?></div>
					<div id="tabs-3"><?PHP echo $this->loadTemplate('gender');?></div>
					<div id="tabs-4"><?PHP echo $this->loadTemplate('location');?></div>
				</div>
			</div>
			<div class="span6">
					<form
					action="index.php?option=com_awardpackage&view=usergroup&package_id=<?php echo $this->package_id; ?>"
					method="post" name="adminForm" id="adminForm"
					class="form-validate">
					<input type="hidden" name="command" value="<?php echo JRequest::getVar('command') ?>">
					<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>
					<input type="hidden" name="criteria_id" value="<?php echo JRequest::getVar('criteria_id');?>"/>
					<input type="hidden" name="var_id" value="<?php echo JRequest::getVar('var_id');?>"/>
					<input type="hidden" name="criteria_id" value="<?php echo JRequest::getVar('criteria_id');?>"/>
					<input type="hidden" name="filter_order" value="<?php if($filter_order) echo $filter_order; ?>" />
					<input type="hidden" name="filter_order_Dir" value="<?php if($filter_order_Dir) echo $filter_order_Dir; ?>" />
					<input type="hidden" name="user_selected" id="user_selected" value=""/>
					<table class="table table-striped table-hover table-bordered" >
					<thead>
						<tr>
							<th><?php echo JText::_( 'COM_AWARDPACKAGE_ID_FIELD' ); ?></th>
							<th><?php echo JHTML::_( 'grid.sort', JText::_( 'COM_AWARDPACKAGE_NAME' ), 'firstname', @$this->lists['order_dir'], @$this->lists['order']); ?></th>							
							<th><?php echo JHTML::_( 'grid.sort', JText::_( 'COM_AWARDPACKAGE_EMAIL' ), 'email', @$this->lists['order_dir'], @$this->lists['order']); ?></th>
					        <th><?php echo JText::_('Birthday'); ?></th>
					        <th><?php echo JText::_('Gender'); ?></th>
					        <th><?php echo JText::_('Street'); ?></th>
					       
					        <th><?php echo JHTML::_( 'grid.sort', JText::_( 'Country' ), 'country', @$this->lists['order_dir'], @$this->lists['order']); ?></th>					        	
						</tr>
					</thead>
					<tbody>
						<?php
							if (count( $this->registeredUser) > 0) {
								foreach ($this->registeredUser as $v1) {
									?>
							<tr>
								<td><?php echo $v1->id; ?></td>
								<td><?php echo $v1->firstname; ?> <?php echo $v1->lastname; ?></td>
								<td><?php echo $v1->email; ?></td>
								<td><?php echo $v1->birthday; ?></td>
								<td><?php echo $v1->gender; ?></td>
								<td><?php echo $v1->street; ?></td>
								
								<td><?php echo $v1->country; ?></td>
							</tr>
							<?php
								}
							}
						?>
					</tbody>
				</table>
					<div style="float: right;"><?php echo $this->pagination->getListFooter();?></div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php  
	$varc=JRequest::getVar('criteria_id');
	if (!empty($varc)) {
			$cr=JRequest::getVar('criteria_id');
		}
	 	else {
	 		$cr= '0'; 
	   } ?>








