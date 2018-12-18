<?php
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
//JHtml::_('behavior.formvalidation');

$cid = JRequest::getVar("cid");

?>
<script type="text/javascript">
function isNumberKey(evt)
{	
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
    return false;
    return true;
}
/*
Joomla.submitbutton = function(task)
{
    if (task == 'cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
        Joomla.submitform(task, document.getElementById('adminForm'));
    }
    else{
        alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
    }
}
*/
</script>


<?php
JHtml::_('jquery.framework', false);
$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::base().'components/com_searchengine/assets/css/jquery.ui.all.css');
//$doc->addScript(JURI::base().'components/com_searchengine/assets/js/jquery.min.js');
$doc->addScript(JURI::base().'components/com_searchengine/assets/js/jquery.ui.datepicker.js');
$doc->addScript(JURI::base().'components/com_searchengine/assets/js/jquery.ui.core.js');
$doc->addScript(JURI::base().'components/com_searchengine/assets/js/jquery.ui.widget.js');

//$startDate = $this->start_date!=''? date('Y-m-d', strtotime($this->start_date)): '';
//$endDate = $this->end_date!=''? date('Y-m-d', strtotime($this->end_date)): '';
?>
<script src="<?php echo $baseurl . 'components/com_searchengine/js/jquery.validate.min.js';?>"></script>
<script>
	jQuery(document).ready(function() {
		// validate the comment form when it is submitted
		jQuery("#adminForm").validate();
});	
	</script>
	<style>
.error {
    font-weight: bold;
    color: red;
}
label {
    display: block;
    margin-bottom: 5px;
}
	</style>
<style>
.topmenu {
    margin-bottom: 20px;
}

.topmenu a {
    color: #000000;
    line-height: 35px;
    padding: 5px;
    text-decoration: none;
}
.topmenu a.active{
	background-color:#ccc;
	color:#000;
	padding:5px 10px;
	text-decoration:none;
}



.table.table-striped .actions a {
	border: 2px solid #ccc;
	color: #000;
	float: right;
	font-weight: bold;
	margin-left: 10px;
	padding: 5px 18px;
	text-decoration: none;
}

</style>
<div id="cj-wrapper">
    <div class="survey-wrapper nospace-left no-space-left no-space-right">
        <div class="row-fluid">
            <?php echo $this->loadTemplate('nav');?>
            <div id="j-main-container" class="span10">
                <form action="index.php?option=com_searcengine&view=dashboard&task=dashobard.save" method="post" name="adminForm" class="form-validate" id="adminForm">
				
<div class="navbar">
	<div class="navbar-inner" style="background-color: #fafafa;">	
		<div class="header-container">
			<div class="cq-nav-collapse nav-collapse collapse">
				<ul class="nav">					
					<li>
						<?php /*
						<button class="btn margin-bottom-10" type="button" onclick=" Joomla.submitbutton('save');"><i class="fa fa-times"></i> <?php echo JText::_('Save & Close');?></button> */ ?>
						<button class="btn margin-bottom-10" type="submit" value="Submit" /><i class="fa fa-times"></i> <?php echo JText::_('Save & Close');?></button>		
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=dashboard');?>">
							<?php echo JText::_('Cancel');?>														
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

                    <div id="wrapper">
					<div class="span12 topmenu">
					<div class="selection">
						<a class="active" href="javascript:void(0)" id="tab1">Reward Profile</a>
						<a href="javascript:void(0)" id="tab2">Search engine user group</a>
						<a href="javascript:void(0)" id="tab3">Keyword group</a>
						<a href="javascript:void(0)" id="tab4">Url reward list</a>
						<a href="javascript:void(0)" id="tab5">Survey group</a>
						<a href="javascript:void(0)" id="tab6">Quiz group</a>
						<a href="javascript:void(0)" id="tab7">Utility label group</a>
					</div>
					</div>
                        <br/><br/>
                        <div class="span10 tab1 tabsdata" id="tab_rewards_profile">
                            
                            <table>
                                <tr>
                                    <td style="padding-left: 20px;"><?php echo JText::_('User/Creator');?></td>        
                                    <td>
                                        <div style="width: 20px;"></div>                        
                                    </td>
                                    <td>
									<div class="clearfix">
								<input type="hidden" name="userid" id="userid"
									value="<?php echo $this->item->created_by?>" />
								<input type="text" class="input-medium"
									name="xxx_userid" id="userid_visible"
									value="<?php echo $this->item->username;?>" disabled="disabled" />
								<input type="hidden" 
									name="user_id"
									value="<?php echo $this->item->created_by;?>" />
								
								</div>
									
									
									
									</td>
                                </tr>   

                                <tr>
                                    <td style="padding-left: 20px;"><?php echo JText::_('User Email');?></td>        
                                    <td>
                                        <div style="width: 20px;"></div>                        
                                    </td>
                                    <td><div class="clearfix">
								
								<input type="text" class="input-medium"
									name="xxx_userid" id="userid_visible"
									value="<?php echo $this->item->email;?>" disabled="disabled" /></td>
                                </tr> 

                                <tr>
                                    <td style="padding-left: 20px;"><?php echo JText::_('Reward Name');
									
									?></td>        
                                    <td>
                                        <div style="width: 20px;"></div>                        
                                    </td>
                                    <td><input type="text" id="reward_name" name="reward_name" data-rule-required="true" data-msg-required="This field is required" value="<?php if(!empty($this->serl_details[0]->title)) echo $this->serl_details[0]->title;?>" class="required" onkeypress=""/></td>
                                </tr> 

                                <tr>
                                    <td style="padding-left: 20px;"><?php echo JText::_('Publish Start Date');?></td>        
                                    <td>
                                        <div style="width: 20px;"></div>                        
                                    </td>
                                    <td>
									
									<input type="date" id="publish_start_date" name="publish_start_date" data-rule-required="true" data-msg-required="This field is required" value="<?php if(!empty($this->serl_details[0]->startpublishdate)) echo $this->serl_details[0]->startpublishdate;?>" class="required" onkeypress=""/>
									</td>
                                </tr> 

                                <tr>
                                    <td style="padding-left: 20px;"><?php echo JText::_('Publish End Date');?></td>        
                                    <td>
                                        <div style="width: 20px;"></div>                        
                                    </td>
                                    <td><input type="date" id="publish_end_date" name="publish_end_date" data-rule-required="true" data-msg-required="This field is required" value="<?php if(!empty($this->serl_details[0]->endpublishdate)) echo $this->serl_details[0]->endpublishdate;?>" class="required" onkeypress=""/></td>
                                </tr> 
									<script>
										jQuery('#publish_start_date,#publish_end_date').datepicker({
											showOn: "button",
											buttonImage: "<?php echo JURI::base().'components/com_searchengine/assets/css/images/calendar.gif';?>",
											dateFormat: 'yy-mm-dd',		
											numberOfMonths: 1
										});
										
									</script>
                                <tr>
                                    <td style="padding-left: 20px;"><?php echo JText::_('Description');?></td>        
                                    <td>
                                        <div style="width: 20px;"></div>                        
                                    </td>
                                    <td><textarea id="sedescription" name="sedescription" value="" class="required" onkeypress=""/><?php if(!empty($this->serl_details[0]->sedescription)) echo trim($this->serl_details[0]->sedescription);?></textarea></td>
                                </tr> 

                            </table>
                            <br />
                            <br />
                        </div>
						<div class="span10 tab2 tabsdata" id="tab_search_engine_user_group" style="display:none;">
							<table align="center" border="0" class="table table-striped" width="70%">
								<thead>									
									<tr class="actions">
										<input type="hidden" name="group-id[]" value="1">
										<td colspan="3" align="right"><a href="javascript:void(0)" class="deleteAll">Delete</a><a href="#" data-toggle="modal" data-target="#myModalseug" data-group-id="1">Add</a></td>
									</tr>
								</thead>
							</table>
							<table id="tblseug" align="center" border="0" class="table table-striped" style="width:100%;">
								<thead>
									<tr style="text-align:center; background-color:#CCCCCC">									
										<th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?></th>
										<th><?php echo JText::_('Search engine user group');?></th>
										<th><?php echo JText::_('Created Date');?></th>	

									</tr>
								</thead>
								<tbody><?php
									if(!empty($this->serl_details[0]->usergroupfull))
									{										
										$data_value=json_decode($this->serl_details[0]->usergroupfull);		
										$data_value=trim($data_value,'"'); 
										$data1=explode(',',$data_value);
									
									?>
										<tr>
										<td><input type="checkbox" value="<?php echo $data_value; ?>" name="chkseug[]"><input type="hidden" value="<?php echo $data_value; ?>" name="hdnseugfull"><input type='hidden' name='hdnseugid' value="<?php echo $this->serl_details[0]->usergroup; ?>"></td>
										<td><?php echo $data1[1]; ?></td>	
										<td><?php echo $data1[2]; ?></td>	
										</tr>										
									<?php
										}									
									?>	</tbody>
							</table>
							<br><br>
							Description<br><textarea id="seugdesc" name="seugdesc"><?php if(!empty($this->serl_details[0]->usergroupdesc)) echo trim($this->serl_details[0]->usergroupdesc);?></textarea>
						</div>
						<div class="span10 tab3 tabsdata" id="tab_keyword_group" style="display:none;">
							<table align="center" border="0" class="table table-striped" width="70%">
								<thead>									
									<tr class="actions">
										<input type="hidden" name="group-id[]" value="1">
										<td colspan="3" align="right"><a href="javascript:void(0)" class="deleteAll">Delete</a><a href="#" data-toggle="modal" data-target="#myModalkg" data-group-id="1">Add</a></td>
									</tr>
								</thead>
							</table>
							<table id="tblkg" align="center" border="0" class="table table-striped" style="width:100%;">
								<thead>
									<tr style="text-align:center; background-color:#CCCCCC">									
										<th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?></th>
										<th><?php echo JText::_('Keyword group');?></th>
										<th><?php echo JText::_('Created Date');?></th>	
										<th><?php echo JText::_('Keywords');?></th>	
									</tr>
									
								</thead>
								<tbody><?php
									if(!empty($this->serl_details[0]->keywordgroupfull))
									{										
										$data_value=json_decode($this->serl_details[0]->keywordgroupfull);		
										$data_value=trim($data_value,'"'); 
										$data1=explode(',',$data_value);
									
									?>
										<tr>
										<td><input type="checkbox" value="<?php echo $data_value; ?>" name="chkkg[]"><input type="hidden" value="<?php echo $data_value; ?>" name="hdnkgfull"><input type='hidden' name='hdnkgid' value="<?php echo $this->serl_details[0]->keywordgroup; ?>"></td>
										<td><?php echo $data1[1]; ?></td>	
										<td><?php echo $data1[2]; ?></td>
										<td><?php echo $data1[3]; ?></td>
										</tr>										
									<?php
										}									
									?>		</tbody>
							</table>
							<br><br>
							Description<br><textarea id="kgdesc" name="kgdesc"><?php if(!empty($this->serl_details[0]->keywordgroupdesc)) echo trim($this->serl_details[0]->keywordgroupdesc);?></textarea>
						</div>
						<div class="span10 tab4 tabsdata" id="tab_url_group" style="display:none;">
							<table align="center" border="0" class="table table-striped" width="70%">
								<thead>									
									<tr class="actions">
										<input type="hidden" name="group-id[]" value="1">
										<td colspan="3" align="right"><a href="javascript:void(0)" class="deleteAll">Delete</a><a href="#" data-toggle="modal" data-target="#myModalug" data-group-id="1">Add</a></td>
									</tr>
								</thead>
							</table>
							<table id="tblug" align="center" border="0" class="table table-striped" style="width:100%;">
								<thead>
									<tr style="text-align:center; background-color:#CCCCCC">									
										<th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?></th>
										<th><?php echo JText::_('Url reward list');?></th>
										<th><?php echo JText::_('Created Date');?></th>	
										<th><?php echo JText::_('Urls');?></th>	
									</tr>
									
								</thead>
								<tbody><?php
									if(!empty($this->serl_details[0]->urlgroupfull))
									{										
										$data_value=json_decode($this->serl_details[0]->urlgroupfull);		
										$data_value=trim($data_value,'"'); 
										$data1=explode(',',$data_value);
									
									?>
										<tr>
										<td><input type="checkbox" value="<?php echo $data_value; ?>" name="chkug[]"><input type="hidden" value="<?php echo $data_value; ?>" name="hdnugfull"><input type='hidden' name='hdnugid' value="<?php echo $this->serl_details[0]->urlgroup; ?>"></td>
										<td><?php echo $data1[1]; ?></td>	
										<td><?php echo $data1[2]; ?></td>
										<td><?php echo $data1[3]; ?></td>
										</tr>										
									<?php
										}									
									?>		</tbody>
							</table>
							<br><br>
							Description<br><textarea id="ugdesc" name="ugdesc"><?php if(!empty($this->serl_details[0]->urlgroupdesc)) echo trim($this->serl_details[0]->urlgroupdesc);?></textarea>
						</div>
						<div class="span10 tab5 tabsdata" id="tab_survey_group" style="display:none;">
							<table align="center" border="0" class="table table-striped" width="70%">
								<thead>									
									<tr class="actions">
										<input type="hidden" name="group-id[]" value="1">
										<td colspan="3" align="right"><a href="javascript:void(0)" class="deleteAll">Delete</a><a href="#" data-toggle="modal" data-target="#myModalsg" data-group-id="1">Add</a></td>
									</tr>
								</thead>
							</table>
							<table id="tblsg" align="center" border="0" class="table table-striped" style="width:100%;">
								<thead>
									<tr style="text-align:center; background-color:#CCCCCC">									
										<th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?></th>
										<th><?php echo JText::_('Survey group');?></th>
										<th><?php echo JText::_('Created Date');?></th>	
										<th><?php echo JText::_('Surveys');?></th>	
									</tr>
									
								</thead>
								<tbody>	<?php
									if(!empty($this->serl_details[0]->surveygroupfull))
									{										
										$data_value=json_decode($this->serl_details[0]->surveygroupfull);		
										$data_value=trim($data_value,'"'); 
										$data1=explode(',',$data_value);
									
									?>
										<tr>
										<td><input type="checkbox" value="<?php echo $data_value; ?>" name="chksg[]"><input type="hidden" value="<?php echo $data_value; ?>" name="hdnsgfull"><input type='hidden' name='hdnsgid' value="<?php echo $this->serl_details[0]->surveygroup; ?>"></td>
										<td><?php echo $data1[1]; ?></td>	
										<td><?php echo $data1[2]; ?></td>
										<td><?php echo $data1[3]; ?></td>
										</tr>										
									<?php
										}									
									?>	</tbody>
							</table>
							<br><br>
							Description<br><textarea id="sgdesc" name="sgdesc"><?php if(!empty($this->serl_details[0]->surveygroupdesc)) echo trim($this->serl_details[0]->surveygroupdesc);?></textarea>
						</div>
						<div class="span10 tab6 tabsdata" id="tab_quiz_group" style="display:none;">
							<table align="center" border="0" class="table table-striped" width="70%">
								<thead>									
									<tr class="actions">
										<input type="hidden" name="group-id[]" value="1">
										<td colspan="3" align="right"><a href="javascript:void(0)" class="deleteAll">Delete</a><a href="#" data-toggle="modal" data-target="#myModalqg" data-group-id="1">Add</a></td>
									</tr>
								</thead>
							</table>
							<table id="tblqg" align="center" border="0" class="table table-striped" style="width:100%;">
								<thead>
									<tr style="text-align:center; background-color:#CCCCCC">									
										<th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?></th>
										<th><?php echo JText::_('Quiz group');?></th>
										<th><?php echo JText::_('Created Date');?></th>	
										<th><?php echo JText::_('Quizzes');?></th>	
									</tr>
									
								</thead>
								<tbody><?php
									if(!empty($this->serl_details[0]->quizgroupfull))
									{										
										$data_value=json_decode($this->serl_details[0]->quizgroupfull);		
										$data_value=trim($data_value,'"'); 
										$data1=explode(',',$data_value);
									
									?>
										<tr>
										<td><input type="checkbox" value="<?php echo $data_value; ?>" name="chkqg[]"><input type="hidden" value="<?php echo $data_value; ?>" name="hdnqgfull"><input type='hidden' name='hdnqgid' value="<?php echo $this->serl_details[0]->quizgroup; ?>"></td>
										<td><?php echo $data1[1]; ?></td>	
										<td><?php echo $data1[2]; ?></td>
										<td><?php echo $data1[3]; ?></td>
										</tr>										
									<?php
										}									
									?>		</tbody>
							</table>
							<br><br>
							Description<br><textarea id="qgdesc" name="qgdesc"><?php if(!empty($this->serl_details[0]->quizgroupdesc)) echo trim($this->serl_details[0]->quizgroupdesc);?></textarea>
						</div>
						<div class="span10 tab7 tabsdata" id="tab_utilitylabel_group" style="display:none;">
						Utility Label Group
						</div>
                        <br />
                    </div>
                    <input type="hidden" name="package_id" value="<?php echo $this->packageId;?>">  
					<input type="hidden" name="cid" id="cid" value="<?php echo $cid; ?>"/>  
                    <input type="hidden" name="option" value="com_searchengine" />
                    <input type="hidden" name="task" value="" />
                    <input type="hidden" name="controller" value="dashboard" />
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal seug -->
			  <div class="modal fade" id="myModalseug" role="dialog" style="display:none;">
				<div class="modal-dialog modal-sm">
				  <div class="modal-content">
					<div class="modal-header">
					  <button type="button" class="close" data-dismiss="modal">&times;</button>
					  <h4 class="modal-title">Search engine user group</h4>
					</div>
					<div class="modal-body" style="overflow-y:scroll">
						<table class="table table-striped">
							<thead>
								<tr style="text-align:center; background-color:#CCCCCC">
										<th></th>
										<th><?php echo JText::_('Search engine user group');?></th>
										<th><?php echo JText::_('Created')?></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($this->seuglist as $i=>$seuglist) { 
$seugtitle = $seuglist->title==''? 'User Group '.$seuglist->id:$seuglist->title;
?>
								<tr class="modaltrseug" class="row<?php echo $i%2;?>">
									<td><input type="radio" name="chkseug_id[]" class="dayChkbox" value="<?php echo $seuglist->id."-".$seugtitle."-".date('d M Y',strtotime($seuglist->created)); ?>"></td>
									<td align="center"><?php echo $seugtitle; ?></td>
									<td align="center"><?php echo date('d M Y',strtotime($seuglist->created));?></td>					
								</tr>
								<?php } ?>
							</tbody>							
						</table>
					</div>
					<div class="modal-footer">
						<input type="hidden" name="box-id" value="" class="box-id">
					   <button type="button" class="btn btn-default" id="btnsaveseuglist">Save</button><button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				  </div>
				</div>
			  </div>
<!-- Modal kg -->
			  <div class="modal fade" id="myModalkg" role="dialog" style="display:none;">
				<div class="modal-dialog modal-sm">
				  <div class="modal-content">
					<div class="modal-header">
					  <button type="button" class="close" data-dismiss="modal">&times;</button>
					  <h4 class="modal-title">Keyword group list</h4>
					</div>
					<div class="modal-body" style="overflow-y:scroll">
						<table class="table table-striped">
							<thead>
								<tr style="text-align:center; background-color:#CCCCCC">
										<th></th>
										<th><?php echo JText::_('Keyword group');?></th>
										<th><?php echo JText::_('Created')?></th>
										<th><?php echo JText::_('Keywords')?></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($this->kglist as $i=>$kglist) { ?>
								<tr class="modaltrkg" class="row<?php echo $i%2;?>">
									<td><input type="radio" name="chkkg_id[]" class="dayChkbox" value="<?php echo $kglist->id."-".$kglist->title."-".date('d M Y',strtotime($kglist->created))."-".$kglist->count1; ?>"></td>
									<td align="center"><?php echo $kglist->title;?></td>
									<td align="center"><?php echo date('d M Y',strtotime($kglist->created));?></td>
									<td align="center"><?php echo $kglist->count1;?></td>
								</tr>
								<?php } ?>
							</tbody>							
						</table>
					</div>
					<div class="modal-footer">
						<input type="hidden" name="box-id" value="" class="box-id">
					   <button type="button" class="btn btn-default" id="btnsavekglist">Save</button><button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				  </div>
				</div>
			  </div>
<!-- Modal ug -->
			  <div class="modal fade" id="myModalug" role="dialog" style="display:none;">
				<div class="modal-dialog modal-sm">
				  <div class="modal-content">
					<div class="modal-header">
					  <button type="button" class="close" data-dismiss="modal">&times;</button>
					  <h4 class="modal-title">Url reward list</h4>
					</div>
					<div class="modal-body" style="overflow-y:scroll">
						<table class="table table-striped">
							<thead>
								<tr style="text-align:center; background-color:#CCCCCC">
										<th></th>
										<th><?php echo JText::_('Url reward list');?></th>
										<th><?php echo JText::_('Created')?></th>
										<th><?php echo JText::_('Urls')?></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($this->uglist as $i=>$uglist) { ?>
								<tr class="modaltrug" class="row<?php echo $i%2;?>">
									<td><input type="radio" name="chkug_id[]" class="dayChkbox" value="<?php echo $uglist->id."-".$uglist->title."-".date('d M Y',strtotime($uglist->created))."-".$uglist->count1; ?>"></td>
									<td align="center"><?php echo $uglist->title;?></td>
									<td align="center"><?php echo date('d M Y',strtotime($uglist->created));?></td>
									<td align="center"><?php echo $uglist->count1;?></td>
								</tr>
								<?php } ?>
							</tbody>							
						</table>
					</div>
					<div class="modal-footer">
						<input type="hidden" name="box-id" value="" class="box-id">
					   <button type="button" class="btn btn-default" id="btnsaveuglist">Save</button><button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				  </div>
				</div>
			  </div>
<!-- Modal sg -->
			  <div class="modal fade" id="myModalsg" role="dialog" style="display:none;">
				<div class="modal-dialog modal-sm">
				  <div class="modal-content">
					<div class="modal-header">
					  <button type="button" class="close" data-dismiss="modal">&times;</button>
					  <h4 class="modal-title">Survey group list</h4>
					</div>
					<div class="modal-body" style="overflow-y:scroll">
						<table class="table table-striped">
							<thead>
								<tr style="text-align:center; background-color:#CCCCCC">
										<th></th>
										<th><?php echo JText::_('Survey group');?></th>
										<th><?php echo JText::_('Created')?></th>
										<th><?php echo JText::_('Surveys')?></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($this->sglist as $i=>$sglist) { ?>
								<tr class="modaltrsg" class="row<?php echo $i%2;?>">
									<td><input type="radio" name="chksg_id[]" class="dayChkbox" value="<?php echo $sglist->id."-".$sglist->title."-".date('d M Y',strtotime($sglist->created))."-".$sglist->count1; ?>"></td>
									<td align="center"><?php echo $sglist->title;?></td>
									<td align="center"><?php echo date('d M Y',strtotime($sglist->created));?></td>
									<td align="center"><?php echo $sglist->count1;?></td>								
								</tr>
								<?php } ?>
							</tbody>							
						</table>
					</div>
					<div class="modal-footer">
						<input type="hidden" name="box-id" value="" class="box-id">
					   <button type="button" class="btn btn-default" id="btnsavesglist">Save</button><button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				  </div>
				</div>
			  </div>			  
<!-- Modal qg -->
			  <div class="modal fade" id="myModalqg" role="dialog" style="display:none;">
				<div class="modal-dialog modal-sm">
				  <div class="modal-content">
					<div class="modal-header">
					  <button type="button" class="close" data-dismiss="modal">&times;</button>
					  <h4 class="modal-title">Quiz group list</h4>
					</div>
					<div class="modal-body" style="overflow-y:scroll">
						<table class="table table-striped">
							<thead>
								<tr style="text-align:center; background-color:#CCCCCC">
										<th></th>
										<th><?php echo JText::_('Quiz group');?></th>
										<th><?php echo JText::_('Created')?></th>
										<th><?php echo JText::_('Quizzes')?></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($this->qglist as $i=>$qglist) { ?>
								<tr class="modaltrqg" class="row<?php echo $i%2;?>">
									<td><input type="radio" name="chkqg_id[]" class="dayChkbox" value="<?php echo $qglist->id."-".$qglist->title."-".date('d M Y',strtotime($qglist->created))."-".$qglist->count1; ?>"></td>
									<td align="center"><?php echo $qglist->title;?></td>
									<td align="center"><?php echo date('d M Y',strtotime($qglist->created));?></td>
									<td align="center"><?php echo $qglist->count1;?></td>									
								</tr>
								<?php } ?>
							</tbody>							
						</table>
					</div>
					<div class="modal-footer">
						<input type="hidden" name="box-id" value="" class="box-id">
					   <button type="button" class="btn btn-default" id="btnsaveqglist">Save</button><button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				  </div>
				</div>
			  </div>			  			  
<script>
	jQuery(document).ready(function(){
		
		jQuery(".selection a").click(function(){
			var seldiv = jQuery(this).attr("id");
			if(seldiv != "")
			{
				jQuery(".tabsdata").hide();
				jQuery("."+seldiv).show();
			}		
			else{
				jQuery(".tabsdata").hide();
				jQuery(".tab1").show();				
			}
			
			jQuery(".selection").find("a").removeClass("active");
			jQuery(this).addClass("active");
			
		});
		
		jQuery(document).on("click", "a[data-toggle=modal]", function () {
			 var groupId = jQuery(this).data('group-id');
			 jQuery(".modal-footer").find(".box-id").val(groupId);
		});
		
		
		jQuery(".hasTooltip").click(function(){
			
			if(this.checked) { // check select status
				jQuery(this).parents().closest("table").find("tbody tr input[type='checkbox']").each(function(index, element){
					this.checked = true;  //select all checkboxes with class "checkbox1"              
				});
			}else{
				jQuery(this).parents().closest("table").find("tbody tr input[type='checkbox']").each(function(index, element){
					this.checked = false; //deselect all checkboxes with class "checkbox1"                      
				});        
			}
				
		});		
		
	});

	jQuery(document).on('click','.deleteAll',function(){
						
			jQuery(this).parents().closest("table").next("table").find("tbody tr input[type='checkbox']").each(function(index, element){
				if(this.checked){
					jQuery(element).parent().parent().remove();
				}            
			});
				
		});
		
		
		jQuery(document).on("click","#btnsaveseuglist",function(){
			var selectedVal = "";
			var selected = jQuery(".modaltrseug input[type='radio']:checked");
			if (selected.length > 0) {
				
				selectedVal = selected.val();
				//alert(selectedVal);
				
				selectedVal = selectedVal.split("-");
				var boxid = jQuery(this).parent().find("input").val();
				//alert(boxid);
				
				jQuery('#tblseug tbody tr:last').remove();
				jQuery("#tab_search_engine_user_group").find("#tblseug tbody").append("<tr><td><input type='checkbox' name='chkseug[]' class='chkseug"+boxid+"' value='"+selectedVal[0]+"'><input type='hidden' name='hdnseugfull' value='"+selectedVal[0]+","+selectedVal[1]+","+selectedVal[2]+"'><input type='hidden' name='hdnseugid' value='"+selectedVal[0]+"'></td><td>"+selectedVal[1]+"</td><td>"+selectedVal[2]+"</td></tr>");
				jQuery('#myModalseug').modal('hide') 
			}
			else{
				alert("Please select any search engine user group.")
			}
		});
		
		
		jQuery(document).on("click","#btnsavekglist",function(){
			var selectedVal = "";
			var selected = jQuery(".modaltrkg input[type='radio']:checked");
			if (selected.length > 0) {
				
				selectedVal = selected.val();
				//alert(selectedVal);
				
				selectedVal = selectedVal.split("-");
				var boxid = jQuery(this).parent().find("input").val();
				//alert(boxid);
				
				jQuery('#tblkg tbody tr:last').remove();
				jQuery("#tab_keyword_group").find("#tblkg tbody").append("<tr><td><input type='checkbox' name='chkkg[]' class='chkkg"+boxid+"' value='"+selectedVal[0]+"'><input type='hidden' name='hdnkgfull' value='"+selectedVal[0]+","+selectedVal[1]+","+selectedVal[2]+","+selectedVal[3]+"'><input type='hidden' name='hdnkgid' value='"+selectedVal[0]+"'></td><td>"+selectedVal[1]+"</td><td>"+selectedVal[2]+"</td><td>"+selectedVal[3]+"</td></tr>");
				jQuery('#myModalkg').modal('hide') 
			}
			else{
				alert("Please select any keyword group.")
			}
		});
		
		jQuery(document).on("click","#btnsaveuglist",function(){
			var selectedVal = "";
			var selected = jQuery(".modaltrug input[type='radio']:checked");
			if (selected.length > 0) {
				
				selectedVal = selected.val();
				//alert(selectedVal);
				
				selectedVal = selectedVal.split("-");
				var boxid = jQuery(this).parent().find("input").val();
				//alert(boxid);
				
				jQuery('#tblug tbody tr:last').remove();
				jQuery("#tab_url_group").find("#tblug tbody").append("<tr><td><input type='checkbox' name='chkug[]' class='chkug"+boxid+"' value='"+selectedVal[0]+"'><input type='hidden' name='hdnugfull' value='"+selectedVal[0]+","+selectedVal[1]+","+selectedVal[2]+","+selectedVal[3]+"'><input type='hidden' name='hdnugid' value='"+selectedVal[0]+"'></td><td>"+selectedVal[1]+"</td><td>"+selectedVal[2]+"</td><td>"+selectedVal[3]+"</td></tr>");
				jQuery('#myModalug').modal('hide') 
			}
			else{
				alert("Please select any keyword group.")
			}
		});
		
		
		jQuery(document).on("click","#btnsavesglist",function(){
			var selectedVal = "";
			var selected = jQuery(".modaltrsg input[type='radio']:checked");
			if (selected.length > 0) {
				
				selectedVal = selected.val();
				//alert(selectedVal);
				
				selectedVal = selectedVal.split("-");
				var boxid = jQuery(this).parent().find("input").val();
				//alert(boxid);
				
				jQuery('#tblsg tbody tr:last').remove();
				jQuery("#tab_survey_group").find("#tblsg tbody").append("<tr><td><input type='checkbox' name='chksg[]' class='chksg"+boxid+"' value='"+selectedVal[0]+"'><input type='hidden' name='hdnsgfull' value='"+selectedVal[0]+","+selectedVal[1]+","+selectedVal[2]+","+selectedVal[3]+"'><input type='hidden' name='hdnsgid' value='"+selectedVal[0]+"'></td><td>"+selectedVal[1]+"</td><td>"+selectedVal[2]+"</td><td>"+selectedVal[3]+"</td></tr>");
				jQuery('#myModalsg').modal('hide') 
			}
			else{
				alert("Please select any survey group.")
			}
		});
		
		
		jQuery(document).on("click","#btnsaveqglist",function(){
			var selectedVal = "";
			var selected = jQuery(".modaltrqg input[type='radio']:checked");
			if (selected.length > 0) {
				
				selectedVal = selected.val();
				//alert(selectedVal);
				
				selectedVal = selectedVal.split("-");
				var boxid = jQuery(this).parent().find("input").val();
				//alert(boxid);
				
				jQuery('#tblqg tbody tr:last').remove();
				jQuery("#tab_quiz_group").find("#tblqg tbody").append("<tr><td><input type='checkbox' name='chkqg[]' class='chkqg"+boxid+"' value='"+selectedVal[0]+"'><input type='hidden' name='hdnqgfull' value='"+selectedVal[0]+","+selectedVal[1]+","+selectedVal[2]+","+selectedVal[3]+"'><input type='hidden' name='hdnqgid' value='"+selectedVal[0]+"'></td><td>"+selectedVal[1]+"</td><td>"+selectedVal[2]+"</td><td>"+selectedVal[3]+"</td></tr>");
				jQuery('#myModalqg').modal('hide') 
			}
			else{
				alert("Please select any quiz group.")
			}
		});
		

	function checkAll(self, elem){
		jQuery('.'+elem).prop('checked',jQuery('#'+self).prop('checked'));
	}
</script>
