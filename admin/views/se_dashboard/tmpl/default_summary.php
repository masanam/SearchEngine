<?php
$listData =& $this->listData;
//print_r($listData);
?>
<div id="top_table">
<table align="center" border="0" class="table table-hover table-striped">
        <thead>
            <tr style="text-align:center; background-color:#CCCCCC">
				<th width="1%" align="center"><input type="checkbox" name="checkall-toggle"
				value="on" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
				onclick="Joomla.checkAll(this)" /></th>
                <th><?php echo JText::_('User');?></th>
                <th><?php echo JText::_('Email');?></th>
                <th><?php echo JText::_('Rewards Name');?></th>
                <th><?php echo JText::_('Start Publish Date');?></th>
                <th><?php echo JText::_('End Publish Date');?></th>
                <th><?php echo JText::_('Search Engine User Group');?></th>
                <th><?php echo JText::_('Keyword Group');?></th>
                <th><?php echo JText::_('Url rewards List');?></th>
                <th><?php echo JText::_('Survey group');?></th>
                <th><?php echo JText::_('Quiz group');?></th>
                <th><?php echo JText::_('Created');?></th>
                <th><?php echo JText::_('Item Status');?></th>
            </tr>
			</thead>
            <?php
			if( $this->listData ){
            foreach ($listData as &$result)
            {
				
				$user1	= JFactory::getUser($result->user_id);	
            ?>
            <tr style="text-align:center;">
				<td align="center"><?php echo JHtml::_('grid.id', $i, $result->id); ?></td>	
                <td><?php echo $user1->username;?></td>
                <td><?php echo $user1->email;?></td>
                <td>
				<a href="<?php echo JRoute::_('index.php?option=com_searchengine&controller=se_dashboard&task=edit&cid='.$result->id.'&package_id='.JRequest::getVar('package_id')); ?>"><?php echo $result->title; ?>
				</a>
				<?php //echo $result->title;?></td>
                <?php /* <td><?php echo date_format(date_create($result->startpublishdate),"Y/m/d");?></td>
                <td><?php echo date_format(date_create($result->endpublishdate),"Y/m/d");?></td>                 
                 */
                ?>
                <td align="center"><?php echo strtotime($result->created)>0 ? date('d M Y',strtotime($result->startpublishdate)): '-';?></td>
                <td align="center"><?php echo strtotime($result->created)>0 ? date('d M Y',strtotime($result->endpublishdate)): '-';?></td>
                
                <td>
					<?php
						if(!empty($result->usergroupfull))
						{						
							$usergroupfull=json_decode($result->usergroupfull);
						}
		
						if(!empty($usergroupfull))
						{
							/*
							foreach($usergroupfull as $key=>$value)
							{
								if(!empty($value) && $value!='null')
								{
									echo explode(',',$value)[1];
								}
							}
							*/
							if(!empty($usergroupfull) && $usergroupfull!='null')
							{
								echo explode(',',$usergroupfull)[1];
							}
						}
					?>	
				</td>
                <td>
					<?php
						if(!empty($result->keywordgroupfull))
						{						
							$keywordgroupfull=json_decode($result->keywordgroupfull);
						}		
						if(!empty($keywordgroupfull))
						{
							if(!empty($keywordgroupfull) && $keywordgroupfull!='null')
							{
								echo explode(',',$keywordgroupfull)[1];
							}
						}
					?>	
				</td>
                <td>
					<?php
						if(!empty($result->urlgroupfull))
						{						
							$urlgroupfull=json_decode($result->urlgroupfull);
						}		
						if(!empty($urlgroupfull))
						{
							if(!empty($urlgroupfull) && $urlgroupfull!='null')
							{
								echo explode(',',$urlgroupfull)[1];
							}
						}
					?>	
				</td>
                <td>
					<?php
						if(!empty($result->surveygroupfull))
						{						
							$surveygroupfull=json_decode($result->surveygroupfull);
						}		
						if(!empty($surveygroupfull))
						{
							if(!empty($surveygroupfull) && $surveygroupfull!='null')
							{
								echo explode(',',$surveygroupfull)[1];
							}
						}
					?>	
				</td>
                <td>
					<?php
						if(!empty($result->quizgroupfull))
						{						
							$quizgroupfull=json_decode($result->quizgroupfull);
						}		
						if(!empty($quizgroupfull))
						{
							if(!empty($quizgroupfull) && $quizgroupfull!='null')
							{
								echo explode(',',$quizgroupfull)[1];
							}
						}
					?>	
				</td>
                <?php /* <td><?php echo date_format(date_create($result->created),"Y/m/d");?></td> */ ?>
                
                <td align="center"><?php echo strtotime($result->created)>0 ? date('G:i a d M Y',strtotime($result->created)): '-';?></td>
                
                <td><?php echo $result->surveycount+$result->quizcount+$result->urlgroupcount ?></td>
		  	
            </tr>
            <?php
		
		$i++;
			}
			}
		else{
		?>
			<tr><td colspan="5">No records found</td></tr>
		<?php
		}
		 ?>
        
    </table>
	<table width="100%">
                <tr><td style="text-align:right;">
                <div class="pagination pagination-toolbar">
                  <?php 
                  echo $this->pagination;                  
                ?>
                </div>
                </td></tr>
              </table>
</div>	
