<?php
$listData =& $this->listData;
//print_r($listData);
?>
<div id="top_table">
<div class="navbar">
	<div class="navbar-inner" style="background-color: #fafafa;">	
		<div class="header-container">
			<div class="cq-nav-collapse nav-collapse collapse">
				<ul class="nav">					
					<li>
						<a class="btn" href="<?php echo JRoute::_('index.php?option=com_searchengine&view=se_keywordgroup&layout=create');?>">
							<?php echo JText::_('New keyword group');?>														
						</a>						
					</li>
					<li>
						&nbsp;&nbsp;&nbsp;<button class="btn margin-bottom-10" type="button" onclick=" Joomla.submitbutton('remove');"><i class="fa fa-times"></i> <?php echo JText::_('Delete');?></button>						
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<table align="center" border="0" class="table table-hover table-striped">
        <thead>
            <tr style="text-align:center; background-color:#CCCCCC">
				<th width="1%" align="center"><input type="checkbox" name="checkall-toggle"
				value="on" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
				onclick="Joomla.checkAll(this)" /></th>
                <th><?php echo JText::_('Keyword Group');?></th>
                <th><?php echo JText::_('Created');?></th>
                <th><?php echo JText::_('Modified');?></th>
            </tr>
			</thead>
            <?php
			if( $this->listData ){
            foreach ($listData as $k => $result)
            {
            ?>
            <tr class="row<?php echo $k % 2; ?>">
				<td align="center"><?php echo JHtml::_('grid.id', $i, $result->id); ?></td>			
                <td>
				<a href="<?php echo JRoute::_('index.php?option=com_searchengine&controller=se_keywordgroup&task=edit&cid='.$result->id); ?>"><?php echo $result->title; ?>
				</a>
				<?php //echo $result->title;?></td>
                <td align="center"><?php echo strtotime($result->created)>0 ? date('G:i a d M Y',strtotime($result->created)): '-';?></td>
		<td align="center"><?php echo strtotime($result->modified)>0 ? date('G:i a d M Y',strtotime($result->modified)): '-';?></td>
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
