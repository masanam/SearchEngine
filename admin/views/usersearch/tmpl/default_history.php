<h2>Penalty history: <small><strong><?php echo $this->records[0]->fullname;?></strong></small></h2>

<hr style="margin: 4px;">	
<table class="table table-striped" style="border: 1px solid #ccc;">
<thead>
  <tr>
    <th width="5%"><u><?php echo JText::_('#')?></u></th>
    <th width="20%"><u><?php echo JText::_('Created date time')?></u></th>
    <th width="20%"><u><?php echo JText::_('Penalty')?></u></th>    
  </tr>
</thead>
<tbody>                    
 <?php 
 if($this->records) {

 foreach ($this->records as $key => $item) { 
  /* $startDate = date_create($item->created_at);
   $endDate =date( "Y-m-d H:i:s",strtotime($item->created_at . "+$item->days day +23 hours +59 minutes +59 seconds")); 
   $endDate = date_create($endDate);    
   $interval = date_diff($startDate,$endDate);*/

    $endD = date_create($item->created_at);
    date_add($endD,date_interval_create_from_date_string("+$item->days days +23 hours +59 minutes +59 seconds"));
    $end_at = date_format($endD,"Y-m-d H:i:s");
    $interval = DateHelper::dateDifference($item->created_at,$end_at);
   ?>
  <tr>                      
  <td>
  <?php echo $key+1;?> 
  </td>
  <td><?php echo $item->created_at;?></td>
  <td><?php echo $interval;?></td>                    
  </tr>                                                             
  <?php 
    }
  }else{?>     
    <tr><td colspan="4">No records found</td></tr>
  <?php 
  }?>                                                                     
</tbody>
</table>