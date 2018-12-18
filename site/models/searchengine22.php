<?php
//Redirect access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
//jimport(JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'giftcode.php');
class SearchEngineModelSearchEngine extends JModelLegacy{

	public function getInfo(){
	}

	public function addInfo($data){
		$query = "INSERT INTO #__se_search_list(user_ip,keyword,user_id,url,url_clicked,package_id,user_ip_int,uniqueid) VALUES('$data[user_ip]','$data[keyword]','$data[user_id]','$data[url]','$data[url_clicked]','$data[package_id]',INET_ATON('$data[user_ip]'),'$data[uniqueid]')";
		$db 	= JFactory::getDbo();
		$db->setQuery($query);
		$db->query();
		$ret = $db->insertid();
		return $ret;
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

		$query = "INSERT INTO #__se_search_list_urls(user_ip,user_id,url_clicked,package_id,user_ip_int,uniqueid,url_id,url_type,clicked_date_time) VALUES('$data[user_ip]','$data[user_id]','$data[url_clicked]','$data[package_id]',INET_ATON('$data[user_ip]'),'$data[uniqueid]','$data[url_id]','$data[url_type]','$data[clicked_date_time]')";
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

	public function get_searchenginesrewardsdetail($id){


 $query = "

 select grpl.id, grpl.title, 'Survey Group' as gctype,0,
(select sum(e.complete_giftcode_quantity+e.incomplete_giftcode_quantity)
from #__survey_question_giftcode as e
where e.survey_id in ( select survey_id from #__se_surveygrouplist_surveys where surveygroup_id  = grpl.id ))  as gc
,'' as urls,'' as survey_key,1 as published
from #__se_surveygrouplist as grpl
left join  #__se_surveygrouplist_surveys aq on aq.surveygroup_id  = grpl.id
WHERE grpl.id = (select surveygroup from #__se_rewardlist where id =$id)
group by grpl.id

UNION ALL

select grpl.id, grpl.title, 'Quiz Group' as gctype,0,
(select sum(e.complete_giftcode_quantity+e.incomplete_giftcode_quantity)
from #__quiz_question_giftcode as e
where e.quiz_id in ( select quiz_id from #__se_quizgrouplist_quizs where quizgroup_id  = grpl.id ))  as gc
,'' as urls,'' as survey_key,1 as published
from #__se_quizgrouplist as grpl
left join  #__se_quizgrouplist_quizs aq on aq.quizgroup_id  = grpl.id
WHERE grpl.id = (select quizgroup from #__se_rewardlist where id =$id)
group by grpl.id


UNION ALL

select a.id, a.title, 'Url Group' as gctype,a.package_id, (select sum(gcqty)
from #__se_urlrewardlist_settings_gc
where urlrewardid=(select urlgroup from #__se_rewardlist where id =$id)) as gc ,
(select group_concat(concat(id,'-',title)) from #__se_urlgrouplist_urls where urlgroup_id=a.id) as urls,'' as survey_key,a.published
from #__se_urlrewardlist_settings_ugs augs
left join #__se_urlgrouplist a ON a.id=augs.urllist
where urlrewardid = (select urlgroup from #__se_rewardlist where id =$id)


UNION ALL




 select
					a.id, a.title, 'Survey' as gctype,a.package_id,  (select sum(e.complete_giftcode_quantity+e.incomplete_giftcode_quantity) from  #__survey_question_giftcode as e
where e.survey_id = a.id) as gc,'' as urls,survey_key,a.published

				from #__se_surveygrouplist_surveys  aq
				left join #__survey as a on a.id=aq.survey_id


				WHERE aq.surveygroup_id = (select surveygroup from #__se_rewardlist where id =$id)

UNION ALL

select

		a.id, a.title, 'Quiz' as gctype,a.package_id,  (select sum(e.complete_giftcode_quantity+e.incomplete_giftcode_quantity) from  #__quiz_question_giftcode as e
where e.quiz_id = a.id) as gc,'' as urls,'' as survey_key,a.published


				from #__se_quizgrouplist_quizs  aq
				left join #__quiz_quizzes as a on a.id=aq.quiz_id


				WHERE aq.quizgroup_id = (select quizgroup from #__se_rewardlist where id =$id)

";



$this->_db->setQuery($query);
return $this->_db->loadObjectList();


/*

select grpl.id, grpl.title, 'QuizGroup' as gctype,0,
(select sum(e.complete_giftcode_quantity+e.incomplete_giftcode_quantity)
from gzcs6_quiz_question_giftcode as e
where e.quiz_id in ( select quiz_id from gzcs6_se_quizgrouplist_quizs where quizgroup_id  = grpl.id ))  as gc
,'' as urls,'' as survey_key,1 as published ,grpl.title as grouptitle
from gzcs6_se_quizgrouplist as grpl
left join  gzcs6_se_quizgrouplist_quizs aq on aq.quizgroup_id  = grpl.id
WHERE grpl.id = (select quizgroup from gzcs6_se_rewardlist where id =35)
group by grpl.id;

select group_concat(concat(a.id,'-',a.title,'-',
(select sum(e.complete_giftcode_quantity+e.incomplete_giftcode_quantity)
from gzcs6_quiz_question_giftcode as e where e.quiz_id = a.id)) SEPARATOR '###') as aaa
from gzcs6_se_quizgrouplist_quizs aq
left join gzcs6_quiz_quizzes as a on a.id=aq.quiz_id
WHERE aq.quizgroup_id = (select quizgroup from gzcs6_se_rewardlist where id =35)

*/

/*
if (empty($this->_data)) {
            $this->_data = $this->_getList($query);
            }
            return $this->_data;
*/

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

//match number of words
$wherekgk .= " AND ".$keywordscount."=LENGTH(title) - LENGTH(REPLACE(title, ' ', ''))+1";
//echo $keywordscount;
//LENGTH(title) - LENGTH(REPLACE(title, ' ', ''))+1

		//$where = ' a.package_id = \'' .$packageId. '\' ';

		$where = ' a.package_id = a.package_id ';
		$where .= " AND keywordgroup in (select keywordgroup_id from #__se_keywordgrouplist_keywords as kgk
where ".$wherekgk.") ";

		if( empty($options['userid']) && $packageId>0 ) {
			$this->serach($packageId);
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


        public function serach($package_id){

        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->quoteName('#__se_usergroups_fields'));
        $query->where($this->_db->quoteName('package_id') . "='" . $package_id . "'");

        $this->_db->setQuery($query);
        $criterias = $this->_db->loadObjectList();

        $email= array();
        $name = array();
        $street=array();
        $city=array();
        $state=array();
        $post_code=array();
        $country=array();
        $gender=array();
        $age=array();

        $user = JFactory::getUser();
	    $query = "DELETE FROM  #__se_user_usergroup_searchtemp  WHERE user_id ='$user->id'";
        $this->_db->setQuery($query);
        $this->_db->query();

        if(!$criterias)
            return null;

        $usergroup_id = array();
        if($criterias){
			$hasAll = false;

            foreach( $criterias as $criteria){
                $fnName= array();
                $ageFromTo= array();
                $alllocation= array();
                if(!isset($usergroup_id[$criteria->usergroup_id])){
                	$usergroup_id[$criteria->usergroup_id] = array('email'=>array(),'gender'=>array(),'name'=>array(),'age'=>array(),'location'=>array(), 'hasAll' =>false);
                }

                switch ($criteria->field) {
                    case 'email':
                        if(!empty($criteria->email)){
                            $usergroup_id[$criteria->usergroup_id]['email'][]= "'".strtolower($criteria->email)."'";
                        }
                    break;
                    case 'gender':
                        if(!empty($criteria->gender)){
                            $usergroup_id[$criteria->usergroup_id]['gender'][]= "'".$criteria->gender."'";
                        }
                    break;
                    case 'name':
                        if(!empty($criteria->firstname)){
                            $fnName[] = $criteria->firstname;

                        }

                        if(!empty($criteria->lastname)){
                              $fnName[] = $criteria->lastname;
                        }
                        $usergroup_id[$criteria->usergroup_id]['name'][] = $fnName;
                    break;

                    case 'age':
                        if(!empty($criteria->from_age) || $criteria->from_age==0 ){
                            $ageFromTo[] = $criteria->from_age;
                        }

                        if(!empty($criteria->to_age)){
                            $ageFromTo[] = $criteria->to_age;
                        }
                        $usergroup_id[$criteria->usergroup_id]['age'][] = $ageFromTo;
                    break;
                    case 'location':
                    	if($criteria->country =='All'){
                    		$hasAll = ture;
                    		$quiz_id[$criteria->quiz_id]['hasAll'] = true;
                    	}
                        if(!empty($criteria->country) && $criteria->country !='All'){
                            $alllocation[]= "'".$criteria->country."'";
                        }
                        if(!empty($criteria->state) && $criteria->state !='All'){
                            $alllocation[]= "'".$criteria->state."'";
                        }
                        $usergroup_id[$criteria->usergroup_id]['location'][] = $alllocation;
                    break;
                }

            }
        }

        if($usergroup_id){

        	foreach ($usergroup_id as $key => $survey) {
        		$name=  $survey['name'];
        		$email=  $survey['email'];
        		$gender=  $survey['gender'];
        		$age=  $survey['age'];
        		$location=  $survey['location'];
        		$hasAll = $quiz['hasAll'];

				$conditions0 = array();
				$conditions1 = array();
		        $conditions2 = array();
		        $conditions3 = array();
		        $conditions4 = array();
		        $query = "SELECT id FROM #__se_useraccounts";

	        if(count($email)>0){
	            $email=  array_unique($email);
	            $conditions0[]= $this->_db->quoteName('email') . 'IN('. implode(',',$email).')';
	        }

	         if(count($gender)>0){
	            $gender=  array_unique($gender);
	            $conditions1[]= $this->_db->quoteName('gender') . 'IN('. implode(',',$gender).')';
	        }

	        if($name){
	            foreach($name as $n){
	                if(count($n)>0){
	                    $_query =array();
	                    if(isset($n[0])){
	                        $_query[]= 'LOWER('.$this->_db->quoteName('firstname') .")='".strtolower($n[0])."'";
	                    }
	                    if(isset($n[1])){
	                        $_query[]= 'LOWER('.$this->_db->quoteName('lastname') .")='".strtolower($n[1])."'";
	                    }
	                    $conditions2[] =  '('. implode( ' AND ',$_query ) .')';
	                }
	            }
	        }

	        if($location && $hasAll==false){
	            foreach($location as $loc){
	                if(count($loc)>0){
	                    $_query =array();
	                    if(isset($loc[0])){
	                        $_query[]= 'LOWER('.$this->_db->quoteName('country') .")=".strtolower($loc[0]);
	                    }
	                    if(isset($loc[1])){
	                        $_query[]= 'LOWER('.$this->_db->quoteName('state') .")=".strtolower($loc[1]);
	                    }
	                    $conditions3[] =  implode( ' AND ',$_query );

	                }
	            }
	        }

	         if($age){
	            foreach($age as $ag){
	                if(count($ag)>0){
	                    $_query =array();
	                    if(isset($ag[0])){
	                        //$_query[]= 'TIMESTAMPDIFF(YEAR,'.$this->_db->quoteName('birthday').',CURDATE())' ."<='".$ag[0]."'";
	                         $_query[] =  " CASE
							WHEN `birthday` IS NULL THEN `birthday` IS NULL
							ELSE TIMESTAMPDIFF(YEAR,`birthday`,CURDATE()) >='".$ag[0]."'
							END";
	                    }
	                    if(isset($ag[1])){
	                        //$_query[]= 'TIMESTAMPDIFF(YEAR,'.$this->_db->quoteName('birthday').',CURDATE())' .">='".$ag[1]."'";
	                         $_query[] =  " CASE
								WHEN `birthday` IS NULL THEN `birthday` IS NULL
								ELSE TIMESTAMPDIFF(YEAR,`birthday`,CURDATE()) <='".$ag[1]."'
								END";
	                    }
	                    $conditions4[] =  '('. implode( ' AND ',$_query ) .')';
	                }
	            }
	        }



	        $query .= " WHERE package_id='$package_id'";
	        $sub_query1=array();
	        $sub_query2=array();
	        $qq=[];

	        if(count($conditions0)>0){
	            $sub_query1[] = ' ('.implode(' OR ', $conditions1 ).')';
	        }
	        if(count($conditions2)>0){
	            $sub_query1[] .= ' ('.implode(' OR ', $conditions2 ).')';
	        }


	        if(count($conditions1)>0){
	            $sub_query2[] = ' ('.implode(' OR ', $conditions1 ).')';
	        }
	        if(count($conditions3)>0){
	            $sub_query2[] .= ' ('.implode(' OR ', $conditions3 ).')';
	        }

	        if(count($conditions4)>0){
	            $sub_query2[] .= ' ('.implode(' OR ', $conditions4 ).')';
	        }

	        if(sizeof($sub_query1)>0 ) {
	            $qq[] =  implode(' OR ',  $sub_query1) ;
	        }

	        if(sizeof($sub_query2)>0 ) {
	            $qq[] =  ' ('.implode(' AND ',  $sub_query2) .')' ;
	        }

	        if(sizeof($qq)>0 ) {
	        	$query .= ' AND ('. implode(' OR ',  $qq) .')';
	    	}


	        ///echo str_replace('#__', 'raj_', $query);
	        //exit;

	        $this->_db->setQuery($query);
	        $result = $this->_db->loadObjectList();
	        $users_id= array();
	        $fields = array();
	        if( $result){
	            foreach ($result as $key1 => $value) {
	                $users_id[] = (int)  $value->id;
	                $fields[] = "('$key','$value->id')";
	            }

	             $query = 'INSERT INTO #__se_user_usergroup_searchtemp (se_usergroup_id,user_id) VALUES '. implode(',',$fields);
	             $this->_db->setQuery($query);
	             $this->_db->query();


	        }

        	}

		}
    }


    function get_total_giftcode_survey($id) {
		$this->_db = &JFactory::getDBO ();

		$query = "SELECT b.survey_id, b.complete_giftcode, b.incomplete_giftcode, b.complete_giftcode_quantity,
		           b.incomplete_giftcode_quantity
		FROM #__survey_question_giftcode b
		WHERE b.survey_id = '".$id."'  " ;
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}

    function get_total_giftcode_quiz($id) {
		$this->_db = &JFactory::getDBO ();
		$query = "SELECT b.quiz_id, b.complete_giftcode, b.incomplete_giftcode, b.complete_giftcode_quantity,
		           b.incomplete_giftcode_quantity
		FROM #__quiz_question_giftcode b
		WHERE b.quiz_id = '".$id."'  " ;
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}



    function exist_criteria_searchengine($data) {

        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $from_age = $data['from_age'];
        $to_age = $data['to_age'];
        $email = $data['email'];
        $state = $data['state'];
  /*      $city = $data->city;
        $street = $data->street;
        $post_code = $data->post_code;*/
        $country = $data['country'];
        $gender = $data['gender'];


		$db = JFactory::getDBO();
		$where = array();
        	$query = "SELECT a.* FROM `#__se_usergroup` as a INNER JOIN #__search_engine AS p ON p.package_id=a.package_id ";

		if(!empty($email) && $email != ''){
			$where[] = 'LOWER(a.email) = "'.strtolower($email).'"';
		}
		if(!empty($firstname) && $firstname != ''){
			$where[] = 'LOWER(a.firstname) = "'.strtolower($firstname).'"';
		}
		if(!empty($lastname) && $lastname != ''){
			$where[] = 'LOWER(a.lastname) = "'.strtolower($lastname).'"';
		}
		if(!empty($street) && $street != ''){
			$where[] = 'LOWER(a.street) = "'.strtolower($street).'"';
		}
		if(!empty($city) && $city != ''){
			$where[] = 'LOWER(a.city) = "'.strtolower($city).'"';
		}
		if(!empty($country) && $country != ''){
			$loc_where = '';

			if(!empty($state) && $state != ''){
				$loc_where .= ' ( ( LOWER(a.state) = "" OR LOWER(a.state) = "'.strtolower($state).'") AND ';
			} else {
				$loc_where .= ' ( LOWER(a.state) = "" AND ';
			}

			$loc_where .= 'LOWER(a.country) = "'.strtolower($country).'"';

			$loc_where .= ' ) ';

			$where[] = $loc_where;
		}
		if(!empty($gender) && $gender != ''){
			$where[] = 'LOWER(a.gender) = "'.strtolower($gender).'"';
		}

		$where = array_filter($where);
		if(count($where) > 0)
		{
			//$query = $query.' WHERE package_id = '.$package_id.' AND '.implode(' or ',$where);
			$query = $query.' WHERE '.implode(' or ',$where);
		}
		else{
			return null;
		}

		$query = $query.' ORDER BY a.package_id asc LIMIT 1';


        	$db->setQuery($query);
		$checkData = $db->loadObjectList();
		//echo $query;
		//echo '<pre>';print_r($data);print_r($checkData);
		//exit;
		return $checkData;
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


}
