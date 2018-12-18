<?php
defined('_JEXEC') or die();
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
				
?>
<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">
			<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_searchengine&view=userlist&task=userlist.user_list');?>" method="post" name="adminForm">
				<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>
				
						<table class="table table-hover table-striped table-bordered">
									<thead>
                                    <tr><td colspan="12" style="text-align:right;">      
                                   
                                   <?php echo $this->pagination->getLimitBox(); ?>
                                    </td>                                   
    </tr>
										<tr>
											<th style="text-align:center;"><?php echo JText::_('Prize value'); ?></th>																	
											<th style="text-align:center;"><?php echo JText::_('Prize'); ?></th>
											<th style="text-align:center;"><?php echo JText::_('Symbol set'); ?></th>
											<th style="text-align:center;"><?php echo JText::_('Symbol pieces to collect'); ?></th>
											<th style="text-align:center;"><?php echo JText::_('Extracted pieces (EP)'); ?></th>
											<th style="text-align:center;"><?php echo JText::_('Value pieces (VP)'); ?></th>											
											<th style="text-align:center;"><?php echo JText::_('Free pieces (FP)'); ?></th>
											<th style="text-align:center;"><?php echo JText::_('Pieces already collected'); ?></th>
											<th style="text-align:center;"><?php echo JText::_('Pieces not collected'); ?></th>
                                            <th style="text-align:center;"><?php echo JText::_('Prize status'); ?></th>
											<th style="text-align:center;"><?php echo JText::_('Extracted pieces insert into symbol queue'); ?></th>
											<th style="text-align:center;"><?php echo JText::_('Symbol queue number for extracted pieces'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php 
										foreach ($this->symbolprize as $presentation):
											
										?>
										<tr>
											<td class="hidden-phone"><?php echo JText::_('$'.$presentation->prize_value); ?></td>
											<td class="hidden-phone">
												<img
												src="./components/com_searchengine/asset/prize/<?php echo $presentation->prize_image; ?>"
												style="width: 100px;" />
											</td>
											<td class="hidden-phone">
												<img
												src="./components/com_searchengine/asset/symbol/<?php echo $presentation->symbol_image; ?>"
												style="width: 100px;" />
											</td>											
                                            <td class="hidden-phone"><?php echo $presentation->pieces; ?></td>											
											<td class="hidden-phone"><?php echo JText::_(count($extracteds)); ?></td>											
											<td class="hidden-phone"><?php echo JText::_($remain); ?></td>
											<td class="hidden-phone"><?php echo JText::_('0'); ?></td>
											<td class="hidden-phone"><?php echo $this->countprize; ?></td>
											<td class="hidden-phone"><?php $remains = $presentation->pieces - $this->countprize; 
											echo $remains;
											?></td>
                                            <td class="hidden-phone"><?php echo $presentation->status; ?></td>											
                                            <td class="hidden-phone"><?php echo $presentation->status; ?></td>											
                                            <td class="hidden-phone"><?php echo $presentation->status; ?></td>											
                                            
											
										</tr>
										<?php endforeach;?>
                                        <tr><td colspan="12" style="text-align:right;">                                      
		                                   <div class="pagination">
											<?php
											echo $this->pagination->getListFooter();
											echo '<br/><br/>'. $this->pagination->getPagesCounter(); 
											?>
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