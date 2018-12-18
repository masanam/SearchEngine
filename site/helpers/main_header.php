<?php
defined('_JEXEC') or die();
$user = JFactory::getUser();

$users = AwardPackageHelper::getUserData();

require_once JPATH_COMPONENT. '/helpers/memberticket.php';
MemberticektHelper::assignTickets();
$active_tickets = MemberticektHelper::countAciveTicktes();

$model = & JModelLegacy::getInstance( 'ugiftcode', 'AwardpackageUsersModel' );
$modelPrize = & JModelLegacy::getInstance( 'prizewon', 'AwardpackageModel' );
$total_giftcode = $model->getTotalRemainingGiftCode();
AwardPackageHelper::addtoEvents();

$freeGiftCodeQty = 0;//$model->getFreeGiftCodeQty($users->free_usergroup_id);

//$prize = $modelPrize->getPrizeSent();

$prize = $modelPrize->getSymbolSymbolPrize1($users->package_id);

$total_symbol = $modelPrize->getTotalSymbol($user->id,1);

$total_collected_symbols_count = $modelPrize->getAllSymbolCollectedCount($user->id,1);
$symbol_counts = array();

/*foreach ($total_collected_symbols_count as $key => $value) {
	if( ! isset($symbol_counts[$value->presentation_id]) ){
		$symbol_counts[$value->presentation_id] = 1;
	}else{
		$symbol_counts[$value->presentation_id] = $symbol_counts[$value->presentation_id]+1 ;
	} 				
}*/

foreach ($total_symbol as $key => $value) {
	if( ! isset($symbol_counts[$value->presentation_id]) ){
		$symbol_counts[$value->presentation_id] = 1;
	}else{
		$symbol_counts[$value->presentation_id] = $symbol_counts[$value->presentation_id]+1 ;
	} 				
}

$prizedClaimed = $modelPrize->getCliamedPirze($users->id,$users->package_id);
$claimedIdList = array();
foreach ($prizedClaimed as $key => $value) {
	$claimedIdList[] = $value->claimed_prize_id; 
}

//$tot = count($total_symbol);
//$count = 0;
//foreach($prize as $p){
//$total=0;
//    foreach($total_symbol as $tot){
//        if($p->prize_id == $tot->symbol_prize_id){
//            $total++;
//        }
//    }
//    if($tot >= $p->pieces){
//        $count++;
//    }
//}
//$tot = count($total_symbol);
$count = 0;
/*foreach($prize as $p){
    $total=0;
    $totalpicesToCollect = $modelPrize->getTotalPiecesToCollect($p->symbol_id);
    $totalCollected = $symbol_counts[$p->symbol_id];
    foreach($total_symbol as $tot){
        if($p->prize_id == $tot->symbol_prize_id){
            $total++;
        }
    }
    if($total >= $p->pieces && $totalpicesToCollect == $totalCollected){
        $count++;
    }
}*/


/*foreach($prize as $p){
	$totalpicesToCollect = $modelPrize->getTotalPiecesToCollect($p->symbol_id,$p->symbol_prize_id);
	$totalCollected = $symbol_counts[$p->presentation_id];
	if( $totalpicesToCollect == $totalCollected && !in_array($p->prize_id, $claimedIdList) ){
		$count++;
	}
}*/

foreach($prize as $p){
	$totalpicesToCollect = $modelPrize->get_total_symbol_set_to_collect($p->symbol_id,$p->presentation_id);
	$totalCollected = $symbol_counts[$p->presentation_id];
	if( $totalpicesToCollect == $totalCollected && !in_array($p->prize_id, $claimedIdList) ){
		$count++;
	}
}


?>

<div id='cssmenu'>
<ul>
	<li
		class="<?php echo $this->page_id == "1" ? ' active' : '' ?>">
	<a
		href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=uaccount&task=uaccount.getMainPage');?>"><?php echo JText::_('Account')?></a>
	</li>	
	<li
		class="<?php echo $this->page_id == "2" ? ' active' : '' ?>">
	<a
		href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=ufunding&task=ufunding.getMainPage');?>"><?php echo JText::_('Funds')?></a>
	</li>
	<li
		class="<?php echo $this->page_id == "3" ? ' active' : '' ?>">
	<a
		href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=udonation&task=udonation.getMainPage');?>"><?php echo JText::_('Donation')?></a>
	</li>	
	<li
		class="<?php echo $this->page_id == "4" ? ' active' : '' ?>">
	<a
		href="index.php?option=com_awardpackage&view=quiz&task=quiz.get_latest_quizzes"><?php echo JText::_('Quiz')?></a>
	</li>	
	<li
		class="<?php echo $this->page_id == "5" ? ' active' : '' ?>">
	<a
		href="index.php?option=com_awardpackage&view=survey&task=survey.get_all_surveys"><?php echo JText::_('Survey')?></a>
	</li>
	<li
		class="<?php echo $this->page_id == "6" ? ' active' : '' ?>">
		<?php $class = (($total_giftcode+$freeGiftCodeQty) >= 1000)?'large_giftcode_value':'';?>
	<a
		href="index.php?option=com_awardpackage&view=ugiftcode&task=ugiftcode.getMainPage"><div class="<?php echo $class?> aw-red-box" style="margin-left: -20%;float:left;background-color:red;border-radius: 50%;height: 28px;width: 28px;color: white;font-weight: bold;text-align: center;line-height: 26px;"><?php echo ($total_giftcode+$freeGiftCodeQty)<0?0:($total_giftcode+$freeGiftCodeQty); ?></div><?php echo JText::_('Giftcode')?></a>
	</li>
	<li
		class="<?php echo $this->page_id == "7" ? ' active' : '' ?>">
	<a
		href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=prize&task=prize.getMainPage');?>"><div class="aw-red-box" style="margin-left: -20%;float:left;background-color:red;border-radius: 50%;height: 28px;width: 28px;color: white;font-weight: bold;text-align: center;line-height: 26px;"><?php echo $count;/*count($prize);*/?></div><?php echo JText::_('Prizes')?></a>
	</li>

	
	<li
		class="<?php echo $this->page_id == "8" ? ' active' : '' ?>">
	<a
		href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=ushoppingcreditplan&task=ushoppingcreditplan.getMainPage');?>"><?php echo JText::_('Shopping Credits')?></a>
	</li>

	<li
		class="<?php echo $this->page_id == "8" ? ' active' : '' ?>">
	<a
		href="<?php echo JRoute::_('index.php?option='.S_APP_NAME.'&view=membertickets&task=membertickets.getMainPage');?>">
		<div class="aw-red-box" id="ticket_bubble" style="margin-left: -20%;float:left;background-color:red;border-radius: 50%;height: 28px;width: 28px;color: white;font-weight: bold;text-align: center;line-height: 26px;top:12px;"><?php echo $active_tickets ;?></div>
		<?php echo 'Member<br>Tickets'?></a>
	</li>
					<li class="divider-vertical"></li>
					<!--li class="dropdown">
						<a href="<?php echo JRoute::_('index.php?option=com_users&view=login');?>" ><?php echo JText::_('Logout')?></a>
					</li-->
</ul>
</div>


