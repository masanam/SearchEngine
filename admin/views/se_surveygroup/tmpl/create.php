<?php
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

//echo "aaa";
//print_r(JRequest::getVar("cid"));
//echo $this->is_edit."bbb";
$surveygroup_title = "";
$surveys_model = JModelLegacy::getInstance( 'surveys', 'SearchEngineModel' );
$surveys = $surveys_model->get_surveys(array(), 20, 0, -1);

$surveygroup_survey_ids = array();

if ($this->is_edit == true) {	
    $cid = JRequest::getVar("cid");
   
    $model =& JModelLegacy::getInstance('se_surveygroup','SearchEngineModel');
    $surveygroup_title = $model->get_surveygroup_title($cid)[0]->title;  
	
    
    $surveygroup_surveys = $model->get_surveygroup_surveys($cid);  

	foreach($surveygroup_surveys as $surveygroup_survey)
	{
		$surveygroup_survey_ids[] = $surveygroup_survey->survey_id;
	}

	//print_r($surveygroup_survey_ids);
	
} 



$i=1;

?>
<script type="text/javascript">
function isNumberKey(evt)
{	
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
    return false;
    return true;
}

Joomla.submitbutton = function(task)
{
    if (task == 'cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
        Joomla.submitform(task, document.getElementById('adminForm'));
    }
    else{
        alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
    }
}
</script>
<div id="cj-wrapper">
    <div class="survey-wrapper nospace-left no-space-left no-space-right">
        <div class="row-fluid">
            <?php echo $this->loadTemplate('nav');?>
            <div id="j-main-container" class="span10">
                <form action="index.php?option=com_searcengine&view=se_surveygroup&task=se_surveygroup.save" method="post" name="adminForm" class="form-validate" id="adminForm">
                    <div id="wrapper">
                        <br/><br/>
                        <div class="tabsContent"> 
                            
                            <table>
                                <tr>
                                    <td style="padding-left: 20px;"><?php echo JText::_('Survey Group Name');?></td>        
                                    <td>
                                        <div style="width: 20px;"></div>                        
                                    </td>
                                    <td><input type="text" id="survey_group_name" name="survey_group_name" value="<?php echo $surveygroup_title;?>" class="required"  onkeypress=""/></td>
                                </tr>  
                            </table>
                            
                            <br />
                            <br />
                        </div>
                        <br />
						
						
						
						<table id="tblsurveys" align="center" border="0" class="table table-hover table-striped">
						<thead>
                                <tr style="background-color:#cccccc;">
                                    <th style="padding: 10px;text-align:center;">
									<input name="checkall-toggle" value="on" title="Check All" onclick="Joomla.checkAll(this)" type="checkbox">
									</th>        
                                    <th style="padding: 10px;text-align:center;">
                                        <div class="serialnumber" style="width: 20px;">#</div>                        
                                    </th>
                                    <th style="padding: 10px;text-align:center;">Survey</th>
                                </tr>
						</thead>
								
								<?php 
								//print_r($surveys);
								foreach($surveys as $survey){ ?>
								<tr>
                                    <td style="padding: 10px;text-align:center;"><input type="checkbox" id="cb<?php echo $i; ?>" name="chksurveys[]" value="<?php echo $survey->id; ?>" <?php if (in_array($survey->id, $surveygroup_survey_ids)){ echo "checked"; } ?> onclick="Joomla.isChecked(this.checked);" /></td>        
                                    <td style="padding: 10px;text-align:center;">
                                        <div style="width: 20px;"><?php echo $i; ?></div>                        
                                    </td>
                                    <td style="padding: 10px;text-align:center;"><?php echo $survey->title; ?></td>
                                </tr>								
								<?php  $i++; } ?>
							
                            </table>
                    </div>
                    <input type="hidden" name="package_id" value="<?php echo JRequest::getVar('package_id');?>">  
					<input type="hidden" name="cid" id="cid" value="<?php echo $cid; ?>"/>    
                    <input type="hidden" name="option" value="com_searchengine" />
                    <input type="hidden" name="task" value="" />
                    <input type="hidden" name="controller" value="se_surveygroup" />
					<input name="boxchecked" value="0" type="hidden">
                </form>
            </div>
        </div>
    </div>
</div>
