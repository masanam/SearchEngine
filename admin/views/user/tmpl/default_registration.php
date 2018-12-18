<?php
defined('_JEXEC') or die();
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
?>

<div id="cj-wrapper">
<div class="login ">
<form
	action="<?php echo JRoute::_("index.php?option=com_awardpackage&view=user&task=user.save");?>"
	method="post" class="form-horizontal">
<fieldset class="well">

<div class="control-group">
<div class="control-label"><label id="username-lbl" for="username"
	class=" required">User Name<span class="star">&#160;*</span></label></div>
<div class="controls"><input type="text" name="username" id="username"
	value="<?php echo $this->data['name']; ?>" class="validate-username" size="25" required
	aria-required="true" /></div>
</div>

<div class="control-group">
<div class="control-label"><label id="password-lbl" for="password"
	class=" required">Password<span class="star">&#160;*</span></label></div>
<div class="controls"><input type="password" name="password"
	id="password" value="" class="validate-password" size="25"
	maxlength="99" required aria-required="true" /></div>
</div>

<div class="control-group">
<div class="control-label"><label id="confirmpasw-lbl" for="confirmpasw"
	class=" required">Confirm Password<span class="star">&#160;*</span></label>
</div>
<div class="controls"><input type="password" name="confirmpasw"
	id="confirmpasw" value="" class="validate-confirmpasw" size="25"
	required aria-required="true" /></div>
</div>

<div class="control-group">
<div class="control-label"><label id="email-lbl" for="email"
	class=" required">Email<span class="star">&#160;*</span></label></div>
<div class="controls"><input type="text" name="email" id="email"
	value="<?php echo $this->data['email'];  ?>" class="validate-email" size="25" required aria-required="true" />
</div>
</div>

<br />
<div class="controls">
<button type="submit" class="btn btn-primary">Submit</button>
</div>
</fieldset>
</form>
</div>