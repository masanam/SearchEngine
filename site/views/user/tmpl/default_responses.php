<?php
/**
 * @version		$Id: default_responses.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();
?>
<div id="cj-wrapper">
	<div class="login ">
		<form action="<?php echo JRoute::_("index.php?option=com_awardpackage&view=useraccount&task=useraccount.edit&id=".$this->user->id);?> method="post" class="form-horizontal">
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
				<button type="submit" class="btn btn-primary">
					Log in				</button>
			</div>

			</fieldset>
	</form>
</div>