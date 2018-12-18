<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');

JHtml::_('behavior.formvalidation');
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
    <div id="cj-wrapper">
        <div class="survey-wrapper nospace-left no-space-left no-space-right">
            <div class="row-fluid">
                <?php echo $this->loadTemplate('nav');?>
                <div id="j-main-container" class="span10">
                    <?php echo $this->loadTemplate($this->active);?>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="package_id" value="<?php echo $this->packageId; ?>">  
	<input
	type="hidden" name="count" id="count"
	value="<?php //echo count( $this->data );?>" /> <input type="hidden"
	name="baseurl" id="baseurl" value="<?php //echo $base;?>" />
    <input type="hidden" name="option" value="com_searchengine"/> 
    <input type="hidden" name="boxchecked" value="0" /> 
    <input type="hidden" name="task" value="create" /> 
    <input type="hidden" name="controller" value="se_urlgroup" />
    
</form>