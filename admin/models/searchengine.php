<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class SearchEngineModelSearchEngine extends JModelList
{
	protected function getListQuery()
	{

		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__search_engine')->order('created DESC');
		return $query;
	}

	function getCheckValue() {
			$db    = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id', 'check_status')));
			$query->from($db->quoteName('#__check'));
			$db->setQuery($query);
			$rows = $db->loadObject();
			return $rows;
	}


	public function getSearchList(){
$this->_db = &JFactory::getDBO();
$query = "SELECT * FROM `#__se_search_list` " ;
$this->_db->setQuery ( $query );
$result = $this->_db->loadObjectList();
return $result;
	}

	public function getHistory(){
$this->_db = &JFactory::getDBO();
$query = "SELECT check_status FROM `#__check` " ;
$this->_db->setQuery ( $query );
$result = $this->_db->loadObject();
return $result;
	}

	public function updateHistory($history){
$db = &JFactory::getDBO();
$query = "UPDATE  `#__check` SET check_status ='$history' " ;
$db->setQuery ( $query );
$db->execute();
	}

	public function getSearchListData($limitstart,$limit){
$this->_db = &JFactory::getDBO();
$query = "SELECT * FROM `#__se_search_list` ORDER BY `id` DESC limit $limitstart,$limit " ;
$this->_db->setQuery ( $query );
$result = $this->_db->loadObjectList();
return $result;
	}

	public function getSearchListTotal(){
$this->_db = &JFactory::getDBO();
$query = "SELECT * FROM `#__se_search_list`";
$this->_db->setQuery ( $query );
$result = $this->_db->loadObjectList();
return $result;
	}

public function getInfo($search_type,$search_keyword){
$this->_db = &JFactory::getDBO();
$query = "SELECT *
			FROM  `#__se_search_list`
			WHERE  `search_type` =  '".$search_type."'
			AND  `keyword` =  '".$search_keyword."' ";
$this->_db->setQuery ( $query );
$result = $this->_db->loadObjectList();
return $result;
}

//$QueryUpdate = "UPDATE `#__giftcode_category` SET published = 0 WHERE package_id IN (".implode(',',$data).")";

public function addInfo($data){
$query = "INSERT INTO #__se_search_list(user_ip,keyword,user_id,url,url_clicked,package_id,user_ip_int,uniqueid,search_type) VALUES('$data[user_ip]','$data[keyword]','$data[user_id]','$data[url]','$data[url_clicked]','$data[package_id]',INET_ATON('$data[user_ip]'),'$data[uniqueid]','$data[search_type]')";
$db 	= JFactory::getDbo();
$db->setQuery($query);
$db->query();
$ret = $db->insertid();
return $ret;
}

function add_quotes($str) {
	return sprintf("'%s'", $str);
}

public function getSearchInfo($search_type,$search_keyword){
$this->_db = &JFactory::getDBO();
$query = "SELECT *
			FROM  `#__se_user_search_list`
				WHERE `search_type`= '".$search_type."' AND `search_keyword` = '".$search_keyword."' ";
$this->_db->setQuery ( $query );
$result = $this->_db->loadObjectList();
return $result;
}
//			 WHERE `fixture_id` IN (" . implode(",", $opponents_id) . ")

public function addSearchInfo($data){
$query = "INSERT INTO `#__se_user_search_list` (search_type,search_keyword,userip_addr,user_id,package_id,created_at) VALUES('$data[type]','$data[keyword]','$data[userip]','$data[userId]','$data[packageId]','$data[date]')";
$db 	= JFactory::getDbo();
$db->setQuery($query);
$db->query();
$ret_id = $db->insertid();
return $ret_id;
}

	public function getSearchDetailInfo($search_type,$search_keyword){
$this->_db = &JFactory::getDBO();
$query = "SELECT *
			FROM  `#__se_search_result_detail`
			WHERE  `search_type` =  '".$search_type."'
			AND  `search_keyword` =  '".$search_keyword."' ";
$this->_db->setQuery ( $query );
$result = $this->_db->loadObjectList();
return $result;
}


public function addSearchDetailInfo($sresults){
$ret_ids = array();
foreach ($sresults as $data)
{
	$query = "INSERT INTO `#__se_search_result_detail` (list_id,search_title,search_description,search_meta_description,search_link,search_image_thumb,search_type,search_keyword,created_at) VALUES('$data[result_id]','$data[title]','$data[descr]','$data[meta_desc]','$data[link]','$data[thumb_img]','$data[type]','$data[keyword]','$data[date]')";
	$db 	= JFactory::getDbo();
	$db->setQuery($query);
	$db->query();
	$ret_ids[] = $db->insertid();

}
return $ret_ids;
}

public function addInfoUpdate($id,$uid){
$db = JFactory::getDBO();
		$fields = array(
								$db->quoteName('rewardid') . ' = ' . $db->quote($id),
								$db->quoteName('rewardid_clicked_date_time') . ' = ' . $db->quote(date("Y-m-d H:m:s"))
						);

				$conditions = array(
						$db->quoteName('uniqueid') . ' = ' . $db->quote($uid)
				);
				$query = $db->getQuery(true);
				$query->update($db->quoteName('#__se_search_list'))->set($fields)->where($conditions);
				$db->setQuery($query);
	$db->execute();
}

public function addInfoInsertUrl($data){
$query = "INSERT INTO `#__se_search_list_urls` (user_ip,user_id,url_clicked,package_id,user_ip_int,uniqueid,url_id,url_type,clicked_date_time) VALUES('$data[user_ip]','$data[user_id]','$data[url_clicked]','$data[package_id]',INET_ATON('$data[user_ip]'),'$data[uniqueid]','$data[url_id]','$data[url_type]','$data[clicked_date_time]')";
$db 	= JFactory::getDbo();
$db->setQuery($query);
$db->query();
}

		function getListQueryPagi($package_id,$limitstart,$limit)
		{
				$db     = JFactory::getDBO();
				$query  = $db->getQuery(true);
				$query->select('*');
				$query->from($db->QuoteName('#__se_keywordgrouplist'));
				$query->where("package_id='".$package_id."'");
				$db->setQuery($query,$limitstart,$limit);

				$rows = $db->loadObjectList();

				$subQuery = $db->getQuery(true);
				$subQuery->select('COUNT(*)');
				$subQuery->from($db->QuoteName('#__se_keywordgrouplist'));
				$subQuery->where("package_id='".$package_id."'");
				$db->setQuery($subQuery);
				$this->total = (int) $db->loadResult();

				return $rows;
		}

		public function get_searchenginesrewards($options = array(),$packageId,$userid){


	$return = array();


	$limitstart = !empty($options['limitstart']) ? $options['limitstart'] : 0;
	$limit = !empty($options['limit']) ? $options['limit'] : 20;

		$search_params = $options['search_params'];

		if(!empty($search_params['q'])){

			$keywords = explode(' ', $search_params['q']);
			$stopwords = ",a's,accordingly,again,allows,also,amongst,anybody,anyways,appropriate,aside,available,because,before,below,between,by,can't,certain,com,consider,corresponding,definitely,different,don't,each,else,et,everybody,exactly,fifth,follows,four,gets,goes,greetings,has,he,her,herein,him,how,i'm,immediate,indicate,instead,it,itself,know,later,lest,likely,ltd,me,more,must,nd,needs,next,none,nothing,of,okay,ones,others,ourselves,own,placed,probably,rather,regarding,right,saying,seeing,seen,serious,she,so,something,soon,still,t's,th,that,theirs,there,therein,they'd,third,though,thus,toward,try,under,unto,used,value,vs,way,we've,weren't,whence,whereas,whether,who's,why,within,wouldn't,you'll,yourself ,able,across,against,almost,although,an,anyhow,anywhere,are,ask,away,become,beforehand,beside,beyond,c'mon,cannot,certainly,come,considering,could,described,do,done,edu,elsewhere,etc,everyone,example,first,for,from,getting,going,had,hasn't,he's,here,hereupon,himself,howbeit,i've,in,indicated,into,it'd,just,known,latter,let,little,mainly,mean,moreover,my,near,neither,nine,noone,novel,off,old,only,otherwise,out,particular,please,provides,rd,regardless,said,says,seem,self,seriously,should,some,sometime,sorry,sub,take,than,that's,them,there's,theres,they'll,this,three,to,towards,trying,unfortunately,up,useful,various,want,we,welcome,what,whenever,whereby,which,whoever,will,without,yes,you're,yourselves,about,actually,ain't,alone,always,and,anyone,apart,aren't,asking,awfully,becomes,behind,besides,both,c's,cant,changes,comes,contain,couldn't,despite,does,down,eg,enough,even,everything,except,five,former,further,given,gone,hadn't,have,hello,here's,hers,his,however,ie,inasmuch,indicates,inward,it'll,keep,knows,latterly,let's,look,many,meanwhile,most,myself,nearly,never,no,nor,now,often,on,onto,ought,outside,particularly,plus,que,re,regards,same,second,seemed,selves,seven,shouldn't,somebody,sometimes,specified,such,taken,thank,thats,themselves,thereafter,thereupon,they're,thorough,through,together,tried,twice,unless,upon,uses,very,wants,we'd,well,what's,where,wherein,while,whole,willing,won't,yet,you've,zero,above,after,all,along,am,another,anything,appear,around,associated,be,becoming,being,best,brief,came,cause,clearly,concerning,containing,course,did,doesn't,downwards,eight,entirely,ever,everywhere,far,followed,formerly,furthermore,gives,got,happens,haven't,help,hereafter,herself,hither,i'd,if,inc,inner,is,it's,keeps,last,least,like,looking,may,merely,mostly,name,necessary,nevertheless,nobody,normally,nowhere,oh,once,or,our,over,per,possible,quite,really,relatively,saw,secondly,seeming,sensible,several,since,somehow,somewhat,specify,sup,tell,thanks,the,then,thereby,these,they've,thoroughly,throughout,too,tries,two,unlikely,us,using,via,was,we'll,went,whatever,where's,whereupon,whither,whom,wish,wonder,you,your,according,afterwards,allow,already,among,any,anyway,appreciate,as,at,became,been,believe,better,but,can,causes,co,consequently,contains,currently,didn't,doing,during,either,especially,every,ex,few,following,forth,get,go,gotten,hardly,having,hence,hereby,hi,hopefully,i'll,ignored,indeed,insofar,isn't,its,kept,lately,less,liked,looks,maybe,might,much,namely,need,new,non,not,obviously,ok,one,other,ours,overall,perhaps,presumably,qv,reasonably,respectively,say,see,seems,sent,shall,six,someone,somewhere,specifying,sure,tends,thanx,their,thence,therefore,they,think,those,thru,took,truly,un,until,use,usually,viz,wasn't,we're,were,when,whereafter,wherever,who,whose,with,would,you'd,yours,";
			$wheres2 = array();

			foreach ($keywords as $keyword){

				$keyword = preg_replace('/[^\p{L}\p{N}\s]/u', '', $keyword);

				if(strlen($keyword) > 2 && strpos($stopwords, ','.$keyword.',') === false){

					$wheres2[] = '
								a.title rlike \'[[:<:]]'.$this->_db->escape($keyword).'[[:>:]]\' or
								a.sedescription rlike \'[[:<:]]'.$this->_db->escape($keyword).'[[:>:]]\'';
				}
			}

		}




	$keywordscount=0;
	if(!empty($search_params['q'])){

	$keywords = explode(' ', $search_params['q']);
	$keywordscount=count($keywords);
	$stopwords = ",a's,accordingly,again,allows,also,amongst,anybody,anyways,appropriate,aside,available,because,before,below,between,by,can't,certain,com,consider,corresponding,definitely,different,don't,each,else,et,everybody,exactly,fifth,follows,four,gets,goes,greetings,has,he,her,herein,him,how,i'm,immediate,indicate,instead,it,itself,know,later,lest,likely,ltd,me,more,must,nd,needs,next,none,nothing,of,okay,ones,others,ourselves,own,placed,probably,rather,regarding,right,saying,seeing,seen,serious,she,so,something,soon,still,t's,th,that,theirs,there,therein,they'd,third,though,thus,toward,try,under,unto,used,value,vs,way,we've,weren't,whence,whereas,whether,who's,why,within,wouldn't,you'll,yourself ,able,across,against,almost,although,an,anyhow,anywhere,are,ask,away,become,beforehand,beside,beyond,c'mon,cannot,certainly,come,considering,could,described,do,done,edu,elsewhere,etc,everyone,example,first,for,from,getting,going,had,hasn't,he's,here,hereupon,himself,howbeit,i've,in,indicated,into,it'd,just,known,latter,let,little,mainly,mean,moreover,my,near,neither,nine,noone,novel,off,old,only,otherwise,out,particular,please,provides,rd,regardless,said,says,seem,self,seriously,should,some,sometime,sorry,sub,take,than,that's,them,there's,theres,they'll,this,three,to,towards,trying,unfortunately,up,useful,various,want,we,welcome,what,whenever,whereby,which,whoever,will,without,yes,you're,yourselves,about,actually,ain't,alone,always,and,anyone,apart,aren't,asking,awfully,becomes,behind,besides,both,c's,cant,changes,comes,contain,couldn't,despite,does,down,eg,enough,even,everything,except,five,former,further,given,gone,hadn't,have,hello,here's,hers,his,however,ie,inasmuch,indicates,inward,it'll,keep,knows,latterly,let's,look,many,meanwhile,most,myself,nearly,never,no,nor,now,often,on,onto,ought,outside,particularly,plus,que,re,regards,same,second,seemed,selves,seven,shouldn't,somebody,sometimes,specified,such,taken,thank,thats,themselves,thereafter,thereupon,they're,thorough,through,together,tried,twice,unless,upon,uses,very,wants,we'd,well,what's,where,wherein,while,whole,willing,won't,yet,you've,zero,above,after,all,along,am,another,anything,appear,around,associated,be,becoming,being,best,brief,came,cause,clearly,concerning,containing,course,did,doesn't,downwards,eight,entirely,ever,everywhere,far,followed,formerly,furthermore,gives,got,happens,haven't,help,hereafter,herself,hither,i'd,if,inc,inner,is,it's,keeps,last,least,like,looking,may,merely,mostly,name,necessary,nevertheless,nobody,normally,nowhere,oh,once,or,our,over,per,possible,quite,really,relatively,saw,secondly,seeming,sensible,several,since,somehow,somewhat,specify,sup,tell,thanks,the,then,thereby,these,they've,thoroughly,throughout,too,tries,two,unlikely,us,using,via,was,we'll,went,whatever,where's,whereupon,whither,whom,wish,wonder,you,your,according,afterwards,allow,already,among,any,anyway,appreciate,as,at,became,been,believe,better,but,can,causes,co,consequently,contains,currently,didn't,doing,during,either,especially,every,ex,few,following,forth,get,go,gotten,hardly,having,hence,hereby,hi,hopefully,i'll,ignored,indeed,insofar,isn't,its,kept,lately,less,liked,looks,maybe,might,much,namely,need,new,non,not,obviously,ok,one,other,ours,overall,perhaps,presumably,qv,reasonably,respectively,say,see,seems,sent,shall,six,someone,somewhere,specifying,sure,tends,thanx,their,thence,therefore,they,think,those,thru,took,truly,un,until,use,usually,viz,wasn't,we're,were,when,whereafter,wherever,who,whose,with,would,you'd,yours,";
	$wheres2 = array();
	$keywordi=1;
	foreach ($keywords as $keyword){

	$keyword = preg_replace('/[^\p{L}\p{N}\s]/u', '', $keyword);

	if(strlen($keyword) > 2 && strpos($stopwords, ','.$keyword.',') === false){

	if($keywordi == $keywordscount)
	{
	$wheres2[] = 'kgk.title rlike \''.$this->_db->escape($keyword).'\'';
	}
	else{
	$wheres2[] = 'kgk.title rlike \'[[:<:]]'.$this->_db->escape($keyword).'[[:>:]]\'';
	}
	}
	$keywordi=$keywordi+1;
	}

	if(!empty($wheres2)){

	$wheres[] = '('.implode(') and (', $wheres2).')';
	//$wheres[] = '('.implode(') or (', $wheres2).')';
	}
	}

	$wherekgk = '('.implode(' ) and (', $wheres).')';
	$keywordscount=10;
	//match number of words
	$wherekgk .= " AND ".$keywordscount."=LENGTH(kgk.title) - LENGTH(REPLACE(kgk.title, ' ', ''))+1";
	//echo $keywordscount;
	//LENGTH(title) - LENGTH(REPLACE(title, ' ', ''))+1

	//$where = ' a.package_id = \'' .$packageId. '\' ';

	$where = ' a.package_id = a.package_id ';
	$where .= " AND keywordgroup in (select keywordgroup_id from #__se_keywordgrouplist_keywords as kgk
	where ".$wherekgk.") ";

	if( empty($options['userid']) && $packageId>0 ) {
	$this->search($packageId);
	$where .= ' AND  a.usergroup IN (SELECT us.se_usergroup_id FROM #__se_user_usergroup_searchtemp us) ';
	//inner join is not working, so we hame to make it to in condition.
	}

	$query = '
		select
			a.id, a.title, a.sedescription,a.package_id, a.created, a.usergroup,
			u.id as created_by, u.username as created_by_alias, u.email,
																		(select sum(complete_giftcode_quantity)+sum(incomplete_giftcode_quantity)
	from #__quiz_question_giftcode where quiz_id in (select quiz_id from #__se_quizgrouplist_quizs
	where quizgroup_id=a.quizgroup) ) as quizgifcodesum,
																		(select sum(complete_giftcode_quantity)+sum(incomplete_giftcode_quantity)
	from #__survey_question_giftcode where survey_id in (select survey_id from #__se_surveygrouplist_surveys
	where surveygroup_id=a.surveygroup) ) as surveygifcodesum,
																		(select sum(gcqty) from #__se_urlrewardlist_settings_gc where urlrewardid=a.urlgroup) as urlrewardlistgifcodesum,
			(select count(*) from #__se_urlrewardlist_settings_ugs where urlrewardid=a.urlgroup) as urlgroupsinurlrewardlistcount


	,(select count(*) from  #__se_surveygrouplist_surveys
	LEFT JOIN #__survey s on s.id = #__se_surveygrouplist_surveys.survey_id
	where surveygroup_id=(select surveygroup from #__se_rewardlist where id=a.id)
	AND s.published=1) as surveycount,
	(select count(*) from  #__se_quizgrouplist_quizs
	LEFT JOIN #__quiz_quizzes q on q.id = #__se_quizgrouplist_quizs.quiz_id
	where quizgroup_id=(select quizgroup from #__se_rewardlist where id=a.id)
	AND q.published=1) as quizcount,
	(select count(*) from #__se_urlrewardlist_settings_ugs
	LEFT JOIN #__se_urlgrouplist ON #__se_urlgrouplist.id=#__se_urlrewardlist_settings_ugs.urllist
	WHERE urlrewardid  = (select urlgroup from #__se_rewardlist where id=a.id)
	AND #__se_urlgrouplist.published=1) as urlgroupcount


		from
			#__se_rewardlist as a
		left join
			#__users as u on a.created_by = u.id';

	//if( empty($options['userid']) ) {
	//$query .= ' INNER JOIN #__se_user_usergroup_searchtemp us ON us.se_usergroup_id = a.usergroup';
	//}

	$query .=' where
			'.$where;




	$this->_db->setQuery($query, $limitstart, $limit);
	$return['serewardlist'] = $this->_db->loadObjectList('id');



	/************ pagination *****************/
	$subQuery = '
		select
			count(*)
		from
			#__se_rewardlist as a
		left join
			#__users as u on a.created_by = u.id';

	//if(empty($options['userid']) ) {
										//$query .= ' INNER JOIN #__se_user_usergroup_searchtemp us ON us.se_usergroup_id = a.usergroup';
	//}

	$subQuery .=' where
			'.$where;


	$this->_db->setQuery($subQuery);
	$this->total = (int) $this->_db->loadResult();




	return $return;
	}

	function getUserSearchListDetail($list_id) {
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id,list_id,search_title,search_description,search_meta_description,search_link,search_image_thumb,search_type,created_at');
		$query->from('#__se_search_result_detail');
		$query->where($db->quoteName('list_id')." = ".$db->quote($list_id));
		$db->setQuery($query,$limitstart,$limit);
		$rows = $db->loadObjectList();
		return $rows;
	}

	function registered_users($package_id){
		$db 	= JFactory::getDBO();

		$query 	= $db->getQuery(TRUE);

		$query->select("*");

		$query->from("#__se_useraccounts as a");

        $query->join('INNER', $db->quoteName('#__users', 'b') . ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.id') . ')');

		$query->where("a.package_id='".$package_id."'");

		$db->setQuery($query);

		$accounts = $db->loadObjectList();
		$results = array();
		foreach ($accounts as $account){
			if(	$this->is_registered_group_by_name($package_id, $account->firstname, $account->lastname) ||
				$this->is_registered_group_by_email($package_id, $account->email) ||
				$this->is_registered_group_by_age($package_id, $account->birthday) ||
				$this->is_registered_group_by_gender($package_id, $account->gender) ||
				$this->is_registered_group_by_location($package_id, $account->street, $account->city,
							$account->state, $account->post_code, $account->country)){
				$results[]= $account;
			}
		}
		return count($results);
	}

	function is_registered_group_by_name($package_id, $firstname, $lastname){
		$query = "
				select * from #__se_usergroup where
				(
					lower(firstname) like '%".strtolower($firstname)."%' or
					lower(lastname) like '%".strtolower($lastname)."%'
				) and package_id = '".$package_id."'
			";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		if(!empty($result)){
			return true;
		}
		return false;
	}

	function is_registered_group_by_email($package_id, $email){
		$query = "
				select * from #__se_usergroup where
				lower(email) like '%".$email."%'
				and package_id = '".$package_id."'
			";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		if(!empty($result)){
			return true;
		}
		return false;
	}

	function is_registered_group_by_age($package_id, $birthday){
		$query = "
				SELECT * FROM (
				SELECT FLOOR((DATEDIFF('".$birthday."', NOW()) / 360 )) AS age
				FROM #__se_useraccounts WHERE package_id = '".$package_id."'
				) dat
				INNER JOIN #__se_usergroup au ON dat.age >= au.`from_age` AND dat.age <= au.`to_age`
				AND au.`package_id` = '".$package_id."'
			";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		if(!empty($result)){
			return true;
		}
		return false;
	}

	function is_registered_group_by_gender($package_id, $gender){
		$query = "
				select * from #__se_usergroup where
				lower(gender) like '%".$gender."%'
				and package_id = '".$package_id."'
			";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		if(!empty($result)){
			return true;
		}
		return false;
	}

	function is_registered_group_by_location($package_id, $street, $city="", $state="", $post_code="", $country="") {
		$query = "
				select * from #__se_usergroup where
				(lower(street) like '%".strtolower($street)."%'
				 or lower(city) like '%".strtolower($city)."%'
				 or lower(state) like '%".strtolower($state)."%'
				 or lower(post_code) like '%".strtolower($post_code)."%'
				 or lower(country) like '%".strtolower($country)."%')
				and package_id = '".$package_id."'
			";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		if(!empty($result)){
			return true;
		}
		return false;
	}

	public function getSearEnginePackage($package_id){
		$db			= 	JFactory::getDBO();
		$query		= 	$db->getQuery(TRUE);
		$query->select("*");
		$query->from('#__search_engine');
		$query->where("package_id='".$package_id."'");
		$db->setQuery($query);
		$row		= 	$db->loadObject();
		return $row;
	}

	function isAuctionCompInstalled(){
		$db = JFactory::getDbo();
		$db->setQuery("SELECT enabled FROM #__extensions WHERE name = 'com_auctionfactory'");
		$is_enabled = $db->loadResult();
		return $is_enabled;
	}

	function addIp($data){
		$db = JFactory::getDbo();
		$db->setQuery("INSERT INTO #__se_ip (date_from,date_to,ip_from,ip_to,package_id) VALUES ('".$data['date_from']."','".$data['date_to']."','".$data['ip_from']."','".$data['ip_to']."','".JRequest::getVar('package_id')."')");
		return $db->query();
	}

	function getIpList($package_id){
		$db			= 	JFactory::getDBO();
		$query		= 	$db->getQuery(TRUE);
		$query->select("*");
		$query->from('#__se_ip');
		$query->where("package_id='".$package_id."'");

		$db->setQuery($query);
		$rows		= 	$db->loadObjectList();
		return $rows;
	}

	function deleteIp($data) {
		$db = JFactory::getDBO();
		$query = "DELETE FROM `#__se_ip` WHERE id IN (" . implode(',', $data) . ")";
		$db->setQuery($query);
		$db->query();
    	}


	function addKey($data){
		$db = JFactory::getDbo();
		$db->setQuery("INSERT INTO #__se_file_keyword (date_from,date_to,package_id,keyword_list) VALUES ('".$data['date_from1']."','".$data['date_to1']."','".JRequest::getVar('package_id')."','".$data['keyword_list']."')");
		$db->query();
		$fileid = $db->insertid();

		$uploadPath = JPATH_SITE.DS.'components'.DS.'com_searchengine'.DS.'data'.DS.'keywords'.DS.$data['keyword_list'];

		$file = fopen($uploadPath,"r");

		while(! feof($file))
		  {
			$textvalues=trim(fgets($file));
			if(!empty($textvalues))
			{
				$db = JFactory::getDbo();
				$db->setQuery("INSERT INTO #__se_file_keyword_list (fileid,textvalues) VALUES ('".$fileid."','".trim($textvalues)."')");
				$db->query();
			}
		  }

		fclose($file);

	}

	function getKeywordFile($id){
		$db			= 	JFactory::getDBO();
		$query		= 	$db->getQuery(TRUE);
		$query->select("*");
		$query->from('#__se_file_keyword');
		$query->where("id='".$id."'");

		$db->setQuery($query);
		$row		= 	$db->loadObject();
		return $row;
	}
	function getKeywordsList($package_id){
		$db			= 	JFactory::getDBO();
		$query		= 	$db->getQuery(TRUE);
		$query->select("*");
		$query->from('#__se_file_keyword');
		$query->where("package_id='".$package_id."'");

		$db->setQuery($query);
		$rows		= 	$db->loadObjectList();
		return $rows;
	}

	function deleteKeyword($data) {
		$db = JFactory::getDBO();
		$query = "DELETE FROM `#__se_file_keyword` WHERE id IN (" . implode(',', $data) . ")";
		$db->setQuery($query);
		$db->query();

		$db = JFactory::getDBO();
		$query = "DELETE FROM `#__se_file_keyword_list` WHERE fileid IN (" . implode(',', $data) . ")";
		$db->setQuery($query);
		$db->query();
    	}

	function addUrl($data){
		$db = JFactory::getDbo();
		$db->setQuery("INSERT INTO #__se_file_url(date_from,date_to,package_id,url_list) VALUES ('".$data['date_from2']."','".$data['date_to2']."','".JRequest::getVar('package_id')."','".$data['url_list']."')");
		$db->query();

		$fileid = $db->insertid();

		$uploadPath = JPATH_SITE.DS.'components'.DS.'com_searchengine'.DS.'data'.DS.'urls'.DS.$data['url_list'];

		$file = fopen($uploadPath,"r");

		while(! feof($file))
		  {
			$textvalues=trim(fgets($file));

			//!filter_var($textvalues, FILTER_VALIDATE_URL) === false
			$validurl=false;

			if (strpos($textvalues, 'http://') !== false) {
			    $validurl=true;
			}

			if (strpos($textvalues, 'https://') !== false) {
			    $validurl=true;
			}

			if (strpos($textvalues, 'www.') !== false) {
			    $validurl=true;
			}

			if(!empty($textvalues) && $validurl)
			{
				$db = JFactory::getDbo();
				$db->setQuery("INSERT INTO #__se_file_url_list (fileid,textvalues) VALUES ('".$fileid."','".trim($textvalues)."')");
				$db->query();
			}
		  }

		fclose($file);
	}

	function getUrlFile($id){
		$db			= 	JFactory::getDBO();
		$query		= 	$db->getQuery(TRUE);
		$query->select("*");
		$query->from('#__se_file_url');
		$query->where("id='".$id."'");

		$db->setQuery($query);
		$row		= 	$db->loadObject();
		return $row;
	}
	function getUrlList($package_id){
		$db			= 	JFactory::getDBO();
		$query		= 	$db->getQuery(TRUE);
		$query->select("*");
		$query->from('#__se_file_url');
		$query->where("package_id='".$package_id."'");

		$db->setQuery($query);
		$rows		= 	$db->loadObjectList();
		return $rows;
	}

	function deleteUrl($data) {
		$db = JFactory::getDBO();
		$query = "DELETE FROM `#__se_file_url` WHERE id IN (" . implode(',', $data) . ")";
		$db->setQuery($query);
		$db->query();

		$db = JFactory::getDBO();
		$query = "DELETE FROM `#__se_file_url_list` WHERE fileid IN (" . implode(',', $data) . ")";
		$db->setQuery($query);
		$db->query();
    	}

	function getSummarySearch($package_id,$searchview){
		$db			= 	JFactory::getDBO();
		$query		= 	$db->getQuery(TRUE);
		$query->select("*");
		$query->from('#__se_search_list');
		$query->where("package_id='".$package_id."'");

		$db->setQuery($query);
		$rows		= 	$db->loadObjectList();
		return $rows;
	}

	function getUrlClickedSummary($package_id,$searchview){

		$where1 = " and package_id='".$package_id."'";
		$where2 = " and package_id='".$package_id."'";

		$where1main = " ";

		if($searchview == "ip"){
			$IpLists = $this->getIpList($package_id);

			$ipwhere1 = "";
			$ipwhere2 = "";

			$i=0;
			foreach($IpLists as $IpList)
			{
				if($i>0){$ipwhere1 .= " OR ";$ipwhere2 .= " OR ";}

				$ipwhere1 .= " (sl.date_time between '".$IpList->date_from."' and '".$IpList->date_to."' ";
				$ipwhere1 .= " and sl.user_ip_int between INET_ATON('$IpList->ip_from') and INET_ATON('$IpList->ip_to')) ";

				$ipwhere2 .= " (slu.clicked_date_time between '".$IpList->date_from."' and '".$IpList->date_to."' ";
				$ipwhere2 .= " and slu.user_ip_int between INET_ATON('$IpList->ip_from') and INET_ATON('$IpList->ip_to')) ";

				$i++;
			}

			if(!empty($ipwhere1))
			{
				$where1=$where1." and ".$ipwhere1;

				$where1main .= " and "." (sesl.date_time between '".$IpList->date_from."' and '".$IpList->date_to."' ";
				$where1main .= " and sesl.user_ip_int between INET_ATON('$IpList->ip_from') and INET_ATON('$IpList->ip_to')) ";

			}
			if(!empty($ipwhere2))
			{
				$where2=$where2." and ".$ipwhere2;
			}
		}
		else if($searchview == "url"){
			$UrlLists = $this->getUrlList($package_id);

			$urlwhere1 = "";
			$urlwhere2 = "";

			$i=0;
			foreach($UrlLists as $UrlList)
			{
				if($i>0){$urlwhere1 .= " OR ";$urlwhere2 .= " OR ";}

				$urlwhere1 .= " (sl.date_time between '".$UrlList->date_from."' and '".$UrlList->date_to."') ";
				$urlwhere2 .= " (slu.clicked_date_time between '".$UrlList->date_from."' and '".$UrlList->date_to."' ";
				$urlwhere2 .= " and slu.url_clicked rlike (select GROUP_CONCAT(textvalues SEPARATOR  '|') from #__se_file_url_list where fileid=$UrlList->id ))";

				$i++;
			}

			if(!empty($urlwhere1))
			{
				$where1=$where1." and ".$urlwhere1;

				$where1main .= " and "." (sesl.date_time between '".$UrlList->date_from."' and '".$UrlList->date_to."') ";

			}
			if(!empty($urlwhere2))
			{
				$where2=$where2." and ".$urlwhere2;
			}
		}
		else if($searchview == "keyword"){
			$KeywordLists = $this->getKeywordsList($package_id);

			$kwwhere1 = "";
			$kwwhere2 = "";

			$i=0;
			foreach($KeywordLists as $KeywordList)
			{
				if($i>0){$kwwhere1 .= " OR ";$kwwhere2 .= " OR ";}

				$kwwhere1 .= " (sl.date_time between '".$KeywordList->date_from."' and '".$KeywordList->date_to."' ";
				$kwwhere1 .= " and sl.keyword rlike (select GROUP_CONCAT(textvalues SEPARATOR  '|') from #__se_file_keyword_list where fileid=$KeywordList->id ))";
				$kwwhere2 .= " (slu.clicked_date_time between '".$KeywordList->date_from."' and '".$KeywordList->date_to."') ";


				$i++;
			}

			if(!empty($kwwhere1))
			{
				$where1=$where1." and ".$kwwhere1;

				$where1main .= " and "." (sesl.date_time between '".$KeywordList->date_from."' and '".$KeywordList->date_to."' ";
				$where1main .= " and sesl.keyword rlike (select GROUP_CONCAT(textvalues SEPARATOR  '|') from #__se_file_keyword_list where fileid=$KeywordList->id ))";
			}
			if(!empty($kwwhere2))
			{
				$where2=$where2." and ".$kwwhere2;
			}
		}

		$db = JFactory::getDBO();
/*
		$query = "select
sua.id,sua.email,sua.firstname,sua.lastname,sua.country,sua.gender,
(select user_ip from #__se_search_list sl where sl.user_id = sua.id and user_ip!='' limit 1 ) as user_ip,
(select count(*) from #__se_search_list sl where sl.user_id = sua.id ".$where1." ) as totalkeywordinput,
(select count(*) from #__se_search_list_urls slu where slu.user_id = sua.id ".$where2." ) as totalurlclicked
from #__se_useraccounts sua WHERE  package_id=$package_id";
*/

		$query = "select
sua.id,sua.email,sua.firstname,sua.lastname,sua.country,sua.gender,user_ip,
(select count(*) from #__se_search_list sl where sl.user_id = sua.id ".$where1."  and sl.user_ip=sesl.user_ip ) as totalkeywordinput,
(select count(*) from #__se_search_list_urls slu where slu.user_id = sua.id ".$where2." and slu.user_ip=sesl.user_ip ) as totalurlclicked
from #__se_search_list sesl
left join #__se_useraccounts sua ON sua.id=sesl.user_id
WHERE  user_id>0  AND sesl.package_id=$package_id  ".$where1main."
group by user_ip";




		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getUrlClickedList($package_id,$searchview,$limitstart,$limit){

		$where1 = " where slu.package_id='".$package_id."'";

		if($searchview == "ip"){
			$IpLists = $this->getIpList($package_id);

			$ipwhere1 = "";
			$ipwhere2 = "";

			$i=0;
			foreach($IpLists as $IpList)
			{
				if($i>0){$ipwhere1 .= " OR ";$ipwhere2 .= " OR ";}

				$ipwhere1 .= " (sl.date_time between '".$IpList->date_from."' and '".$IpList->date_to."' ";
				$ipwhere1 .= " and sl.user_ip_int between INET_ATON('$IpList->ip_from') and INET_ATON('$IpList->ip_to')) ";

				$ipwhere2 .= " (slu.clicked_date_time between '".$IpList->date_from."' and '".$IpList->date_to."' ";
				$ipwhere2 .= " and slu.user_ip_int between INET_ATON('$IpList->ip_from') and INET_ATON('$IpList->ip_to')) ";

				$i++;
			}

			if(!empty($ipwhere1))
			{
				$where1=$where1." and (".$ipwhere1." ) ";
			}
			if(!empty($ipwhere2))
			{
				$where1=$where1." and (".$ipwhere2." ) ";
			}
		}
		else if($searchview == "url"){
			$UrlLists = $this->getUrlList($package_id);

			$urlwhere1 = "";
			$urlwhere2 = "";

			$i=0;
			foreach($UrlLists as $UrlList)
			{
				if($i>0){$urlwhere1 .= " OR ";$urlwhere2 .= " OR ";}

				$urlwhere1 .= " (sl.date_time between '".$UrlList->date_from."' and '".$UrlList->date_to."') ";
				$urlwhere2 .= " (slu.clicked_date_time between '".$UrlList->date_from."' and '".$UrlList->date_to."' ";
				$urlwhere2 .= " and slu.url_clicked rlike (select GROUP_CONCAT(textvalues SEPARATOR  '|') from #__se_file_url_list where fileid=$UrlList->id ))";

				$i++;
			}

			if(!empty($urlwhere1))
			{
				$where1=$where1." and (".$urlwhere1." ) ";
			}
			if(!empty($urlwhere2))
			{
				$where1=$where1." and (".$urlwhere2." ) ";
			}
		}
		else if($searchview == "keyword"){
			$KeywordLists = $this->getKeywordsList($package_id);

			$kwwhere1 = "";
			$kwwhere2 = "";

			$i=0;
			foreach($KeywordLists as $KeywordList)
			{
				if($i>0){$kwwhere1 .= " OR ";$kwwhere2 .= " OR ";}

				$kwwhere1 .= " (sl.date_time between '".$KeywordList->date_from."' and '".$KeywordList->date_to."' ";
				$kwwhere1 .= " and sl.keyword rlike (select GROUP_CONCAT(textvalues SEPARATOR  '|') from #__se_file_keyword_list where fileid=$KeywordList->id ))";
				$kwwhere2 .= " (slu.clicked_date_time between '".$KeywordList->date_from."' and '".$KeywordList->date_to."') ";


				$i++;
			}

			if(!empty($kwwhere1))
			{
				$where1=$where1." and (".$kwwhere1." ) ";
			}
			if(!empty($kwwhere2))
			{
				$where1=$where1." and (".$kwwhere2." ) ";
			}
		}


		$db = JFactory::getDBO();
		 $query = "select
slu.user_ip,sua.email,sl.date_time,sl.keyword,slu.clicked_date_time,slu.url_clicked,sl.uniqueid
from #__se_search_list_urls slu
left join #__se_search_list sl on sl.uniqueid=slu.uniqueid
left join #__se_useraccounts sua on sua.id = sl.user_id
".$where1;
		$db->setQuery($query,$limitstart,$limit);

        $rows = $db->loadObjectList();


		 $subQuery = "select COUNT(*)
from #__se_search_list_urls slu
left join #__se_search_list sl on sl.uniqueid=slu.uniqueid
left join #__se_useraccounts sua on sua.id = sl.user_id
".$where1;

        $db->setQuery($subQuery);
        $this->total = (int) $db->loadResult();

		return $rows;
	}
}

?>
