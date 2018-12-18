<?php
/**
 * @version		$Id: user.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/awardpackages.php';

class SearchEngineControllerSearchengine extends JControllerLegacy {

	function __construct(){
		parent::__construct();
		$this->registerTask('registration', 'registration_user');
		require_once JPATH_COMPONENT . '/helpers/searchenginepackage.php';
	}

	function search(){
		$model =& JModelLegacy::getInstance('searchengine','SearchEngineModel');
		$user = JFactory::getUser();
		if($user->id){
			$users = SearchenginePackageHelper::getUserData();
			//$this->userId = $users->id;
			$data['user_id']=$user->id;
			$data['package_id']=$users->package_id;//this is searchengine package id
		}else{
			$data['user_id']=0;
			$data['package_id']=0;
		}

		//$data['user_ip'] = "127.0.0.1";
		$data['user_ip'] = $_SERVER['REMOTE_ADDR'];
		//$data['user_ip'] = mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255);
		//$data['date_time']='';
		$data['keyword'] = JRequest::getVar('keyword');
		$data['reward'] = JRequest::getVar('reward');
		$data['url']='';
		$data['url_clicked']='';
		$data['clicked_date_time']='';
		$search_keyword = $data['keyword'];
		$search_type = '';
		$search_type = JRequest::getVar('search_type'); // search type ie: image, video, news Or All
		$search_type = ! empty($search_type) ? $search_type : 'web';
		//echo "TEST CONTROLLER ".$search_type;die;
		$data['search_type'] = $search_type;

		$data['uniqueid']=md5(time().mt_rand().md5($_SERVER['REMOTE_ADDR']).rand());
		$hasil = $model->getInfo($search_type,$search_keyword);
		$checkHistory = $model->getHistory();
		if (($hasil ==  NULL ) && ($checkHistory->check_status == '1')){
			$model->addInfo($data);
         }
		$msg = "";
		$this->setRedirect("index.php?option=com_searchengine&Itemid=".JRequest::getVar('Itemid')."&search=".JRequest::getVar('keyword')."&reward=".JRequest::getVar('reward')."&uid=".$data['uniqueid']."&type=".$search_type, $msg);

	}


	public function sedetail(){
		$user = JFactory::getUser();
		if($user->id){
			$view = $this->getView('searchengine', 'html');
			$view->display('sedetail');
		}else{
			$this->setRedirect("index.php?option=com_searchengine", "");
		}
	}

	public function sedetailurlclicked(){

		$user = JFactory::getUser();
		if($user->id){

			$users = SearchenginePackageHelper::getUserData();
			//$this->userId = $users->id;
			$data['user_id']=$user->id;
			$data['package_id']=$users->package_id;//this is searchengine package id
		}else{
			$data['user_id']=0;
			$data['package_id']=0;
		}

		//$data['user_ip'] = "127.0.0.1";
		$data['user_ip'] = $_SERVER['REMOTE_ADDR'];
		//$data['user_ip'] = mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255);

		$data['uniqueid']=JRequest::getVar('uid');

		$data['url']='';
		$data['url_clicked']=JRequest::getVar('url');
		$data['url_id']=JRequest::getVar('urlid');
		$data['url_type']=JRequest::getVar('urltype');
		$data['clicked_date_time']=date("Y-m-d H:m:s");



		$this->setRedirect(urldecode(str_replace("ampersand","&",JRequest::getVar('url'))), "");
	}
}
