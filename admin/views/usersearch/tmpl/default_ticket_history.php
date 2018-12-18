<h2>Member points ticket history: <small><strong><?php echo $this->tickets[0]->fullname;?></strong></small></h2>

<hr style="margin: 4px;">	
<table class="table table-striped" style="border: 1px solid #ccc;">
<thead>
  <tr>
    <th width="15%"><u><?php echo JText::_('Received date time')?></u></th>
    <th width="12%"><u><?php echo JText::_('Member ticket')?></u></th>
    <th width="12%"><u><?php echo JText::_('Member points')?></u></th>
    <th width="20%"><u><?php echo JText::_('Value of 1 member points')?></u></th>
    <th width="5%"><u><?php echo JText::_('Status')?></u></th>
    <th width="7%"><u><?php echo JText::_('% used')?></u></th>
    <th width="18%"><u><?php echo JText::_('Started in user date time')?></u></th>
    <th width="15%"><u><?php echo JText::_('Used date time')?></u></th>    
  </tr>
</thead>
<tbody>                    
 <?php 
                
                if($this->tickets):                    
                foreach ($this->tickets as $ticket):                            
                    if($ticket->used_points == 0){
                        $used_points=0;    
                    }else{
                        $used_points = ($ticket->used_points/$ticket->points)*100;    
                    }
                    
                ?>                    
                    <tr>                          
                        <td class="hidden-phone"><?php echo date('ga d M Y', strtotime($ticket->created_at)); ?></td>
                        <td class="hidden-phone"><?php echo $ticket->title;?></td>
                        <td class="hidden-phone"><?php echo $ticket->points;?></td>                       
                        <td class="hidden-phone"><?php echo sprintf('%s cent USD', 1);?></td>
                        <td class="hidden-phone">
                            <?php 
                            switch ($ticket->status) {
                                case 0:
                                        echo 'Not in use';
                                    break;
                                
                                 case 1:
                                        echo 'In use';
                                    break;

                                 case 2:
                                         echo 'Used';
                                    break;
                            }                                
                            ?>
                        </td>  
                        <td class="hidden-phone"><?php echo sprintf('%s%s used', $used_points,'%');?></td> 
                        <td class="hidden-phone"><?php echo strtotime($ticket->started_date)>0?date('ga d M Y', strtotime($ticket->started_date)):'-'; ?></td>
                        <td class="hidden-phone"><?php echo strtotime($ticket->used_date)>0? date('ga d M Y', strtotime($ticket->used_date)):'-'; ?></td>         
                    </tr>                                                         
                <?php endforeach;
                        
                else:
                ?>
                <tr><td colspan="8">No records found.</td></tr>
                <?php endif;?>

                                                                         
</tbody>
</table>