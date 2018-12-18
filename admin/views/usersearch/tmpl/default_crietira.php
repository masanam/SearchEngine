 <h2>Search User</h2>
<table class="table table-hover table-striped " width="100%" style="margin: 2px;" id="tb-serach" >
  <tr>
    <td>            
      <div class="control-label"> 
     
        <form name="usergorupForm" id="usergorupForm" method="post" 
              action="index.php?option=com_searchengine&view=usersearch&package_id=<?php echo $this->package_id; ?>">

                User registered from <?php echo JHTML::calendar(JRequest::getVar('usergorup_from'),'usergorup_from', 'usergorup_from', '%Y-%m-%d',array('style'=>'width:60%','maxlength'=>'10')); ?> <br>
                    User registered to <?php echo JHTML::calendar(JRequest::getVar('usergorup_to'),'usergorup_to', 'usergorup_to', '%Y-%m-%d',array('style'=>'width:68%','maxlength'=>'10')); ?> 

          <input type="hidden" name="package_id" value="<?php echo $this->package_id; ?>"/>
                <input type="hidden" name="option" value="com_searchengine" />
                <input type="hidden" name="controller" value="usersearch" />
                <input type="hidden" name="task" value="searchgroup" />  
                <input type="hidden" name="target_column1" id="target_column1" value="<?php echo JRoute::_('index.php?option=com_searchengine&view=usersearch&package_id='.$this->package_id.'&criteria=1&task=usersearch.searchlist&tmpl=component', false);?>"/>                    
          <button type="button" class="btn btn-primary search_btn"  style="vertical-align: top;">Search</button>
          </form>
      </div>
    </td>
  </tr>
</table>
<br>

<div id="tabs" name="tabs">
  <ul>
    <li <?php if ($this->field == 'name') echo $this->class; ?>><a href="#tabs-0">Name</a></li>
    <li <?php if ($this->field == 'email') echo $this->class; ?>><a href="#tabs-1">E-mail</a></li>
    <li <?php if ($this->field == 'age') echo $this->class; ?>><a href="#tabs-2">Age</a></li>
    <li <?php if ($this->field == 'gender') echo $this->class; ?>><a href="#tabs-3">Gender</a></li>
    <li <?php if ($this->field == 'location') echo $this->class; ?>><a href="#tabs-4">Location</a></li>
  </ul>
  <div id="tabs-0"><?php echo $this->loadTemplate('name');?></div>
  <div id="tabs-1"><?php echo $this->loadTemplate('email');?></div>
  <div id="tabs-2"><?php echo $this->loadTemplate('age');?></div>
  <div id="tabs-3"><?php echo $this->loadTemplate('gender');?></div>
  <div id="tabs-4"><?php echo $this->loadTemplate('location');?></div>
</div>
<script type="text/javascript">
 $(function($) {
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
</script>
<style type="text/css">
  #tb-serach{
    border: 1px solid #ddd;
    border-radius:4px;
  }
  .ui-tabs .ui-tabs-panel {
  padding: 1em 0.4em !important;
}
</style>