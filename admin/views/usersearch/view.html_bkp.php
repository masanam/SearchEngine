<?php
/**
 * @version		$Id: view.html.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Quizzes
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();
jimport ( 'joomla.application.component.view' );
jimport('joomla.html.pagination');

class AwardpackageViewUsertransaction extends JViewLegacy {

	function display($tpl = null) {
		//CommunitySurveysHelper::initiate();
		
		JToolBarHelper::title(JText::_('User Transaction'), 'logo.png');
		$document = & JFactory::getDocument();
        $document->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js');
		$app = JFactory::getApplication();
		$package_id = JRequest::getVar('package_id');
		$model =  JModelLegacy::getInstance( 'userlist', 'AwardpackageModel' );
		$total = 10;//$model->getDonationHistoryTotal($userId, $packageId);
		$app = JFactory::getApplication();		
		$limitstart = $app->getUserStateFromRequest( '', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);
		$limit = (!empty($limit) ? $limit : 5);		
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
    	$this->pager = new JPagination($total, $limitstart, $limit);
     	$this->pagination = $this->pager;
		$result = $model->getAllUserlist(JRequest::getVar('package_id'));
		//$transactions = !empty($result['data']) ? $result['data'] : array();
		$data = array();
        		$data['user_name'] = JRequest::getVar('user_name');
				$data['start_date'] = JRequest::getVar('start_date');
				$data['end_date'] = JRequest::getVar('end_date');
				$data['transaction_type'] = JRequest::getVar('transaction_type');
				$data['amount_from'] = JRequest::getVar('amount_from');
				$data['amount_to'] = JRequest::getVar('amount_to');
				$data['user_action'] = JRequest::getVar('user_action');
       // $type = $data['transaction_type'];
		$countries = AwardpackagesHelper::Countries_list();
		$this->assignRef('countries', $countries);
		//$this->assignRef('type', $type);

		  switch($type){
			case 'symbol':
			    $useraccounts = $model->get_user($data,$limit, $limitstart);
				foreach ($useraccounts as $row){
					$userId = $row->id;//number_format($rows->prize_value/$rows->pieces ,0);
					$userName = $row->firstname;
				}
			    $transactions = $model->getTotalSymbol($userId,1,$limit, $limitstart, $filter_start, $filter_end);
				//var_dump($userId);
				$symbolPrizes = $model->getSymbolSymbolPrizeAmount();
				foreach ($symbolPrizes as $rows){
					$amount = number_format($rows->prize_value/$rows->pieces ,0);
				}

				$this->assignRef('total_symbol', $total_symbol);
				$this->assignRef('username', $userName);
				$this->assignRef('amount', $amount);
				//$transactions = $model->getTotalSymbol($userId,1,$limit, $limitstart);
				//$model->getFundingHistory($data, JRequest::getVar('package_id'), $limit, $limitstart);
				break;
			case 'shopping':
				
			default:
			$pending_transactions = $model->getFundingHistory($data, JRequest::getVar('package_id'), $limit, $limitstart,'PENDING');
			$completed_transactions = $model->getFundingHistory($data, JRequest::getVar('package_id'), $limit, $limitstart,'COMPLETED');
			$transactions = $model->getAllFundingHistory($data, JRequest::getVar('package_id'), $limit, $limitstart);

			break;
        }
					$this->assignRef('pending_transactions', $pending_transactions);
					$this->assignRef('completed_transactions', $completed_transactions);
					$this->assignRef('transactions', $transactions);
		JToolbarHelper::back('Back', 'index.php?option=com_awardpackage&package_id=' . JRequest::getVar('package_id'));
		parent::display($tpl);
	}
}