<?php
$listData =& $this->listData;
//print_r($listData);
?>
<div id="top_table">
    <table align="center" border="0" class="table table-striped" width="70%">
        <thead>
            <tr style="text-align:center; background-color:#CCCCCC">
                <th><?php echo JText::_('Rewards Rule');?></th>
                <th><?php echo JText::_('Created');?></th>
                <th><?php echo JText::_('Modified');?></th>
            </tr>
            <?php
            foreach ($listData as &$result)
            {
            ?>
            <tr style="text-align:center; background-color:#CCCCCC">
                <td><?php echo $result->title;?></td>
                <td><?php echo date_format(date_create($result->created),"Y/m/d");?></td>
                <td><?php echo date_format(date_create($result->modified),"Y/m/d");?></td>
            </tr>
            <?php
            }
            ?>
        </thead>
    </table>
</div>	