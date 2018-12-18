<div class="span2">
    <div id="sidebar">
        <div class="sidebar-nav">
            <ul class="nav nav-tabs nav-stacked">
                <li <?php if($this->active==''){?> class="active" <?php }?>>
                    <a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=se_dashboard&package_id='.JRequest::getVar('package_id'), false);?>">Search engine rewards list</a>
                </li>
                <li <?php if($this->active==''){?> class="active" <?php }?>>
                    <a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=se_usergroup&package_id='.JRequest::getVar('package_id'), false);?>">Search engine user group list</a>
                </li>
                <li <?php if($this->active==''){?> class="active" <?php }?>>
                    <a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=se_surveygroup&package_id='.JRequest::getVar('package_id'), false);?>">Survey group list</a>
                </li>
                <li <?php if($this->active==''){?> class="active" <?php }?>>
                    <a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=se_quizgroup&package_id='.JRequest::getVar('package_id'), false);?>">Quiz group list</a>
                </li>
                <li <?php if($this->active==''){?> class="active" <?php }?>>
                    <a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=se_urlreward&package_id='.JRequest::getVar('package_id'), false);?>">Url rewards list</a>
                </li>
                <li class="active">
                    <a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=se_keywordgroup&package_id='.JRequest::getVar('package_id'), false);?>">Keyword group list</a>
                </li>
                <li <?php if($this->active==''){?> class="active" <?php }?>>
                    <a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=se_urlgroup&package_id='.JRequest::getVar('package_id'), false);?>">Url group list</a>
                </li>
                <li <?php if($this->active==''){?> class="active" <?php }?>>
                    <a href="<?php echo JRoute::_('index.php?option=com_searchengine&view=se_utilitylabelgroup&package_id='.JRequest::getVar('package_id'), false);?>">Utility label group list</a>
                </li>
            </ul>
        </div>
    </div>
</div>