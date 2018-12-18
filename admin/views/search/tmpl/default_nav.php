<div class="span3">
	<div id="sidebar">
		<div class="sidebar-nav">
			<ul class="nav nav-list" id="submenu">
				<li <?php if($this->tpl=='ip'){?> class="active" <?php }?>>
					<a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=search&package_id='.$this->package_id);?>">IP Address</a>
				</li>
				<li <?php if($this->tpl=='keyword'){?> class="active" <?php }?>>
					<a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=search&tpl=keyword&package_id='.$this->package_id);?>">Keyword Input</a>
				</li>
				<li <?php if($this->tpl=='url'){?> class="active" <?php }?>>
					<a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=dashboard&task=urlclicked');?>">Url Clicked</a>
				</li>
			</ul>
		</div>
	</div>
</div>