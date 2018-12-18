<?php
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

//echo "aaa";
//print_r(JRequest::getVar("cid"));
//echo $this->is_edit."bbb";
$urlgroup_title = "";
if ($this->is_edit == true) {	
    $cid = JRequest::getVar("cid");
   
    $model =& JModelLegacy::getInstance('se_urlgroup','SearchEngineModel');
    $urlgroup_title = $model->get_urlgroup_title($cid)[0]->title;  
	
	$model =& JModelLegacy::getInstance('se_urlgroup','SearchEngineModel');
    $urlgroup_urls = $model->get_urlgroup_urls($cid);  
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
<style>


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
                <form action="index.php?option=com_searcengine&view=se_urlgroup&task=se_urlgroup.save" method="post" name="adminForm" class="form-validate" id="adminForm">
                    <div id="wrapper">
                        <br/><br/>
                        <div class="tabsContent"> 
                            
                            <table>
                                <tr>
                                    <td style="padding-left: 20px;"><?php echo JText::_('URL Group Name');?></td>        
                                    <td>
                                        <div style="width: 20px;"></div>                        
                                    </td>
                                    <td><input type="text" id="survey_group_name" name="url_group_name" value="<?php echo $urlgroup_title;?>" class="required"  onkeypress=""/></td>
                                </tr>  
                            </table>
                            
                            <br />
                            <br />
                        </div>
                        <br />
						
						<div class="judul" style="padding-left: 20px;">
						
						<table align="center" border="0" class="table table-striped" width="70%">
								<thead>									
									<tr class="actions">
										<input type="hidden" name="group-id[]" value="1">
										<td colspan="3" align="right"><a id="btnDeleteurltitle" onclick="funDeletetr()" href="javascript:void(0)" class="deleteAll">Delete</a><a id="btnAddurltitle" onclick="funAddtr()" href="javascript:void(0)">Add</a></td>
									</tr>
								</thead>
							</table>
							
                               
                            </div>
							<table align="center" border="0" class="table table-hover table-striped">
						
						<thead>
                                <tr style="text-align:center; background-color:#CCCCCC">		
                                    <th style="padding: 10px;text-align:center;">
									<input name="checkall-toggle" value="on" title="Check All" onclick="Joomla.checkAll(this)" type="checkbox">
									</th>        
                                    <th style="padding: 10px;text-align:center;">
                                        <div style="width: 20px;">#</div>                        
                                    </th>
                                    <th style="padding: 10px;text-align:center;">Urls</th>
                                </tr>
</thead>
<tbody id="tblurls">
								<?php if(count($urlgroup_urls) === 0){ ?>
                                <tr id="tr<?php echo $i; ?>">
                                    <td style="padding: 10px;text-align:center;">
								
									<input type="checkbox" id="cb<?php echo $i; ?>" name="urls[]" onclick="Joomla.isChecked(this.checked);" />
									</td>        
                                    <td style="padding: 10px;text-align:center;">
                                        <div style="width: 20px;">1</div>                        
                                    </td>
                                    <td style="padding: 10px;text-align:center;"><input style="margin-bottom:0px;" type="text" class="urllimitwords" id="textbox<?php echo $i; ?>" name="urlstitle[]"  /></td>
                                </tr>   
								<?php $i++;  }else{ ?>
								<?php 
								
								foreach($urlgroup_urls as $urlgroup_url){ ?>
								<tr id="tr<?php echo $i; ?>">
                                    <td style="padding: 10px;text-align:center;"><input type="checkbox" id="cb<?php echo $i; ?>" name="urls[]" /></td>        
                                    <td style="padding: 10px;text-align:center;">
                                        <div style="width: 20px;"><?php echo $i; ?></div>                        
                                    </td>
                                    <td style="padding: 10px;text-align:center;"><input style="margin-bottom:0px;" type="text" class="urllimitwords" id="textbox<?php echo $i; ?>" name="urlstitle[]" value="<?php echo $urlgroup_url->title; ?>"  /></td>
                                </tr>								
								<?php $i++; } ?>
								<?php } ?>
								</tbody>
                            </table>
                    </div>
                    <input type="hidden" name="package_id" value="<?php echo JRequest::getVar('package_id');?>">  
					<input type="hidden" name="cid" id="cid" value="<?php echo $cid; ?>"/>    
                    <input type="hidden" name="option" value="com_searchengine" />
                    <input type="hidden" name="task" value="" />
                    <input type="hidden" name="controller" value="se_urlgroup" />
					<input name="boxchecked" value="0" type="hidden">
                </form>
            </div>
        </div>
    </div>
</div>
<script>
var trno = <?php echo $i; ?>;
function funAddtr(){
	var table = document.getElementById("tblurls");
	var row = table.insertRow();
	row.id="tr"+trno;
	var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
	var cell3 = row.insertCell(2);
	
	cell1.style="padding: 10px;text-align:center;";
	cell2.style="padding: 10px;text-align:center;";
	cell3.style="padding: 10px;text-align:center;";
	
    cell1.innerHTML = '<input type="checkbox" id="cb'+trno+'" name="urls[]" onclick="Joomla.isChecked(this.checked);" />';
    cell2.innerHTML = '<div class="serialnumber" style="width: 20px;">'+trno+'</div>';
	cell3.innerHTML = '<input style="margin-bottom:0px;" type="text" id="textbox'+trno+'" class="urllimitwords" name="urlstitle[]"  />';
	trno++;
}

function funDeletetr()
{
	if(confirm("Are you sure to delete?")){
		//var uncheck=document.getElementsByTagName('input');
		var uncheck=jQuery("input:checkbox");
	
		for(var i=0;i<uncheck.length;i++)
		{
			if(uncheck[i].type=='checkbox')
			{
				if(uncheck[i].checked){
					//alert(uncheck[i].id);
					chkno = uncheck[i].id.replace("cb","");

					//alert(chkno);
					jQuery("#tr" + chkno).remove();
					
					//serialnumber
					var serialno = 1;
					jQuery(".serialnumber").each(function() {
						//alert(serialno);
						jQuery(this).html(serialno);
						serialno++;
					});
					trno = serialno;
				}
			}
		}
	}
}


</script>
