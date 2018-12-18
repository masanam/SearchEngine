<div class="span2">
    <div id="sidebar">
        <div class="sidebar-nav">
            <ul class="nav nav-tabs nav-stacked">
                <li <?php if($this->active==''){?> class="active" <?php }?>>
                    <a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=dashboard', false);?>">Search engine rewards list</a>
                </li>
                <li <?php if($this->active==''){?> class="active" <?php }?>>
                    <a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=se_usergroup', false);?>">Search engine user group list</a>
                </li>
                <li class="active">
                    <a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=se_surveygroup', false);?>">Survey group list</a>
                </li>
                <li <?php if($this->active==''){?> class="active" <?php }?>>
                    <a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=se_quizgroup', false);?>">Quiz group list</a>
                </li>
                <li <?php if($this->active==''){?> class="active" <?php }?>>
                    <a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=se_urlreward', false);?>">Url rewards list</a>
                </li>
                <li <?php if($this->active==''){?> class="active" <?php }?>>
                    <a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=se_keywordgroup', false);?>">Keyword group list</a>
                </li>
                <li <?php if($this->active==''){?> class="active" <?php }?>>
                    <a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=se_urlgroup', false);?>">Url group list</a>
                </li>
                <li <?php if($this->active==''){?> class="active" <?php }?>>
                    <a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=se_utilitylabelgroup', false);?>">Utility label group list</a>
                </li>
            </ul>
        </div>
    </div>
</div>