<?php 
/**
* @package		com_searchengine
* @copyright	Copyright (C) 2010-2012 JoomPROD.com. All rights reserved.
* @license		GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
JLoader::import('joomla.application.component.model');
JLoader::import('currency', JPATH_COMPONENT . DS . 'models');
class AwardPackageHelper{
	public function addStyleSheet() {
		$app = JFactory::getApplication();
		$templateDir = JPATH_ROOT . '/templates/' . $app->getTemplate();
		$document = JFactory::getDocument();
		$baseurl = JURI::base();
		if (is_file($templateDir . '/html/com_searchengine/css/adsmanager.css')) {
			$templateDir = JURI::base() . 'templates/' . $app->getTemplate();
			$document->addStyleSheet($templateDir . '/html/com_searchengine/css/adsmanager.css');
		}else {
            $document->addStyleSheet($baseurl . 'components/com_searchengine/css/adsmanager.css');
        }
        $document->addScript($baseurl . 'components/com_searchengine/js/jquery-1.6.2.min.js');
        $document->addScript($baseurl . 'components/com_searchengine/js/noconflict.js');
        $document->addScript($baseurl . 'components/com_searchengine/js/jquery-ui-1.8.16.custom.min.js');
        $document->addStyleSheet($baseurl . 'components/com_searchengine/js/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css');
        $document->addScript($baseurl . 'components/com_searchengine/js/plupload/plupload.full.js');
        $document->addScript($baseurl . 'components/com_searchengine/js/plupload/jquery.plupload.queue/jquery.plupload.queue.js');
        $lang = JFactory::getLanguage();
        $tag = $lang->getTag();
        $tag = substr($tag, 0, strpos($tag, '-'));
        if (file_exists(JPATH_BASE . "/components/com_searchengine/js/plupload/i18n/{$tag}.js"))
            $document->addScript($baseurl . "components/com_searchengine/js/plupload/i18n/{$tag}.js");
	}
	
	function checkUser(){	
		$app 			= JFactory::getApplication();
		$task 			= JRequest::getVar('task');
		$mainmodel 		= JModelLegacy::getInstance('award', 'AwardpackageModel');
		$userInfo 		= $mainmodel->getInfo();
        $packageInfo 	= $mainmodel->getAwardPackage($userInfo->package_id);
		$model = JModelLegacy::getInstance('address', 'AwardpackageModel');
		$user = JFactory::getUser();
		if($user->get('guest')){
			
			/*JError::raiseNotice( 100, 'Please login or <a href="index.php?option=com_searchengine&
						view=user&task=user.updateDetailInfo">Register</a>' );*/
			//$this->setRedirect('index.php?option=com_users&view=login&return='.$redirectUrl);
		    $redirectUrl = urlencode(base64_encode('index.php?option=com_searchengine&view=uaccount'));  
		    $errorMsg = 'Please login or <a href="index.php?option=com_searchengine&view=user&task=user.updateDetailInfo">Register</a>';

			$app->redirect('index.php?option=com_users&view=login&return='.$redirectUrl,$errorMsg); 
		} else {
			$model_account = JModelLegacy::getInstance('useraccount','AwardpackageUsersModel');
			$ret = $model_account->checkUserDetailInfo($user->id); 			
			if($ret == null) {
				/*$this->setRedirect(JRoute::_('index.php?option=com_searchengine&
						view=user&task=user.updateDetailInfo&userid=' . $user->id . '&emailRegistered=' . $user->email));*/
				$app->redirect(JRoute::_('index.php?option=com_searchengine&view=user&task=user.updateDetailInfo&userid=' . $user->id . '&emailRegistered=' . $user->email)); 	
			}else 
			if($ret == 'no_package') {
				JFactory::getApplication()->enqueueMessage('Waiting for your package registered');
				//$this->setRedirect(JRoute::_('index.php?option=com_users&view=login'));
				$app->redirect(JRoute::_('index.php?option=com_users&view=login'));
			}
		}		
	}
	
	function getUserData(){
		$user = JFactory::getUser();
		if(!$user->get('guest')){
			$model = JModelLegacy::getInstance('useraccount','AwardpackageUsersModel');
			//$user = $model->checkUserDetailInfo1($user->id);
			$user = $model->checkUserDetailInfo($user->id);
			return $user;	
		}
		//print_r($user);
		return null;
	}
	
	function getUserDataById($user_id){
		$user = JFactory::getUser($user_id);
		
		if(!$user->get('guest')){
			$model = JModelLegacy::getInstance('useraccount','AwardpackageUsersModel');		
			$user = $model->checkUserDetailInfo($user->id);
			return $user;	
		}
		
		return null;
	}
	
	function getPackageId($package_id){		
			$model = JModelLegacy::getInstance('useraccount','AwardpackageUsersModel');
			$packageId = $model->getPackageId($package_id);
			return $packageId;			
	}
	
	function checkDeposit(){
		//check schedule deposit user 
		$model 	= JModelLegacy::getInstance('deposit', 'AwardpackageModel');
		$user	= JFactory::getUser();
		
		//schedule deposit 
		$items		= $model->user_deposit($user->id);
		if($items){
			foreach($items as $item){
				$child 			= $model->check_child($item->id_deposit)->total;
				$amount_left	= $item->deposit_amount - $child;
				
				//check amount left
				if($amount_left<$item->top_amount){
					return false;
				}
				
				$lastChild	= $model->getLastChild($item->id_deposit);
				if($lastChild->dated!=''){
					$date	= $lastChild->dated;
				}else{
					$date	= $item->dated;
				}
				$dates 	= explode("-",$date);
				$year	= $dates[0];
				$month	= $dates[1];
				$day	= $dates[2];
				
				$nextN				= mktime(0, 0, 0, date($month), date($day) + $item->top_every, date($year));
				$deposit_schedule	= date('Y-m-d',$nextN);
				$now				= date('Y-m-d');
				
				$data 				= array();
				if($now == $deposit_schedule){
					$data['dated']=$now;
					$data['payment_gateway']=$item->payment_gateway;
					$data['deposit_amount']=$item->top_amount;
					$data['currency_code']=$item->currency_code;
					$data['status']=1;
					$data['deposit_parent']=$item->id_deposit;
					$data['deposit_number']=$item->id_deposit+1;
					$data['user_id']=$user->id;
					$model->saveDeposit($data);
				}
			}
		}
	}
	
    function upload() {
        header('Content-type: text/plain; charset=UTF-8');
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        // Settings
        $targetDir = JPATH_BASE . '/tmp/plupload/';
        $cleanupTargetDir = false; // Remove old files
        $maxFileAge = 60 * 60; // Temp file age in seconds
        // 5 minutes execution time
        @set_time_limit(5 * 60);

        // Uncomment this one to fake upload time
        // usleep(5000);
        // Get parameters
        $chunk = JRequest::getInt('chunk', 0);
        $chunks = JRequest::getInt('chunks', 0);
        $fileName = JRequest::getString('name', '');

        // Clean the fileName for security reasons
        $fileName = preg_replace('/[^\w\._]+/', '', $fileName);

        // Make sure the fileName is unique but only if chunking is disabled
        if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
            $ext = strrpos($fileName, '.');
            $fileName_a = substr($fileName, 0, $ext);
            $fileName_b = substr($fileName, $ext);

            $count = 1;

            while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
                $count++;

            $fileName = $fileName_a . '_' . $count . $fileName_b;
        }

        // Create target dir
        if (!file_exists($targetDir))
            JFolder::create($targetDir);

        // Remove old temp files
        if (is_dir($targetDir) && ($dir = opendir($targetDir))) {
            while (($file = readdir($dir)) !== false) {
                $filePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                // Remove temp files if they are older than the max age
                if (preg_match('/\\.tmp$/', $file) && (filemtime($filePath) < time() - $maxFileAge))
                    JFile::delete($filePath);
            }

            closedir($dir);
        } else
            die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');

        // Look for the content type header
        if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
            $contentType = $_SERVER["HTTP_CONTENT_TYPE"];

        if (isset($_SERVER["CONTENT_TYPE"]))
            $contentType = $_SERVER["CONTENT_TYPE"];

        // Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
        if (strpos($contentType, "multipart") !== false) {
            if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
                // Open temp file
                $in = JFile::read($_FILES['file']['tmp_name']);
                $out = $targetDir . DIRECTORY_SEPARATOR . $fileName;
                if ($chunk != 0) {
                    $content = JFile::read($out);
                    $in = $content . $in;
                }
                JFile::write($out, $in);
            } else
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
        } else {
            // Open temp file
            $out = $targetDir . DIRECTORY_SEPARATOR . $fileName;
            // Read binary input stream and append it to temp file
            $in = fopen("php://input", "rb");
            if ($chunk != 0) {
                $content = JFile::read($out);
                $in = $content . $in;
            }
            JFile::write($out, $in);
        }

        // Return JSON-RPC response
        die('{"jsonrpc" : "2.0", "result" : null, "id" : "id","tmpfile" : "' . $fileName . '"}');
    }
	
	public function currency(){
		 $model = JModel::getInstance('Currency', 'AwardpackageModel');
		 return $model->getItems();
	}
	
	public function paymentOptions(){
		 $model = JModel::getInstance('Currency', 'AwardpackageModel');
		 return $model->getPaymentOptions();
	}

	public function addtoEvents(){	
		$model 	= JModelLegacy::getInstance('Events', 'AwardpackageModel');
		$model->assignFreeGiftEventLog();
	}
	
	
}
?>
