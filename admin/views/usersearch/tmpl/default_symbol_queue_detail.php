<?php
defined('_JEXEC') or die();
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">
			<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=usersearch&task=usersearch.get_symbol_queue_detail&accountId='.JRequest::getVar("accountId").'&package_id='.JRequest::getVar("package_id").'');?>" method="post" name="adminForm">
				<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>
				<input type="hidden" name="accountId" value="<?php echo JRequest::getVar("accountId"); ?>">
				<div class="span12">
						<table class="table table-hover table-striped table-bordered">
									<thead>
                                     <tr><td colspan="7" style="text-align:right;">                                    
                                   
                                    <?php echo $this->pagination->getLimitBox(); ?>
                                    </td>                                   
    </tr>
										<tr>
											<th width="10%"><?php echo JText::_('Symbol queue number'); ?></th>																	
											<th><?php echo JText::_('Symbol piece'); ?></th>
											<th><?php echo JText::_('Symbol piece type'); ?></th>
											<th><?php echo JText::_('Symbol piece price'); ?></th>
											<th><?php echo JText::_('Prize'); ?></th>
											<th><?php echo JText::_('Symbol piece status'); ?></th>	
											<th><?php echo JText::_('Giftcode'); ?></th>	                                            										
										</tr>
									</thead>
									<tbody>
										<?php
											$i = 0; 
											foreach ($this->symbolPrizes as $row):
											$i++;
										?>
										<tr>
											<td><?php echo JText::_($i); ?></td>
											<td><?php //if($row->cols > 0 && $row->rows > 0){ ?>
									<table border="1px">
									<?php
									$segment_width = 150/$row->cols; //Determine the width of the individual segments
									$segment_height = 150/$row->rows; //Determine the height of the individual segments
									$show = '';
									//for( $rownya = 0; $rownya < $row->rows; $rownya++){
										$show .= '<tr>';
										//for( $colnya = 0; $colnya < $row->cols; $colnya++)
						
											$filename = $row->symbol_pieces_image;
											$file = "./components/com_awardpackage/asset/symbol/pieces/" .$filename;
											$show .= '<td style="padding:3px;">';
											$show .= '<img id="image'.$filename.'" style="left: 0px; top: 0px; width: '.$segment_width.'px;" alt="" src="'.$file.'?timestamp='.time().'"/>';
											$show .= '</td>';
										//}
										$show .= '</tr>';
									//}
									echo $show;
									?>
									</table>
									<?php // } ?>
									</td>
											<td class="hidden-phone"><?php echo JText::_('Clone'); ?></td>
											<td class="hidden-phone"><?php echo JText::_(!empty($row->prize_value) ?  '$'.round($row->prize_value/$row->pieces,0) : 'Free'); ?></td>
											<td class="hidden-phone"><img
												src="./components/com_awardpackage/asset/prize/<?php echo $row->prize_image; ?>"
												style="width: 100px;" /></td>											
											<td class="hidden-phone"><a href="index.php?option=com_awardpackage&view=usersearch&task=usersearch.get_prize_status&prizeId=<?php echo $row->symbol_prize_id; ?>&accountId=<?php echo JRequest::getVar('accountId'); ?>&package_id=<?php echo JRequest::getVar('package_id'); ?>"><?php echo JText::_('View');?></a>	</td>
                                            <td class="hidden-phone">	<?php echo $row->category_name; ?></td>
										</tr>
										<?php endforeach;?>
                                        <tr><td colspan="7" style="text-align:right;">                                        
                                   
                                   <div class="pagination">
    <?php //$pagthisination = new JPagination($total, $p_start, 2);
	
echo $this->pagination->getListFooter();;
echo '<br/><br/>'. $this->pagination->getPagesCounter(); ?>
        </div>
                                    </td>                                   
    </tr>
									</tbody>									
								</table>
							</div>		
			</form>	
		</div>
	</div>
</div>