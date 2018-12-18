<?php 
/**
* @package		com_awardpackage
* @copyright	Copyright (C) 2010-2012 JoomPROD.com. All rights reserved.
* @license		GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
JLoader::import('joomla.application.component.model');
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/awardpackages.php';


class MemberticektHelper{

	public static $user1;
	public static $user2;
	public static $user_id;
	public static $packageId;


	public function setProperties($user_id=null){
		
		if($user_id){
			
			if(!isset(self::$user1)){
				$model = JModelLegacy::getInstance('useraccount','AwardpackageUsersModel');
				self::$user1 = $model->checkUserDetailInfo($user_id);
			}

			self::$user_id = self::$user1->id;		
			self::$packageId = self::$user1->package_id;

		}else{

			if(!isset(self::$user2)){
				self::$user2= AwardPackageHelper::getUserData();
			}

			self::$user_id  = self::$user2->id;		
			self::$packageId= self::$user2->package_id; 
		}

	}

	public static function assignTickets($user_id = null){

		self::setProperties($user_id);
		$model 		= JModelLegacy::getInstance('memberticekts', 'AwardpackageModel');
		$model->assignTickets(self::$user_id,self::$packageId);
	}


	public static function getFundBalance($user_id = null){
		self::setProperties($user_id);		
		$debit = 0;
		$credit = 0;

		$model = JModelLegacy::getInstance( 'ufunding', 'AwardpackageUsersModel' );
		$histories = $model->getAllHistory(self::$user_id, self::$packageId,'FUNDING');
		foreach ($histories as $history) 
		{            
            if ($history->status == 'COMPLETED' )
            {							
				$credit += !empty($history->credit) && $history->credit != null ? (double) $history->credit : 0;
			}
		}
		$histories = $model->getAllHistory(self::$user_id, self::$packageId,'WITHDRAW');
		foreach ($histories as $history) {
            $debit += !empty($history->debit) && $history->debit != null ? (double) $history->debit : 0;							
			
		}

		$histories = $model->getAllHistory(self::$user_id, self::$packageId,'QUIZ');
		if(count($histories)){
			foreach ($histories as $history) {
				$debit += !empty($history->debit) && $history->debit != null ? (double) $history->debit : 0;						
			}
		}
		

		$histories = $model->getAllHistory(self::$user_id, self::$packageId,'SURVEY');
		if(count($histories)){
			foreach ($histories as $history) {
				$debit += !empty($history->debit) && $history->debit != null ? (double) $history->debit : 0;						
			}
		}
		$remain = $credit - $debit;
		return $remain;		
	}

	public function getMemberTickets($user_id){
		self::setProperties($user_id);			
		$model 		= JModelLegacy::getInstance('memberticekts', 'AwardpackageModel');
		$tickets = $model->getMemberTickets(self::$user_id, self::$packageId);
		return $tickets;
		

	}

	public function getTicektPoints($tickets,$maxamount,$current,$count,$remainingAmount,$update,$usedBy=null){
		
		$ticket = $tickets[$current];
		$model 		= JModelLegacy::getInstance('memberticekts', 'AwardpackageModel');

		if( $current > $count){			
			return $remainingAmount;
		}
	
		if( $ticket->status ==1 ){
		   if($ticket->qty>0){
				$ticket->points = $ticket->points* $ticket->qty;	
		   }
		   $totalAmount = $ticket->points;
		   $usedAmount = $ticket->used_points;
		   $remainingAmount += $totalAmount - $usedAmount;

		   if( $remainingAmount < $maxamount){
		   		$model->updateTicketStatus( $ticket);		   		
		   		return self::getTicektPoints($tickets,$maxamount,$current+1,$count,$remainingAmount,$update,$usedBy);
		   }else{
		   		$model->updateTicket( $ticket,$maxamount,$remainingAmount,$update,$usedBy);
		   		return $remainingAmount;
		   }

		}else{
			
		   $remainingAmount += $ticket->points;
		   if( $remainingAmount < $maxamount){
		   		$model->updateTicketStatus( $ticket);
		   		return self::getTicektPoints($tickets,$maxamount,$current+1,$count,$remainingAmount,$update,$usedBy);
		   }else{		   		
		   		$model->updateTicket( $ticket,$maxamount,$remainingAmount,$update,$usedBy);		   		
		   		return $remainingAmount;
		   }			
		}
	}

	public function countAciveTicktes($user_id){
		self::setProperties($user_id);	

		$model 		= JModelLegacy::getInstance('memberticekts', 'AwardpackageModel');
		return $model->countTickets(self::$user_id, self::$packageId);
	}

	public function getInUseMemberTicket($user_id,$status){
		self::setProperties($user_id);	

		$model 		= JModelLegacy::getInstance('memberticekts', 'AwardpackageModel');
		return $model->getInUseMemberTicket(self::$user_id, self::$packageId,$status);

	}
}