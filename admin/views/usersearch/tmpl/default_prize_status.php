<?php
defined('_JEXEC') or die();
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">
			<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=userlist&task=userlist.user_list');?>" method="post" name="adminForm">
				<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>
<div class="span12">
<table class="table table-hover table-striped table-bordered" style="width:50%;">
									<thead>
										<tr>
                                			<th><?php echo JText::_('Prize'); ?></th>
											<th><?php echo JText::_('Prize Value'); ?></th>
											<th><?php echo JText::_('Symbol piece'); ?></th>													
                                            										
										</tr>
									</thead>
                                    <tbody>
                                    <?php
											$i = 0; 
											foreach ($this->prizes as $row):
											$i++;
										?>
										<tr>
											<td class="hidden-phone"><?php echo $row->prize_name; ?></td>
											<td class="hidden-phone"><?php echo '$'.$row->prize_value; ?></td>
											<td class="hidden-phone">
                                            
                                            <table border="1px">
									<?php
									$segment_width = 150/$row->cols; //Determine the width of the individual segments
									$segment_height = 150/$row->rows; //Determine the height of the individual segments
									$show = '';
									for( $rownya = 0; $rownya < $row->rows; $rownya++){
										$show .= '<tr>';
										for( $colnya = 0; $colnya < $row->cols; $colnya++){
						
											$filename = substr($row->symbol_image,0,strlen( $row->symbol_image) - 4).$rownya.$colnya.".png";
											$file = "./components/com_awardpackage/asset/symbol/pieces/" .$filename;
											$show .= '<td style="padding:3px;">';
											$show .= '<img id="image'.$i.'" style="left: 0px; top: 0px; width: '.$segment_width.'px;" alt="" src="'.$file.'?timestamp='.time().'"/>';
											$show .= '</td>';
										}
										$show .= '</tr>';
									}
									echo $show;
									?>
									</table>
                                            </td>	
										</tr>										
										<?php endforeach; ?>
									</tbody>
                                    </table>
</div>
							<div class="span12">
						<table class="table table-hover table-striped table-bordered">
									<thead>
										<tr>
                                			<th><?php echo JText::_('No'); ?></th>
											<th><?php echo JText::_('From'); ?></th>
											<th><?php echo JText::_('To'); ?></th>
											<th><?php echo JText::_('Duration'); ?></th>	
											<th><?php echo JText::_('Status'); ?></th>																	
                                            										
										</tr>
									</thead>
									<tbody>
                                     <?php
											$i = 0; 
											foreach ($this->symboldetail as $rows):
											$i++;
										?>
										<tr>
											<td class="hidden-phone"><?php echo $i; ?></td>
											<td class="hidden-phone"><?php echo $rows->date_created; ?></td>
											<td class="hidden-phone"><?php echo $rows->date_end; ?></td>	
											<td class="hidden-phone"><?php 
											$d1 = new DateTime($rows->date_created);
$d2 = new DateTime($rows->date_end);
$interval = $d2->diff($d1);

echo $interval->format('%H hours, %I minutes, %S seconds');
											 ?></td>
											<td class="hidden-phone"><?php 
											switch ($rows->status){
												case '1':
													echo 'Accepted'; 
													break;
												case '0':
													echo 'Discarded'; 
													break;
												case '2':
													echo 'Sold'; 
													break;
												case '3':
													echo 'Bought'; 
													break;
												}	
											
											
											?></td>                                            										
										</tr>										
										<?php endforeach; ?>
									</tbody>									
								</table>
							</div>
						
			</form>
		</div>
	</div>
</div>