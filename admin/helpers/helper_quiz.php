<?php
/**
 * @version		$Id: header.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

class QuizHelper {
	
	public static function get_question_icon($type){
		
		switch ($type){
			
			case CQ_PAGE_HEADER:
				return 'fa fa-magnet';
				
			case CQ_CHOICE_RADIO:
				return 'fa fa-dot-circle-o';
				
			case CQ_CHOICE_CHECKBOX;
				return 'fa fa-check-circle-o';
				
			case CQ_CHOICE_SELECT:
				return 'fa fa-hand-o-up';
				
			case CQ_GRID_RADIO:
				return 'fa fa-th-large';
				
			case CQ_GRID_CHECKBOX:
				return 'fa fa-th-large';
				
			case CQ_FREE_TEXT_SINGLE_LINE:
				return 'fa fa-minus';
				
			case CQ_FREE_TEXT_MULTILINE:
				return 'fa fa-align-justify';
				
			case CQ_FREE_TEXT_PASSWORD:
				return 'fa fa-qrcode';
				
			case CQ_FREE_TEXT_RICH_TEXT:
				return 'fa fa-file';
				
			case CQ_IMAGE_CHOOSE_IMAGE:
				return 'fa fa-picture-o';
				
			case CQ_IMAGE_CHOOSE_IMAGES:
				return 'fa fa-film';
		}
	}

	public static function award_points($params, $userid, $action, $reference, $info){

		$functions = null;

		switch ($params->get('points_system', 'none')){

			case 'cjblog':
			case 'touch':
			case 'jomsocial':

				$functions = array(
					'newquiz'=>'com_communityquiz.new_quiz',
					'response'=>'com_communityquiz.quiz_response');

				break;

			case 'aup':

				$functions = array(
					'newquiz'=>'sysplgaup_new_quiz',
					'response'=>'sysplgaup_quiz_response');

				break;

			default:

				return false;
		}

		switch ($action){

			case 1: // new quiz

				CJFunctions::award_points($params->get('points_system'), $userid, array(
					'points'=>$params->get('points_on_new_quiz', 0),
					'reference'=>$reference,
					'info'=>$info,
					'function'=>$functions['newquiz']
				));

				break;

			case 2: // new response

				CJFunctions::award_points($params->get('points_system'), $userid, array(
					'points'=>$params->get('points_on_new_response', 0),
					'reference'=>$reference,
					'info'=>$info,
					'function'=>$functions['response']
				));

				break;
		}
	}
}