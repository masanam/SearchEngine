<?php 
defined('_JEXEC') or die('Restricted Access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
    var hasLocation=0;
    var genderCount=0;
    var ageCount=0;
</script>
<script type="text/javascript">
  
function saveForm(frm){
      var frm =  jQuery('#'+frm);
      //var titleText = jQuery('#title').attr('value');
	  var titleText = jQuery('#title').val();
	  
      frm.find('#group_title').attr('value',titleText); 
      frm.submit();
}

</script>


<div id="cj-wrapper">
  <div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
    <div class="row-fluid">
	
	<?php echo $this->loadTemplate('nav');?>
	
	<div class="span10">
  <div class="clearfix">
    <form action="index.php?option=com_searchengine&task=se_usergroup.save&package_id=<?php echo $this->package_id; ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
      <label><?php echo JText::_('Group Name');?></label>
      <input name="title" id="title" type="text"  placeholder="<?php echo JText::_('Enter a group name');?>" required="" aria-required="true" value="<?php echo $this->group->title;?>">

      
      <input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>      
      <input type="hidden" name="option" value="com_searchengine" />
      <input type="hidden" name="task" id="task" value="se_usergroup.save" />
      <input type="hidden" name="id" id="id" value="<?php echo $this->group->id;?>"/>     
      <?php echo JHtml::_('form.token'); ?>

      </form>
    </div>  
</div>

        <div class="span10">          
          <div id="tabs" name="tabs">
            <ul>
              <li <?php if ($this->field == 'name') echo $this->class; ?>><a href="#tabs-0">Name</a></li>
              <li <?php if ($this->field == 'email') echo $this->class; ?>><a href="#tabs-1">E-mail</a></li>
              <li style="margin-left:50px;" <?php if ($this->field == 'location') echo $this->class; ?>><a style="color: #AA0304;" href="#tabs-4">Location</a></li>
              <li <?php if ($this->field == 'age') echo $this->class; ?>><a style="color: #AA0304;" href="#tabs-2">Age</a></li>
              <li <?php if ($this->field == 'gender') echo $this->class; ?>><a style="color: #AA0304;" href="#tabs-3">Gender</a></li>
            </ul>
              <div id="tabs-0"><?php echo $this->loadTemplate('name');?></div>
              <div id="tabs-1"><?php echo $this->loadTemplate('email');?></div>
              <div id="tabs-2"><?php echo $this->loadTemplate('age');?></div>
              <div id="tabs-3"><?php echo $this->loadTemplate('gender');?></div>
              <div id="tabs-4"><?php echo $this->loadTemplate('location');?></div>
          </div>          
      </div>

    </div>
  </div>
</div>
<script type="text/javascript">
function isNumberKey(evt)
{	
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
    return false;
    return true;
}

 jQuery(function($) {
    // For User-Group Filters : Left Column
    var index = 'qpsstats-active-tab';       
    var dataStore = window.sessionStorage;
    var oldIndex = 0;        
    try {            
        oldIndex = dataStore.getItem(index);
    } catch(e) {

    }
    $( "#tabs" ).tabs({
        active: oldIndex,
        activate: function(event, ui) {        
            var newIndex = ui.newTab.parent().children().index(ui.newTab); 
            consoel.log(newIndex);               
            try {
                dataStore.setItem( index, newIndex );
            } catch(e) {}
        }
    });   
	
	 
});

jQuery('.btn-group2').click(function(e){
        if(hasLocation==0){
            alert("You must save at least one location before saving a gender and age");
            return false;    
        }        
    });

Joomla.submitbutton = function(pressbutton) {
  if(pressbutton=='se_usergroup.back'){
    window.location.href = "index.php?option=com_searchengine&views=se_usergroup&task=se_usergroup.getlist&package_id=<?php echo $this->package_id; ?>";
  }else{

    document.adminForm.task.value=pressbutton;
    submitform(pressbutton);
  }
}  
</script>
