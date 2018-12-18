<div class="span3">
	<div id="sidebar">
		<div class="sidebar-nav">
			<ul class="nav nav-list" id="submenu">
				<li <?php if($this->active=='summary'){?> class="active" <?php }?>>
					<a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=dashboard');?>">Keyword Input & Url Clicked Summary</a>
				</li>
				<li <?php if($this->active=='list'){?> class="active" <?php }?>>
					<a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=dashboard&task=urlclicked');?>">Keyword Input & Url Clicked List</a>
				</li>
			</ul>
		</div>
	</div>
</div>
