<?php
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
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
                <form action="index.php?option=com_searcengine&view=se_urlgroup&task=se_urlgroup.save" method="post" name="adminForm" class="form-validate" id="adminForm">
                    <div id="wrapper">
                        <br/><br/>
                        <div class="tabsContent"> 
                            <div class="judul" style="padding-left: 20px;">
                                <div class="judul" style="padding-left: 20px;"> Add New </div>
                            </div>
                            <table>
                                <tr>
                                    <td style="padding-left: 20px;"><?php echo JText::_('Utility Label Group Name');?></td>        
                                    <td>
                                        <div style="width: 20px;"></div>                        
                                    </td>
                                    <td><input type="text" id="survey_group_name" name="utility_label_group_name" value="" class="required hasTip"  onkeypress=""/></td>
                                </tr>  
                            </table>
                            <table>
                                <tr>
                                    <th style="padding-left: 20px;"></th>        
                                    <th style="padding-left: 20px;">
                                        <div style="width: 20px;">#</div>                        
                                    </th>
                                    <th style="padding-left: 20px;">Survey</th>
                                </tr> 
                                <tr>
                                    <td style="padding-left: 20px;"><input type="checkbox" id="" name="" value="" class="required hasTip"  onkeypress=""/></td>        
                                    <td style="padding-left: 20px;">
                                        <div style="width: 20px;">1</div>                        
                                    </td>
                                    <td style="padding-left: 20px;">www.xyz.com</td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 20px;"><input type="checkbox" id="" name="" value="" class="required hasTip"  onkeypress=""/></td>        
                                    <td style="padding-left: 20px;">
                                        <div style="width: 20px;">2</div>                        
                                    </td>
                                    <td style="padding-left: 20px;">www.xyz.com</td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 20px;"><input type="checkbox" id="" name="" value="" class="required hasTip"  onkeypress=""/></td>        
                                    <td style="padding-left: 20px;">
                                        <div style="width: 20px;">3</div>                        
                                    </td>
                                    <td style="padding-left: 20px;">www.xyz.com</td>
                                </tr>
                                

                            </table>
                            <br />
                            <br />
                        </div>
                        <br />
                    </div>
                    <input type="hidden" name="package_id" value="<?php echo JRequest::getVar('package_id');?>">  
                    <input type="hidden" name="option" value="com_searchengine" />
                    <input type="hidden" name="task" value="" />
                    <input type="hidden" name="controller" value="se_utilitylabelgroup" />
                </form>
            </div>
        </div>
    </div>
</div>
