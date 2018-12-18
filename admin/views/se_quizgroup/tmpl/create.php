<?php
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

//echo "aaa";
//print_r(JRequest::getVar("cid"));
//echo $this->is_edit."bbb";
$quizgroup_title = "";
$quizs_model = JModelLegacy::getInstance( 'quizs', 'SearchEngineModel' );
$quizs = $quizs_model->get_quizs(array(), 20, 0, -1);

$quizgroup_quiz_ids = array();

if ($this->is_edit == true) {	
    $cid = JRequest::getVar("cid");
   
    $model =& JModelLegacy::getInstance('se_quizgroup','SearchEngineModel');
    $quizgroup_title = $model->get_quizgroup_title($cid)[0]->title;  
	
    
    $quizgroup_quizs = $model->get_quizgroup_quizs($cid);  

	foreach($quizgroup_quizs as $quizgroup_quiz)
	{
		$quizgroup_quiz_ids[] = $quizgroup_quiz->quiz_id;
	}

	//print_r($quizgroup_quiz_ids);
	
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
    <div class="quiz-wrapper nospace-left no-space-left no-space-right">
        <div class="row-fluid">
            <?php echo $this->loadTemplate('nav');?>
            <div id="j-main-container" class="span10">
                <form action="index.php?option=com_searcengine&view=se_quizgroup&task=se_quizgroup.save" method="post" name="adminForm" class="form-validate" id="adminForm">
                    <div id="wrapper">
                        <br/><br/>
                        <div class="tabsContent"> 
                            
                            <table>
                                <tr>
                                    <td style="padding-left: 20px;"><?php echo JText::_('Quiz Group Name');?></td>        
                                    <td>
                                        <div style="width: 20px;"></div>                        
                                    </td>
                                    <td><input type="text" id="quiz_group_name" name="quiz_group_name" value="<?php echo $quizgroup_title;?>" class="required"  onkeypress=""/></td>
                                </tr>  
                            </table>
                            
                            <br />
                            <br />
                        </div>
                        <br />
						
						
					
						<table id="tblquizs" align="center" border="0" class="table table-hover table-striped">
						<thead>
                                <tr style="background-color:#cccccc;">
                                    <th style="padding: 10px;text-align:center;">
									<input name="checkall-toggle" value="on" title="Check All" onclick="Joomla.checkAll(this)" type="checkbox">
									</th>        
                                    <th style="padding: 10px;text-align:center;">
                                        <div class="serialnumber" style="width: 20px;">#</div>                        
                                    </th>
                                    <th style="padding: 10px;text-align:center;">Quiz</th>
                                </tr>
</thead>
								
								<?php 
								//print_r($quizs);
								foreach($quizs as $quiz){ ?>
								<tr>
                                    <td style="padding: 10px;text-align:center;"><input type="checkbox" id="cb<?php echo $i; ?>" name="chkquizs[]" value="<?php echo $quiz->id; ?>" <?php if (in_array($quiz->id, $quizgroup_quiz_ids)){ echo "checked"; } ?> onclick="Joomla.isChecked(this.checked);" /></td>        
                                    <td style="padding: 10px;text-align:center;">
                                        <div style="width: 20px;"><?php echo $i; ?></div>                        
                                    </td>
                                    <td style="padding: 10px;text-align:center;"><?php echo $quiz->title; ?></td>
                                </tr>								
								<?php  $i++; } ?>
							
                            </table>
                    </div>
                    <input type="hidden" name="package_id" value="<?php echo JRequest::getVar('package_id');?>">  
					<input type="hidden" name="cid" id="cid" value="<?php echo $cid; ?>"/>    
                    <input type="hidden" name="option" value="com_searchengine" />
                    <input type="hidden" name="task" value="" />
                    <input type="hidden" name="controller" value="se_quizgroup" />
					<input name="boxchecked" value="0" type="hidden">
                </form>
            </div>
        </div>
    </div>
</div>
