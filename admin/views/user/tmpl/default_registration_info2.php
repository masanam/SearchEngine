<?php
defined('_JEXEC') or die();
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');

$par = null;
if(!empty($parents)){
	$par = $parents[0];
}

?>

<div id="cj-wrapper">
<div class="login ">
<form
	action="<?php echo JRoute::_("index.php?option=com_awardpackage&view=user&task=user.updateInfo");?>"
	method="post" id="adminForm" name="adminForm" class="form-horizontal">
    
    <input type="hidden" name="email" value="<?php echo @$this->email; ?>" />
<fieldset class="well">

<div class="control-group">
 <span style="color:#FF0000">Read the following terms before you proceed!!<br/></span>
<ul  style="text-align:justify;padding:10px;">
<li>You do NOT require to input your birthday, phone or address to participate in our surveys, quizzes and other activities on our website </li>

<li>We will require your birthday, phone and address ONLY when you need to claim any prizes that you have won on our website</li>

<li>To prevent identity fraud, you will NOT be allowed to claim the prizes that you have won if the personal information you have provided to us on our website  is incorrect or fake. </li>
<li> Your personal information refers to your first name, last name, email, gender, birthday, phone and address.</li>
<li>Incorrect or fake personal information refers to information that do not match the true and correct personal information on your drivers license or passport.</li>
</ul>
<input type="checkbox" name="toc" id="toc" required="" aria-required="true"/>&nbsp; I accept the terms above <span style="color:#FF0000">&nbsp;*</span>
</div>



<div class="control-group">
<div class="control-label">Birthday</div>
<div class="controls"><?php echo JHtml::_('calendar', @$this->data['birthdate'], 'birthdate', 'birthdate', '%Y-%m-%d', array('placeholder' => 'YYYY-MM-DD')); ?>
</div>
</div>



<div class="control-group">
<div class="control-label">Phone</div>
<div class="controls"><input type="text" name="phone" id="phone"
	value="<?php echo @$this->data['phone']; ?>" size="25" /> </div>

<div class="control-group"></div>
<div class="control-label">Address</div>
<div class="controls"><textarea name="street" id="street" style="margin: 0px; height: 133px; width: 463px;"></textarea>
<?php //echo $this->editor->display("street", "", "15", "3", "25", "10", 10, null, null, null, array('mode' => 'extended'));?> </div>


<br />
<div class="controls">
<button type="button" class="btn btn-primary btn-back" >Back</button>

<button type="submit" class="btn btn-primary" >Submit</button>
</div>
</fieldset>
</form>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($){
		$('.btn-back').click(function(){
			window.location = "<?php echo JRoute::_('index.php?option=com_awardpackage&view=user&task=user.updateUserInfo',false);?>"; 			
		})
	});
</script>
