<?php
defined('_JEXEC') or die();
?>
<div id="cj-wrapper">
	
	<div class="login ">
		<form action="<?php echo JRoute::_("index.php?option=com_searchengine&view=user&task=user.login");?>" method="post" class="form-horizontal">
				<fieldset class="well">  
				<div class="control-group">
					<div class="control-label">
						<label id="username-lbl" for="username" class=" required">User Name<span class="star">&#160;*</span></label>						</div>
						<div class="controls">
							<input type="text" name="username" id="username" value="" class="validate-username" size="25" required aria-required="true" />						</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<label id="password-lbl" for="password" class=" required">Password<span class="star">&#160;*</span></label>						</div>
								<div class="controls">
								<input type="password" name="password" id="password" value="" class="validate-password" size="25" maxlength="99" required aria-required="true" />						</div>
							</div>
							<div  class="control-group">
				<div class="control-label"><label>Remember me</label></div>
				<div class="controls"><input id="remember" type="checkbox" name="remember" class="inputbox" value="yes"/></div>
			</div>
			
			<div class="controls">
<button type="submit" class="btn btn-primary">Log in</button>
</div>

			</fieldset>
	</form>

<div>
	<ul class="nav nav-tabs nav-stacked">
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=user&task=user.new_user')?>">
				Don't have an account?
			</a>
		</li>
				
	</ul>
</div>
</div>
</div>


