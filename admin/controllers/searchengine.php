<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitysurveys.php';
require_once JPATH_COMPONENT . '/helpers/awardpackages.php';


class SearchEngineControllerSearchEngine extends JControllerLegacy {
	function __construct(){
		parent::__construct();
	}
	
	public function loggedout(){
		$session =& JFactory::getSession();
		$session->set("loggedin","");
		$this->setRedirect('index.php?option=com_searchengine','Search Logged out');
	}
	
	public function loggedin(){
		$session =& JFactory::getSession();
		$session->set("loggedin","1");
		$this->setRedirect('index.php?option=com_searchengine','Search Logged in');
	}
	
	public function addNew(){
		$package_id = JRequest::getVar('package_id');
		$this->setRedirect('index.php?option=com_searchengine&view=search&package_id='.$package_id.'&tmpl=component');
	}
	
	public function add_ip(){
		$data = JRequest::getVar('data');
		$application = JFactory::getApplication();
		$package_id = JRequest::getVar('package_id');
		$valid = true;
		if($data['date_from']==''){
			$application->enqueueMessage(JText::_('Date From Required'), 'error');
			$valid = false;
		}if($data['date_to']==''){
			$application->enqueueMessage(JText::_('Date To Required'), 'error');
			$valid = false;
		}if($data['ip_from']==''){
			$application->enqueueMessage(JText::_('Ip Range From Required'), 'error');
			$valid = false;
		}if($data['ip_to']==''){
			$application->enqueueMessage(JText::_('Ip Range to Required'), 'error');
			$valid = false;
		}
		if($valid){
			$model = JModelLegacy::getInstance('searchengine','SearchEngineModel');
			if($model->addIp($data)){
				$application->enqueueMessage(JText::_('data has been saved'), 'Message');
			}
		}
		$this->setRedirect('index.php?option=com_searchengine&view=search&package_id='.$package_id.'&tmpl=component&searchview=ip');
	}
	
	public function delete_ip(){
		$cid = JRequest::getVar('cid');
		$application = JFactory::getApplication();
		$package_id = JRequest::getVar('package_id');
		$model = JModelLegacy::getInstance('searchengine','SearchEngineModel');
		$valid = true;
		if(count($cid)<1){
			$application->enqueueMessage(JText::_('No selected data'), 'error');
			$valid = false;
		}
		if($valid){
			if($model->deleteIp($cid)){
				$application->enqueueMessage(JText::_('data has been saved'), 'Message');
			}
		}
		$this->setRedirect('index.php?option=com_searchengine&view=search&package_id='.$package_id.'&tmpl=component&searchview=ip');
	}
	
	public function add_keyword(){
		$package_id = JRequest::getVar('package_id');
		$fieldName = 'filek';
		$path = 'keywords';
		$data = JRequest::getVar('data');
		$data['keyword_list'] = $this->saveFile($fieldName,$path);
		
		if($data['keyword_list']){
			$model = JModelLegacy::getInstance('searchengine','SearchEngineModel');
			
			if($model->addKey($data)){
				$application = JFactory::getApplication();
				$application->enqueueMessage(JText::_('data has been saved'), 'Message');
			}
		}
		$this->setRedirect('index.php?option=com_searchengine&view=search&package_id='.$package_id.'&tmpl=component&searchview=keyword');
	}
	
	public function delete_keyword(){
		$cid = JRequest::getVar('cid');
		$application = JFactory::getApplication();
		$package_id = JRequest::getVar('package_id');
		$model = JModelLegacy::getInstance('searchengine','SearchEngineModel');
		$valid = true;
		if(count($cid)<1){
			$application->enqueueMessage(JText::_('No selected data'), 'error');
			$valid = false;
		}
		if($valid){
			foreach($cid as $id){
					$file = $model->getKeywordFile($id);
					$uploadPath = JPATH_SITE.DS.'components'.DS.'com_searchengine'.DS.'data'.DS.'keywords'.DS.$file->keyword_list;
					unlink($uploadPath);
				}

			if($model->deleteKeyword($cid)){	
				$application->enqueueMessage(JText::_('data has been deleted'), 'Message');
			}
		}
		$this->setRedirect('index.php?option=com_searchengine&view=search&package_id='.$package_id.'&tmpl=component&searchview=keyword');
	}
	
	public function add_url(){
		$package_id = JRequest::getVar('package_id');
		$fieldName = 'file2';
		$path = 'urls';
		$data = JRequest::getVar('data');
		$data['url_list'] = $this->saveFile($fieldName,$path);

		
		if($data['url_list']){
			$model = JModelLegacy::getInstance('searchengine','SearchEngineModel');
			
			if($model->addUrl($data)){
				$application = JFactory::getApplication();
				$application->enqueueMessage(JText::_('data has been saved'), 'Message');
			}
		}

		$this->setRedirect('index.php?option=com_searchengine&view=search&package_id='.$package_id.'&tmpl=component&searchview=url');
	}
	
	public function delete_url(){
		$cid = JRequest::getVar('cid');
		$application = JFactory::getApplication();
		$package_id = JRequest::getVar('package_id');
		$model = JModelLegacy::getInstance('searchengine','SearchEngineModel');
		$valid = true;
		if(count($cid)<1){
			$application->enqueueMessage(JText::_('No selected data'), 'error');
			$valid = false;
		}
		if($valid){
			foreach($cid as $id){
					$file = $model->getUrlFile($id);
					$uploadPath = JPATH_SITE.DS.'components'.DS.'com_searchengine'.DS.'data'.DS.'urls'.DS.$file->url_list;
					unlink($uploadPath);
				}

			if($model->deleteUrl($cid)){
				$application->enqueueMessage(JText::_('data has been deleted'), 'Message');
			}
		}
		$this->setRedirect('index.php?option=com_searchengine&view=search&package_id='.$package_id.'&tmpl=component&searchview=url');
	}
	
	public function saveFile($fieldName,$path){
		$fileError = $_FILES[$fieldName]['error'];
		$application = JFactory::getApplication();
		$package_id = JRequest::getVar('package_id');
		if ($fileError > 0) 
		{
				switch ($fileError) 
			{
				case 1:
				$application->enqueueMessage(JText::_( 'FILE TO LARGE THAN PHP INI ALLOWS' ), 'error');
				return 0;
		 
				case 2:
				$application->enqueueMessage(JText::_( 'FILE TO LARGE THAN HTML FORM ALLOWS' ), 'error');
				return 0;
		 
				case 3:
				$application->enqueueMessage(JText::_( 'ERROR PARTIAL UPLOAD' ), 'error');
				return 0;
		 
				case 4:
				$application->enqueueMessage(JText::_( 'ERROR NO FILE' ), 'error');
				return 0;
			}
		}
		//check for filesize
		$fileSize = $_FILES[$fieldName]['size'];
		if($fileSize > 2000000)
		{
			$application->enqueueMessage(JText::_( 'FILE BIGGER THAN 2MB' ), 'error');
			return 0;
		}
		
		//check the file extension is ok
		$fileName = $_FILES[$fieldName]['name'];
		$uploadedFileNameParts = explode('.',$fileName);
		$uploadedFileExtension = array_pop($uploadedFileNameParts);

		 
		$validFileExts = explode(',', 'txt');
		 
		//assume the extension is false until we know its ok
		$extOk = false;
	
		//go through every ok extension, if the ok extension matches the file extension (case insensitive)
		//then the file extension is ok
		foreach($validFileExts as $key => $value)
		{
			if( preg_match("/$value/i", $uploadedFileExtension ) )
			{
				$extOk = true;
			}
		}

		if (!$extOk) 
		{
			$application->enqueueMessage(JText::_( 'INVALID EXTENSION' ), 'error');
			return 0;
		}
		$fileTemp = $_FILES[$fieldName]['tmp_name'];
		$fileName=$package_id.'_'.$path.'_'.date('YmdHis').'.txt';
		$uploadPath = JPATH_SITE.DS.'components'.DS.'com_searchengine'.DS.'data'.DS.$path.DS.$fileName;
		if(!JFile::upload($fileTemp, $uploadPath)) 
		{
			//echo JText::_( 'ERROR MOVING FILE' );
			$application->enqueueMessage(JText::_( 'ERROR MOVING FILE' ), 'error');
			return 0;
		}
		else
		{
		   // success, exit with code 0 for Mac users, otherwise they receive an IO Error
		   return $fileName;
		}
	}
}
