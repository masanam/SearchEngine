<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');

JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
	function submit_task(task){
		jQuery('#task').val(task);
		//alert(task);
		jQuery('#adminForm').submit();
	}
	function delete_task(task){
		if(window.confirm('Are you sure?')){
			jQuery('#task').val(task);
			//alert(task);
			jQuery('#adminForm').submit();
		}else{
			return false;
		}
	}
	
</script>
<jdoc:include type="message" />
<br/>
<button name="search" class="btn btn-default pull-right search_btn"><i class="glyphicon glyphicon-search">&nbsp;</i><?php echo JText::_('SEARCH');?></button>
<form
                    action="<?php echo JRoute::_('index.php?option=com_searchengine'); ?>"
                    method="post" name="adminForm" id="adminForm" class="validate" enctype='multipart/form-data'>
<?php //echo $this->loadTemplate('nav');
//echo $this->searchview;
?>
<div id="j-main-container">
	<br/>
	  <!-- Nav tabs -->
	  <ul class="nav nav-tabs" role="tablist">
		<li role="presentation" <?php if($this->searchview=='ip'){?> class="active" <?php }?>><a href="#ip" aria-controls="ip" role="tab" data-toggle="tab">IP Address1</a></li>
		<li role="presentation" <?php if($this->searchview=='keyword'){?> class="active" <?php }?>><a href="#keyword" aria-controls="keyword" role="tab" data-toggle="tab">Keyword Input</a></li>
		<li role="presentation" <?php if($this->searchview=='url'){?> class="active" <?php }?>><a href="#url" aria-controls="messages" role="tab" data-toggle="tab">Url Clicked</a></li>
	  </ul>

	  <!-- Tab panes -->
	  <div class="tab-content">
                <div role="tabpanel" <?php if($this->searchview=='ip'){?> class="tab-pane active" <?php } else{ ?> class="tab-pane" <?php } ?>  id="ip">
			<?php echo $this->loadTemplate('ip');?>
		</div>
		<div role="tabpanel" <?php if($this->searchview=='keyword'){?> class="tab-pane active" <?php } else{ ?> class="tab-pane" <?php } ?> id="keyword">
			<?php echo $this->loadTemplate('keyword');?>
		</div>
		<div role="tabpanel" <?php if($this->searchview=='url'){?> class="tab-pane active" <?php } else{ ?> class="tab-pane" <?php } ?> id="url">
			<?php echo $this->loadTemplate('url');?>
		</div>
	  </div>

	
</div>
 <input type="hidden" name="task" value="" id="task" />
 <input type="hidden" value="<?php echo $this->package_id; ?>" name="package_id">
 <input type="hidden" name="boxchecked" value="0">
 <?php echo JHtml::_('form.token'); ?> 
</form>
