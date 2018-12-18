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
                <th><?php echo JText::_('Url Group');?></th>
                <th><?php echo JText::_('Created');?></th>
                <th><?php echo JText::_('Modified');?></th>
            </tr>
			</thead>
            <?php
			if( $this->listData ){
            foreach ($listData as &$result)
            {
            ?>
            <tr style="text-align:center;">
				<td align="center"><?php echo JHtml::_('grid.id', $i, $result->id); ?></td>			
                <td>
				<a href="<?php echo JRoute::_('index.php?option=com_searchengine&controller=se_urlgroup&task=edit&cid='.$result->id.'&package_id='.JRequest::getVar('package_id')); ?>"><?php echo $result->title; ?>
				</a>		
					
				<?php //echo $result->title;?>				
				</td>
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
