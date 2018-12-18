<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
jimport( 'joomla.html.pagination' );

class SearchEngineViewse_SearchEngine extends JViewLegacy
{
	protected $pagination;

	function display($tpl = null)
	{
		JToolBarHelper::title(JText::_('Search Engine'), 'logo.png');
		JToolBarHelper::back('Back', 'index.php?option=com_searchengine&package_id=' . $package_id);

		$document = JFactory::getDocument();

		$document->addStyleSheet(JUri::base() . 'components/com_awardpackage/assets/css/jquery.ui.all.css');

		$document->addScript(JUri::base() . 'components/com_awardpackage/assets/js/jquery.min.js');
		$document->addScript('//code.jquery.com/ui/1.11.4/jquery-ui.min.js');
		$document->addScript(JUri::base() . 'components/com_awardpackage/assets/js/jquery.ui.core.js');
		$document->addScript(JUri::base() . 'components/com_awardpackage/assets/js/jquery.ui.widget.js');
		$document->addScript(JUri::base() . 'components/com_awardpackage/assets/js/jquery.ui.tabs.js');
		$document->addScript(JUri::base() . 'components/com_awardpackage/assets/js/tabs.js');
		$document->addScript('//services.iperfect.net/js/IP_generalLib.js');


 		$document->addStyleSheet(JURI::base() . 'components/com_awardpackage/assets/jqwidgets/styles/jqx.base.css');

		$document->addScript(JUri::base() . 'components/com_awardpackage/assets/jqwidgets/jqxcore.js');
		$document->addScript(JUri::base() . 'components/com_awardpackage/assets/jqwidgets/jqxsplitter.js');
		$document->addScript(JUri::base() . 'components/com_awardpackage/assets/jqwidgets/jqxbuttons.js');
		$document->addScript(JUri::base() . 'components/com_awardpackage/assets/jqwidgets/jqxpanel.js');
		$document->addScript(JUri::base() . 'components/com_awardpackage/assets/jqwidgets/jqxscrollbar.js');
		$document->addScript(JUri::base() . 'components/com_awardpackage/assets/js/jquery.countdownTimer.js');
		$document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/country.js');
		$document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/moment.js');
		$document->addScript(JURI::base() . 'components/com_awardpackage/assets/js/moment-timezone-with-data.min.js');

		$listData = null;

		$user = JFactory::getUser();
		$userId = $users->id;
		$packageId = $users->package_id; //this is searchengine package id
		$packageId = !empty ($users->package_id) ? $users->package_id : 0;

$keyword = '';
$keyword = JRequest::getVar('cid');
$history = JRequest::getVar('history');
$search_cat = JRequest::getVar('search_cat');
$type = array();
	if($keyword ){
     	foreach( $keyword  as $values ) {
        	$values = explode("|", $values);
        	$search_type = $values[0];
        	$search_keywords = $values[1];
        	$search_id = $values[2];
					$type[] = $values[0];
    		}
		}
		$size = count($type);
		$this->assignRef('search_type', $search_type);
		$this->assignRef('search_keywords', $search_keywords);
		$this->assignRef('search_id', $search_id);
		$this->assignRef('type', $type);
		$this->assignRef('search_cat', $search_cat);

		$jinput = JFactory::getApplication()->input;

		$ip = $jinput->server->get('REMOTE_ADDR', '', '');

		$user_ip = $jinput->server->get('REMOTE_ADDR');

		$this->assignRef('userId', $userId);
		$this->assignRef('packageId', $packageId);

		$model = & JModelLegacy::getInstance('searchengine','SearchEngineModel');
		$check_status = $model->getCheckValue();
		$list_search = $model->getSearchList();
		$Pagetotal = COUNT($list_search);
		$this->assignRef('check_status', $check_status->check_status);
		$this->assignRef('list_search', $list_search);
		$this->assignRef('list_data', $list_data);

		$limitstart =  isset($_POST['limitstart']) ? $_POST['limitstart'] : 0 ;
		$limit = 10;
		$pageLimit = 50;
		$pageNav = new JPagination( $Pagetotal, 0, $limit );
		$this->assignRef('pageNav', $pageNav);
		$this->assignRef('checkHistory', $checkHistory->check_status);

		$list_data = $model->getSearchListData($limitstart,$limit);

$checkHistory = $model->getHistory();
$update = $model->updateHistory($history);

			if($search_keywords){
			$search_params = array('q'=>$search_keywords);
			$options = array(
					'search_params'=>$search_params,
					'limit'=>$pageLimit,
					'limitstart'=>$limitstart);

					$this->assignRef('items', $return['serewardlist']);

			$search_result='';
			if($search_keywords){
				if($check_status==1)
				{
					$model =&JModelLegacy::getInstance('searchengine','SearchEngineModel');
					$result = $model->getSearchInfo($search_type,$search_keywords);
					foreach ( $result as $key){
						$result1 = $key->id;
					}
					if ($result1 > 0)
					{
						$date=	date("Y-m-d h:i:sa");
						$searchData = array (
							'keyword' => $search_keywords,
							'url' => '',
							'type' => $search_type,
							'userip' => $user_ip,
							'userId' => $user->id, // $user->id from joomla user table
							'packageId' => $packageId,
							'date' => $date,
							);

						$list_id = $result1;
						$model = &JModelLegacy::getInstance('searchengine','SearchEngineModel');
						$listData = $model->getUserSearchListDetail($list_id);
						$this->assignRef('listData', $listData,$list_id);
						foreach ($listData as $i => $row) {
						if ($search_type == 'web'){
						$search_result[] = array(
							'title' => $row->search_title,
							'link' => $row->search_link,
							'meta_desc' => $row->search_meta_description,
							'descr' => $row->search_description,
							'img_thumb_link' => '',
							'thumb_img' => '',
							'type' => $row->search_type,
							'result_id' => $list_id,
						);
						}
						elseif ($search_type == 'image'){ //if search Type is Images
							$search_result[] = array(
							'title' => $row->search_title,
							'link' => $row->search_link,
							'meta_desc' => $row->search_meta_description,
							'descr' => $row->search_description,
							'img_thumb_link' =>  $row->search_image_thumb,
							'thumb_img' => $row->search_image_thumb,
							'type' => $row->search_type,
							'result_id' => $list_id,
						);

						}
							elseif ($search_type == 'video'){ //if search Type is Videos
							$search_result[] = array(
							'title' => $row->search_title,
							'link' => $row->search_link,
							'meta_desc' => $row->search_meta_description,
							'descr' => $row->search_description,
							'img_thumb_link' => '',
							'thumb_img' => $row->search_image_thumb,
							'type' => $row->search_type,
							'result_id' => $list_id,
						);
						}
							elseif ($search_type == 'news'){//if search Type is News
							$search_result[] = array(
							'title' => $row->search_title,
							'link' => $row->search_link,
							'meta_desc' => $row->search_meta_description,
							'descr' => $row->search_description,
							'img_thumb_link' => '',
							'thumb_img' => $row->search_image_thumb,
							'type' => $row->search_type,
							'result_id' => $list_id,
						);
						}

						}
					}
					else
					{
							$in = JRequest::getVar('search'); // search keywords
					 // search type ie: image, video, news Or All
							$search_type = ! empty($search_type) ? $search_type : 'web';
							$in = str_replace(' ','+',$in); // space is a +
							//if search Type is All
							if ($search_type === 'web'){
								$url  = 'http://www.google.com/search?hl=en&tbo=d&site=&source=hp&q='.$in.'&oq='.$in.'&userip='.$user_ip.'&num=10&start='.$limitstart.'';
							}
							elseif ($search_type === 'image'){ //if search Type is Images
								$url  = 'http://www.google.com/search?hl=en&tbo=d&site=&source=hp&q='.$in.'&oq='.$in.'&tbm=isch'.'&userip='.$user_ip.'&num=10&start='.$limitstart.'';
							}
							elseif ($search_type === 'video'){ //if search Type is Videos
								$url  = 'http://www.google.com/search?hl=en&tbo=d&site=&source=hp&q='.$in.'&oq='.$in.'&tbm=vid'.'&userip='.$user_ip.'&num=10&start='.$limitstart.'';
							}
							elseif ($search_type === 'news'){//if search Type is News
								$url  = 'http://www.google.com/search?hl=en&tbo=d&site=&source=hp&q='.$in.'&oq='.$in.'&tbm=nws'.'&userip='.$user_ip.'&num=10&start='.$limitstart.'';
							}
							else{ //default All
								$url  = 'http://www.google.com/search?hl=en&tbo=d&site=&source=hp&q='.$in.'&oq='.$in.'&userip='.$user_ip.'&num=10&start='.$limitstart.'';
							}
							//$date=	date("d M Y");
							$date=	date("Y-m-d h:i:sa");
							$searchData = array (
								'keyword' => JRequest::getVar('search'),
								'url' => $url,
								'type' => $search_type,
								'userip' => $user_ip,
								'userId' => $user->id, // $user->id from joomla user table
								'packageId' => $packageId,
								'date' => $date,
								'keyword' => $search_keyword,
								);

							$model2 =&JModelLegacy::getInstance('searchengine','SearchEngineModel');
									$hasil2 = $model2->getSearchInfo($search_type,$search_keyword);
							if ($hasil2 ==  NULL ){
								//$result_id = $model2->addSearchInfo($searchData);
							}
							$html = file_get_html($url);

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

									$desc_txt = $descr->plaintext;

									$i++;
									$search_result[] = array(
										'title' => $title,
										'link' => $link,
										'descr' => $desc_txt,
										'meta_desc' => $dateDesc,
										'img_thumb_link' => $image_thumb_link,
										'thumb_img' => $thumb_img,
										'type' => $search_type,
										'result_id' => $result_id,
										'date' => $date,
										'keyword' => $search_keyword,
										);

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

									$desc_txt = $descr->plaintext;

									$i++;
									$search_result[] = array(
										'title' => $title,
										'link' => $link,
										'descr' => $desc_txt,
										'meta_desc' => '',
										'img_thumb_link' => $video_thumb_link,
										'thumb_img' => $thumb_img,
										'type' => $search_type,
										'result_id' => $result_id,
										'date' => $date,
										'keyword' => $search_keyword,
										);

								}
							}
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
										'descr' => '',
										'meta_desc' => '',
										'img_thumb_link' => '',
										'thumb_img' => $image_container,
										'type' => $search_type,
										'result_id' => $result_id,
										'date' => $date,
										'keyword' => $search_keyword,
										);
								}
							}

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

									$desc_txt = $descr->plaintext;

									$i++;
									$search_result[] = array(
										'title' => $title,
										'link' => $link,
										'meta_desc' => $site_url_txt,
										'descr' => $desc_txt,
										'img_thumb_link' => '',
										'thumb_img' => '',
										'type' => $search_type,
										'result_id' => $result_id,
										'date' => $date,
										'keyword' => $search_keyword,
										);
								}
							}
							$model3 =&JModelLegacy::getInstance('searchengine','SearchEngineModel');
							$hasil3 = $model3->getSearchDetailInfo($search_type,$search_keyword);
							if ($hasil3 ==  NULL ){
								//$result3 = $model3->addSearchDetailInfo($search_result);
								}
					}
				}
				else{

				$in = JRequest::getVar('search'); // search keywords
				$search_type = '';
				$search_type = JRequest::getVar('type'); // search type ie: image, video, news Or All
				$search_type = ! empty($search_type) ? $search_type : 'web';
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
				//$date=	date("d M Y");
				$date=	date("Y-m-d h:i:sa");
				$searchData = array (
					'keyword' => JRequest::getVar('search'),
					'url' => $url,
					'type' => $search_type,
					'userip' => $user_ip,
					'userId' => $user->id, // $user->id from joomla user table
					'packageId' => $packageId,
					'date' => $date,
				);

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
						$desc_txt = $descr->plaintext;

						$i++;
						$search_result[] = array(
							'title' => $title,
							'link' => $link,
							'descr' => $desc_txt,
							'meta_desc' => $dateDesc,
							'img_thumb_link' => $image_thumb_link,
							'thumb_img' => $thumb_img,
							'type' => $search_type,
							'result_id' => $result_id,
						);

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
						$desc_txt = $descr->plaintext;

						$i++;
						$search_result[] = array(
							'title' => $title,
							'link' => $link,
							'descr' => $desc_txt,
							'meta_desc' => '',
							'img_thumb_link' => $video_thumb_link,
							'thumb_img' => $thumb_img,
							'type' => $search_type,
							'result_id' => $result_id,
						);

					}
				}


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
						$i++;

						$search_result[] = array(
							'link' => $link,
							'img_src' => $image_container,
							'descr' => '',
							'meta_desc' => '',
							'img_thumb_link' => '',
							'thumb_img' => $image_container,
							'type' => $search_type,
							'result_id' => $result_id,
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

						$desc_txt = $descr->plaintext;

						$i++;
						$search_result[] = array(
							'title' => $title,
							'link' => $link,
							'meta_desc' => $site_url_txt,
							'descr' => $desc_txt,
							'img_thumb_link' => '',
							'thumb_img' => '',
							'type' => $search_type,
							'result_id' => $result_id,
						);


					}
				}

				}
			}
			$this->assignRef('searchData', $searchData);
			$this->assignRef('search_results', $search_result);

        }
		else if(JRequest::getVar('id')){
			$id = JRequest::getVar('id');
			$uid = JRequest::getVar('uid');

			$model1 =& JModelLegacy::getInstance('searchengine','SearchEngineModel');
			$return = $model1->get_searchenginesrewardsdetail($id);

			$this->assignRef('items', $return);

		}
		parent::display($tpl);
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
