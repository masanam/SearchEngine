<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
class SearchEngineViewSearchEngine extends JViewLegacy
{
	protected $pagination;

	function display($tpl = null) 
	{
		$this->addStyleSheet();
                
		$listData = null;
		
		$user = JFactory::getUser();
		$users = SearchenginePackageHelper::getUserData();
		$userId = $users->id;
		$packageId = $users->package_id; //this is searchengine package id  

		$jinput = JFactory::getApplication()->input;

		$ip = $jinput->server->get('REMOTE_ADDR', '', '');

		//Here default value and filter are null, so you can write 

		$user_ip = $jinput->server->get('REMOTE_ADDR');
	//	echo "ip is-> ".$user_ip."<br/>";

		//echo "userId-> ".$userId . " packageId->" . $packageId;
		
		$this->assignRef('userId', $userId);
		$this->assignRef('packageId', $packageId);
		
		
		if(JRequest::getVar('search')){
			//echo "Ammar". JRequest::getVar('search');
						
			$pageLimit = 50;
			
			$page = isset($_GET['page']) ? ((int) $_GET['page']) : 1;
			if( $page > 1  ) {				
				$limitstart = ($page-1) * $pageLimit; 
			}else {				
				$limitstart = 0;
			}


			$search_keywords = JRequest::getVar('search'); // search keywords	
		
			$search_params = array('q'=>$search_keywords);

			//echo "Ammar test <pre>";print_r($search_params);

			$options = array(
					'search_params'=>$search_params,
					'limit'=>$pageLimit, 
					'limitstart'=>$limitstart);

			$model1 =& JModelLegacy::getInstance('searchengine','SearchEngineModel');
			$return = $model1->get_searchenginesrewards($options,$packageId,$userId);

			$this->assignRef('items', $return['serewardlist']);

			$total= $model1->total;

			include("default_html_dom.php");

			/*AAZ Google scrap search starts here*/

			//if (isset ($_POST['submit'])) {
			if(JRequest::getVar('search')){
				//echo "Result Keyword"; die;
				//$in = isset($_POST['search_box']) ? $_POST['search_box'] : '';
				$in = JRequest::getVar('search'); // search keywords	
				$search_type = '';
				$search_type = JRequest::getVar('type'); // search type ie: image, video, news Or All
				$search_type = ! empty($search_type) ? $search_type : 'web';
				//echo "Search type->".$search_type."<br>";

				//$in = "Beautiful Bangladesh";
				$in = str_replace(' ','+',$in); // space is a +

				//if search Type is All
				if ($search_type === 'web'){
					$url  = 'http://www.google.com/search?hl=en&tbo=d&site=&source=hp&q='.$in.'&oq='.$in.'&userip='.$user_ip.'';
				}
				elseif ($search_type === 'image'){ //if search Type is Images
					$url  = 'http://www.google.com/search?hl=en&tbo=d&site=&source=hp&q='.$in.'&oq='.$in.'&tbm=isch'.'&userip='.$user_ip.'';
				}
				elseif ($search_type === 'video'){ //if search Type is Videos
					$url  = 'http://www.google.com/search?hl=en&tbo=d&site=&source=hp&q='.$in.'&oq='.$in.'&tbm=vid'.'&userip='.$user_ip.'';
				}
				elseif ($search_type === 'news'){//if search Type is News
					$url  = 'http://www.google.com/search?hl=en&tbo=d&site=&source=hp&q='.$in.'&oq='.$in.'&tbm=nws'.'&userip='.$user_ip.'';
				}
				else{ //default All
					$url  = 'http://www.google.com/search?hl=en&tbo=d&site=&source=hp&q='.$in.'&oq='.$in.'&userip='.$user_ip.'';
				}

				$searchData = array (
					'keyword' => $in,
					'url' => $url,
					'type' => $search_type,
					'userip' => $user_ip
					//'userId' => $userId
					//'packageId' => $packageId
				);

				//$model2 =& JModelLegacy::getInstance('searchengine','SearchEngineModel');
				//$return = $model2->get_se_searchresult_detail($data);
				$this->assignRef('searchData', $searchData);

				//print $url."<br>";

				$html = file_get_html($url);
				//echo $html;
				
				if ($search_type != 'image'){
					$linkObjs = $html->find('h3.r a'); //for All type and video type
				}
				$search_result = array();
				
				//News TYpe
				$i = 0;
				if ($search_type === 'news'){
					foreach ($linkObjs as $linkObj) {
						$title = trim($linkObj->plaintext);
						$link  = trim($linkObj->href);
						
						// if it is not a direct link but url reference found inside it, then extract
						if (!preg_match('/^https?/', $link) && preg_match('/q=(.+)&amp;sa=/U', $link, $matches) && preg_match('/^https?/', $matches[1])) {
							$link = $matches[1];
						} else if (!preg_match('/^https?/', $link)) { // skip if it is not a valid link
							continue;
						}

						//SLP text

						$dateDesc = $html->find('span.f',$i); // description is not a child element of H3 thereforce we use a counter and recheck.

						$descr = $html->find('div.st',$i); // description is not a child element of H3 thereforce we use a counter and recheck.

						$image_thumb = $html->find('div.g td a',$i);
						$image_thumb_link = $image_thumb->href;

						$thumb_img = $html->find('div.g td a img', $i)->src;

						
						//$description  = trim($descr);
						//echo "Ori descr-> ". $descr. "<br />";
						
						$desc_txt = $descr->plaintext;

						//echo "Test desc_txt-> ".$desc_txt. "<br />";
						$i++;  
						$search_result[] = array(
							'title' => $title,
							'link' => $link,
							'descr' => $desc_txt,
							'date_desc' => $dateDesc,
							'img_thumb_link' => $image_thumb_link,
							'thumb_img' => $thumb_img,
							'type' => $search_type,
						);
						
						//echo '<p>Title: ' . $title . '<br />';
						//echo 'Link: ' . $link . '<br />';
						//echo 'Description: ' . $descr . '</p>';
					}
				}


				//Video type
				//$i = 0;
				if ($search_type === 'video'){
					foreach ($linkObjs as $linkObj) {
						$title = trim($linkObj->plaintext);
						$link  = trim($linkObj->href);
						
						// if it is not a direct link but url reference found inside it, then extract
						if (!preg_match('/^https?/', $link) && preg_match('/q=(.+)&amp;sa=/U', $link, $matches) && preg_match('/^https?/', $matches[1])) {
							$link = $matches[1];
						} else if (!preg_match('/^https?/', $link)) { // skip if it is not a valid link
							continue;
						}

						$descr = $html->find('span.st',$i); // description is not a child element of H3 thereforce we use a counter and recheck.

						$video_thumb = $html->find('div.th a',$i);
						$video_thumb_link = $video_thumb->href;

						$thumb_img = $html->find('div.th a img', $i)->src;

						
						//$description  = trim($descr);
						//echo "Ori descr-> ". $descr. "<br />";
						
						$desc_txt = $descr->plaintext;

						//echo "Test desc_txt-> ".$desc_txt. "<br />";
						$i++;  
						$search_result[] = array(
							'title' => $title,
							'link' => $link,
							'descr' => $desc_txt,
							'vid_thumb_link' => $video_thumb_link,
							'thumb_img' => $thumb_img,
							'type' => $search_type,
						);
						
						//echo '<p>Title: ' . $title . '<br />';
						//echo 'Link: ' . $link . '<br />';
						//echo 'Description: ' . $descr . '</p>';
					}
				}
				//$this->assignRef('search_results', $search_result);
				
				//foreach($html->find('img') as $element) {
					//echo $element->src . '<br>';
				//	echo '<a href="'.$image_url.'">'.'<img src="'.$element->src.'"></a>'."<br />";
				//}

				//if search Type is Images
				if ($search_type === 'image'){
					$anchorObjs = $html->find('table.images_table td a');
					$i=0;
					foreach ($anchorObjs as $anchorObj){
						$link  = trim($anchorObj->href);
						// if it is not a direct link but url reference found inside it, then extract
						if (!preg_match('/^https?/', $link) && preg_match('/q=(.+)&amp;sa=/U', $link, $matches) && preg_match('/^https?/', $matches[1])) {
							$link = $matches[1];
						} else if (!preg_match('/^https?/', $link)) { // skip if it is not a valid link
							continue;
						}
						
						$image_container = $html->find('img', $i)->src;
						//echo '<a href="'.$link.'">'.'<img src="'.$image_container.'"></a>'."<br />";
						$i++;
						
						$search_result[] = array(
							'link' => $link,
							'img_src' => $image_container,
							'type' => $search_type,
						);
					}
				}
				
				//$search_result = array();
				//if search Type is All Type
				if ($search_type === 'web'){
					foreach ($linkObjs as $linkObj) {
						$title = trim($linkObj->plaintext);
						$link  = trim($linkObj->href);
						
						// if it is not a direct link but url reference found inside it, then extract
						if (!preg_match('/^https?/', $link) && preg_match('/q=(.+)&amp;sa=/U', $link, $matches) && preg_match('/^https?/', $matches[1])) {
							$link = $matches[1];
						} else if (!preg_match('/^https?/', $link)) { // skip if it is not a valid link
							continue;
						}

						$site_url_details = $html->find('div.kv cite',$i); // description is not a child element of H3 thereforce we use a counter and recheck.

						$site_url_txt = $site_url_details->plaintext;

						$descr = $html->find('span.st',$i); // description is not a child element of H3 thereforce we use a counter and recheck.
						
						//$description  = trim($descr);
						//echo "Ori descr-> ". $descr. "<br />";
						
						$desc_txt = $descr->plaintext;

						//echo "Test desc_txt-> ".$desc_txt. "<br />";
						$i++;  
						$search_result[] = array(
							'title' => $title,
							'link' => $link,
							'site_url_txt' => $site_url_txt,
							'descr' => $desc_txt,
							'type' => $search_type,
						);
						
						//echo '<p>Title: ' . $title . '<br />';
						//echo 'Link: ' . $link . '<br />';
						//echo 'Description: ' . $descr . '</p>';
					}
				}
				//echo "<pre>";print_r($search_result);
				//die;
				$this->assignRef('search_results', $search_result);
				//echo "<pre>";print_r($descr_list);
				//die;
			}

			/*AAZ ends here*/


			$pagination = new SearchenginePaginationHelper;
			$pagination->setCurrent($page);				
			$pagination->setTotal($total);
			$pagination->addClasses('pagination-list');
			$pagination->setKey('page');
			$pagination->setNext('<span class="icon-next"></span>');
			$pagination->setPrevious('<span class="icon-previous"></span>');
			$pagination->setRPP($pageLimit);
			$this->pagination = $pagination->parse();
        }		
		else if(JRequest::getVar('id')){
			$id = JRequest::getVar('id');	
			$uid = JRequest::getVar('uid');	

			$model1 =& JModelLegacy::getInstance('searchengine','SearchEngineModel');
			$return = $model1->get_searchenginesrewardsdetail($id);
			//echo "Ammar test <pre>";print_r($return);exit;
			
			$model1->addInfoUpdate($id,$uid);

			$this->assignRef('items', $return);

		}
        
		parent::display($tpl);
	}
	
	function AddToolbar(){
		JToolBarHelper::title(JTEXT::_('Search Engine'));
	}
	
	function iscent($value,$digit){
		$model = JModelLegacy::getInstance('settings','AwardpackageModel');
		return $model->iscent($value,$digit);
	}
 
 	function show_username($value){
		$user = JFactory::getUser($value);
		return '<a href="'.JRoute::_('index.php?option=com_users&view=user&layout=edit&id='.$value).'">'.$user->username.'</a>';
	}	
	
	function show_category_info($id){
 		$model = JModelLegacy::getInstance('action','AwardpackageModel');
		return $model->info($id);
	}	
	
	function registered_users($package_id){
		$model = $this->getModelName('SearchEngine');
		return $model->registered_users($package_id);
	}
	
	function registered_quiz($package_id){
		$model = $this->getModelName('quiz');
		return $model->registered_quiz($package_id);
	}
	
	function registered_survey($package_id){
		$model = $this->getModelName('surveys');
		return $model->registered_survey($package_id);
	}
	
	function registered_donation($package_id) {
		$model = $this->getModelName('donation');
		return $model->registered_donation($package_id);
	}
	
	function registered_prize($package_id) {
		$model = $this->getModelName('prize');		
		return $model->registered_prize($package_id);
	}
	
	function getModelName($name){
		$model = JModelList::getInstance($name,'SearchEngineModel');
		return $model;
	}
	
    protected function addStyleSheet() {    	
        $doc = JFactory::getDocument();      
        $url = JURI::base() . 'components/com_searchengine/assets/';
        $doc->addStyleSheet($url . 'css/jquery-ui.min.css');
        $doc->addScript($url . 'js/jquery-1.8.3.js');
        $doc->addScript($url . 'js/jquery.ui.core.min.js');
        $doc->addScript($url . 'js/jquery.ui.widget.min.js');
        $doc->addScript($url . 'js/jquery.ui.tabs.min.js');
		$doc->addScript($url . 'js/jquery_003.js');
		$doc->addScript($url . 'js/jquery_002.js');
		$doc->addScript($url . 'js/highlight.js');
		$doc->addScript($url . 'js/responsive-tables.js');		
		$doc->addStyleSheet($url . 'css/style.css');
		$doc->addStyleSheet($url . 'css/collapsible.css');
		$doc->addStyleSheet($url . 'css/responsive-tables.css');
		$doc->addStyleSheet(JURI::base().'/media/jui/css/bootstrap.min.css');
    }

}
