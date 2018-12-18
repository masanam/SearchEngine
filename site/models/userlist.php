<?php

// no direct access
defined('_JEXEC') or die();

jimport('joomla.application.component.model');
class AwardpackageModelUserlist extends JModelLegacy {
	function __construct() {
		parent::__construct();
	}

	public function get_user_list($data, $package_id, $ids = array(),
	$limit = 5, $limitstart = 0) {
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

        $name ='';
		$dob ='';
		$ageFrom='';
		$ageTo ='';
		$gender ='';
		$city ='';
		$country ='';
		$accountId='';
		
		$filter_order = $app->getUserStateFromRequest('com_awardpackage.userlist.filter_order', 'filter_order', 'a.firstname', 'cmd' );
		$filter_order_dir = $app->getUserStateFromRequest('com_awardpackage.userlist.filter_order_dir', 'filter_order_Dir', 'DESC', 'word' );
		$limitstart = $app->getUserStateFromRequest( 'com_awardpackage.shoppingcreditcategory.limitstart', 'limitstart', $limitstart, 'int' );
		$limit = $app->input->getInt('limit', $limit);
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$order = " order by " . $filter_order . " " . $filter_order_dir;

		$query = "
				SELECT a.`ap_account_id` AS ap_account_id, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`package_id` AS package_id, a.`id` AS id, 
				a.`firstname` AS firstname, a.`lastname` AS lastname,
				a.`birthday` AS birthday, a.`gender` AS gender, a.`street` AS street, a.`city` AS city,
				a.`state` AS state, a.`post_code` AS post_code, a.`email` AS email, a.country AS country, 
				b.RegisterDate, b.username, b.password, a.state
				FROM `#__ap_useraccounts` a 
				INNER JOIN `#__users` b ON b.`id` = a.`id`
				WHERE package_id = '".$package_id."'
			" .
		($data['user_name'] != "" ? " AND ( (LOWER(a.`firstname`) LIKE '%".strtolower($data['user_name'])."%' OR LOWER(a.`lastname`) LIKE '%".strtolower($data['user_name'])."%' ) " : " AND (1=1") .
		($data['user_dob'] != "" ? " AND DATE(a.`birthday`) = '".$data['user_dob']."' " : "") .
		($data['accountId'] != "" ? " AND a.`id` = '".$data['accountId']."' " : "") .
		($data['user_age_from'] != "" && $data['user_age_to'] != "" ?
			" and YEAR( NOW( ) ) - YEAR( birthday ) between '".$data['user_age_from']."' and '".$data['user_age_to']."' " : "") .		
		($data['user_gender'] != "-1" && $data['user_gender'] != "" ? " AND lower(a.`gender`) = '".strtolower($data['user_gender'])."' " : "") .
		($data['user_city'] != "" ? " AND LOWER(a.`city`) = '".strtolower($data['user_city'])."'  " : "") .
		($data['user_country'] != "-1" && $data['user_country'] != "" ? " AND lower(a.`country`) = '".strtolower($data['user_country'])."'  " : "")
		;
		$query .= ' ) ';

		//echo '<pre>' . $query . '</pre>';
		//die();

		$this->_db->setQuery($query, $limitstart, $limit);

		$rows = $this->_db->loadObjectList();		
		
		$return['users'] = array();
		foreach ($rows as $row) {
			$row->all_transactions = $this->get_all_transaction($package_id);
			$row->funds = $this->get_funds($package_id);
			$row->donation = $this->get_donation($package_id);
			$row->giftcode = $this->get_giftcode($package_id);

			$usergroups = $this->getUserGroup($package_id, $row->firstname, $row->lastname, $row->email,
			$row->birthday, $row->gender, $row->street, $row->city, $row->state, $row->post_code, isset($row->country));
			if(!empty($usergroups)){
				$usergroup = $usergroups[0];
				$row->symbol_queue = $this->get_symbol_queue($usergroup->criteria_id);
				$row->presentation = $this->get_symbol_queue($usergroup->criteria_id);
			}
			$row->presentation = $this->get_presentationid($row->id,$package_id);
			$row->symbol_queue = $this->get_symbol_queue_detail($row->id);
			$row->shopping_credits = $this->get_shopping_credits($package_id);
			$row->quiz = $this->get_quiz($row->id, $package_id);
			$row->survey = $this->get_survey($row->id, $package_id);
			$return['users'][] = $row;
		}

		$query = "
				SELECT COUNT(*) FROM `#__ap_useraccounts` a WHERE a.`package_id` = '".$package_id."'
			" .
		($name != "" ? " AND (LOWER(a.`firstname`) LIKE '%".$name."%' OR LOWER(a.`lastname`) LIKE '%".$name."%' ) " : "") .
		($dob != "" ? " AND DATE(a.`birthday`) = '".$dob."' " : "") .
		($ageFrom != "" ? "" : "") .
		($ageTo != "" ? "" : "") .
		($gender != "" ? " AND a.`gender` = '".$gender."' " : "") .
		($city != "" ? " AND a.`city` = '".$city."'  " : "") .
		($country != "" ? " AND a.`country` = '".$country."'  " : "")
		;

		jimport('joomla.html.pagination');
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();

		$return['pagination'] = new JPagination( $total, $limitstart, $limit );
		$return['lists'] = array(
    			'limitstart'=>$limitstart,
    			'limit'=>$limit,
    			'order'=>$filter_order,
    			'order_dir'=>$filter_order_dir
		);
		return $return;
	}

//? " AND ( (LOWER(a.`firstname`) LIKE '%".strtolower($data['user_name'])."%' OR LOWER(a.`lastname`) LIKE '%".strtolower($data['user_name'])."%' ) " : " AND (1=1"))."
public function get_user($data,$limit, $limitstart){
$query = "	SELECT a.* 
					FROM #__ap_useraccounts a
					WHERE  LOWER(a.`firstname`) LIKE '%".strtolower($data['user_name'])."%'
				    LIMIT ".$limitstart.", ".$limit."
					";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
}

public function getTotalSymbol($userId,$status, $limit, $limitstart, $filter_start, $filter_end){
		$query = "	SELECT * 
					FROM #__gc_recieve_user  
					WHERE user_id='".$userId."' AND status='".$status."' 
				    LIMIT ".$limitstart.", ".$limit."
					";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	public function getSymbolSymbolPrizeAmount(){
		$query = "	SELECT a.symbol_prize_id, c.prize_name, c.prize_image, c.prize_value, b.symbol_name, b.symbol_image, b.pieces, b.cols, b.rows, d.presentation_id, d.status, c.unlocked_status 
					FROM #__symbol_symbol_prize a 
					LEFT JOIN #__symbol_symbol b ON b.symbol_id = a.symbol_id 
					LEFT JOIN #__symbol_prize c ON c.id = a.id
					INNER JOIN #__symbol_presentation d ON d.presentation_id = a.presentation_id";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	function getShoppingCredit(){
		$query = "select * from #__shopping_credit_from_donation ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getUserFromId($accountId){
		$query = " SELECT * FROM `#__symbol_queue` a WHERE a.`user_id` = '".$accountId."' ";
		$this->_db->setQuery($query);
		$users = $this->_db->loadObjectList ();
		if(!empty($users)) {
			return $users[0];
		} else {
			return null;
		}
	}
	
	public function getUserNameFromId($accountId){
		$query = " SELECT * FROM `#__ap_useraccounts` a WHERE a.id = ".$accountId." ";
		$this->_db->setQuery($query);
		$users = $this->_db->loadObjectList();
		if(!empty($users)) {
			return $users;
		} else {
			return null;
		}
	}

		public function get_presentationid($user_id,$package_id) {
	$query = "SELECT COUNT(*) AS count
		        FROM #__ap_useraccounts fu 				
				WHERE fu.`package_id` = '" .$package_id. "'  ";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList ();
		return $rows[0]->count;
	}
	
	public function get_quiz($user_id, $package_id) {
		$query = 'SELECT COUNT(*) AS count FROM #__quiz_responses qr INNER JOIN #__quiz_quizzes qq ON qq.id = qr.quiz_id AND qq.package_id = \''.$package_id.'\'
				WHERE qr.created_by = \''.$user_id.'\' ';
		$this->_db->setQuery($query);
		$quiz = $this->_db->loadObjectList ();
		return $quiz[0]->count;
	}
	
	

	public function get_survey($user_id, $package_id) {
		$query = "
				SELECT COUNT(*) AS COUNT FROM #__survey_responses sr INNER JOIN #__survey ps ON ps.id = sr.survey_id AND ps.package_id = '".$package_id."'
				WHERE sr.created_by = '".$user_id."'
			";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList ();
		return $rows[0]->COUNT;
	}

	public function get_all_transaction($package_id) {
	$query = "SELECT COUNT(*) AS count
		        FROM #__funding_user fu 
				INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
				INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
				WHERE fu.`package_id` = '" .$package_id. "' ";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList ();
		return $rows[0]->count;
	}

	public function get_funds($package_id) {
		$query = "SELECT COUNT(*) AS count
		        FROM #__funding_user fu 
				INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
				INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
				WHERE fu.`package_id` = '" .$package_id. "' AND fh.`transaction_type` = 'FUNDING'";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList ();
		return $rows[0]->count;
	}

	public function get_donation($package_id) {
		$query = "SELECT COUNT(*) AS count
		        FROM #__funding_user fu 
				INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
				INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
				WHERE fu.`package_id` = '" .$package_id. "' AND fh.`transaction_type` = 'DONATION'";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList ();
		return $rows[0]->count;
	}

	public function get_giftcode($package_id) {
		$query = "
				SELECT COUNT(*) AS count FROM #__giftcode_category 
				WHERE package_id = '".$package_id."'
			";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList ();
		return $rows[0]->count;
	}

    public function get_symbol_queue_detail($userid) {
		$query = "
			 select count(*) as count from #__gc_recieve_user where user_id = '".$userid."' 
			";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList ();
		return $rows[0]->count;
	}
	
	public function get_symbol_queue($usergroup) {
		$query = "
			 select count(*) as count from #__usergroup_presentation where usergroup = '".$usergroup."' 
			";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList ();
		return $rows[0]->count;
	}


public function getSymbolSymbolPrize($limit,$limitstart){
		$query = "	SELECT a.symbol_prize_id, c.prize_name, c.prize_image, c.prize_value, b.symbol_name, b.symbol_image, b.pieces, b.cols, b.rows, d.presentation_id, d.status, c.unlocked_status 
					FROM #__symbol_symbol_prize a 
					LEFT JOIN #__symbol_symbol b ON b.symbol_id = a.symbol_id 
					LEFT JOIN #__symbol_prize c ON c.id = a.id
					INNER JOIN #__symbol_presentation d ON d.presentation_id = a.presentation_id LIMIT ".$limitstart.",".$limit."";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
public function getSymbolSymbolQueue($userid ,$limit,$limitstart){
		/*$query = "	SELECT a.*, c.prize_name, c.prize_image, c.prize_value , d.symbol_pieces_image, b.category_name, e.pieces 
					FROM #__symbol_queue_detail a 
					LEFT JOIN #__ap_categories b ON b.setting_id = a.category_id
					LEFT JOIN #__symbol_prize c ON c.id = a.symbol_prize_id
					LEFT JOIN #__symbol_symbol_pieces d ON d.symbol_pieces_id = a.symbol_pieces_id
					LEFT JOIN #__symbol_symbol e ON e.symbol_id = a.queue_id
					WHERE a.userid = ".$userid."
                    LIMIT ".$limitstart.",".$limit."";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;*/

		$query = "	SELECT a.*, c.prize_name, c.prize_image, c.prize_value , d.symbol_pieces_image, b.category_name, e.pieces 
					FROM #__symbol_queue_detail a 
					LEFT JOIN #__ap_categories b ON b.setting_id = a.category_id
					LEFT JOIN #__symbol_prize c ON c.id = a.symbol_prize_id
					LEFT JOIN #__symbol_symbol_pieces d ON d.symbol_pieces_id = a.symbol_pieces_id
					LEFT JOIN #__symbol_symbol e ON e.symbol_id = a.queue_id
					WHERE a.userid = '$userid'";

             $query1 = $query;

			$this->_db->setQuery ($query,$limitstart,$limit);
			$result = $this->_db->loadObjectList();

			$this->_db->setQuery($query1);
			$this->_totalCnt = count($this->_db->loadObjectList());
			return $result;
	}

	public function getSymbolSymbolPrizebyId($id,$limit,$limitstart){
		$query = "	SELECT a.symbol_prize_id, c.prize_name, c.prize_image, c.prize_value, b.symbol_name, b.symbol_image, b.pieces, b.cols, b.rows, d.presentation_id, d.status, c.unlocked_status 
					FROM #__symbol_symbol_prize a 
					LEFT JOIN #__symbol_symbol b ON b.symbol_id = a.symbol_id 
					LEFT JOIN #__symbol_prize c ON c.id = a.id
					INNER JOIN #__symbol_presentation d ON d.presentation_id = a.presentation_id 
					WHERE a.id = ".$id."
					LIMIT ".$limitstart.",".$limit."";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getSymbolQueueView($id,$limit,$limitstart){
		$query = "	SELECT a.*
					FROM #__symbol_queue_detail a 					
					WHERE a.symbol_prize_id = ".$id."
					LIMIT ".$limitstart.",".$limit."";
		$this->_db->setQuery ($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function getSymbolQueue($package_id, $usergroup){
		$query = "
				SELECT pp.* FROM #__usergroup_presentation up 
				INNER JOIN #__process_presentation pp ON pp.`id` = up.`process_presentation`
				WHERE up.`package_id` = '".$package_id."' 
			";
		$this->_db->setQuery($query);
		$sps = $this->_db->loadObjectList ();
		$selectedPresentation = null;
		$presentations = null;
		if(!empty($sps)){
			$sp = $sps[0];
			$selectedPresentation = $sp->selected_presentation;
		}
		if($selectedPresentation != null){
			$query = "
					SELECT * FROM #__selected_presentation WHERE id IN (".$selectedPresentation.")
				";
			$this->_db->setQuery($query);
			$rows = $this->_db->loadObjectList();
			foreach ($rows as $row){
				$presentations .= $row->presentations . ',';
			}
			$presentations = substr($presentations, 0, strlen($presentations)-1);
		}
		$results = array();
		if($presentations != null) {
			$query = "
					SELECT a.`presentation_id`, c.`id`, c.`process_id`, d.`prize_image`, e.`symbol_image`, c.`percent_pricing`, c.`percent_of_priced_rpc`, c.`percent_of_free_rpc`
					FROM #__symbol_queue_detail a
					INNER JOIN #__ap_symbol_process_clone b ON b.id = a.`queuedetail_id`
					INNER JOIN #__ap_symbol_process_process_clone c ON c.`id` = b.`clone_id`
					INNER JOIN #__symbol_prize d ON d.`id` = c.`prize_id`
					INNER JOIN #__symbol_symbol e ON e.`symbol_id` = c.`symbol_id`
					WHERE a.`presentation_id` IN (".$presentations.")
					GROUP BY c.`id`, c.`process_id`, c.`prize_id`, c.`symbol_id`, c.`percent_pricing`, c.`percent_of_priced_rpc`, c.`percent_of_free_rpc`
				";
			$this->_db->setQuery($query);
			$rows = $this->_db->loadObjectList();
			foreach ($rows as $row){
				$result = new stdClass();
				$result->presentation_id = $row->presentation_id;
				$result->clone_id = $row->id;
				$result->symbol_process_id = $row->process_id;
				$result->prize_id = $row->prize_image;
				$result->symbol_id = $row->symbol_image;
				$result->percent_pricing = $row->percent_pricing;
				$result->percent_of_priced_rpc = $row->percent_of_priced_rpc;
				$result->percent_of_free_rpc = $row->percent_of_free_rpc;

				$query = "
					SELECT a.*, b.*, d.`prize_image`, e.`symbol_pieces_image`
					FROM #__symbol_queue_detail a
					INNER JOIN #__ap_symbol_process_clone b ON b.id = a.`symbol_pieces_id`
					INNER JOIN #__ap_symbol_process_process_clone c ON c.`id` = b.`clone_id` AND  c.`id` = '".$row->id."'
					INNER JOIN #__symbol_prize d ON d.`id` = a.`symbol_prize_id`	
					INNER JOIN #__symbol_symbol_pieces e ON e.`symbol_pieces_id` = b.`pieces_id`				
					ORDER BY a.`queuedetail_id`
					";
				$this->_db->setQuery($query);
				$rows2 = $this->_db->loadObjectList();
				$result->symbol_queue = $rows2;

				$results[] = $result;
			}
		}
		return $results;
	}

	public function getUserGroup($package_id, $firstname, $lastname, $email, $birthday, $gender, $street, $city,
	$state, $post_code, $country){
		//check for name field
		$query = "
				select * from #__ap_usergroup where package_id = '".$package_id."' and field = 'name' and is_presentation = '1'
				and (lower(firstname) like '%".strtolower($firstname)."%' or lower(lastname) like '%".strtolower($lastname)."%')
			";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList ();
		if(empty($rows) || count($rows) == 0){
			//check for email
			$query = "
					select * from #__ap_usergroup where package_id = '".$package_id."' and field = 'email' and is_presentation = '1'
					and lower(email) like '%".strtolower($email)."%'
				";
			$this->_db->setQuery($query);
			$rows = $this->_db->loadObjectList ();
			if(empty($rows) || count($rows) == 0) {
				//check for gender
				$query = "
						select * from #__ap_usergroup where package_id = '".$package_id."' and field = 'gender' and is_presentation = '1'
						and lower(gender) like '%".strtolower($gender)."%'
					";
				$this->_db->setQuery($query);
				$rows = $this->_db->loadObjectList ();
				if(empty($rows) ||  count($rows) == 0) {
					//check for location
					$query = "
						select * from #__ap_usergroup where package_id = '".$package_id."' and field = 'location' and is_presentation = '1'
						and (lower(street) like '%".strtolower($street)."%'
						or lower(city) like '".strtolower($city)."'
						or lower(state) like '".strtolower($state)."'
						or lower(country) like '".strtolower($country)."'
						)
					";
					$this->_db->setQuery($query);
					$rows = $this->_db->loadObjectList ();
					if(empty($rows) || count($rows) == 0){
						//check for birthday
						$query = "
								SELECT * FROM #__ap_usergroup WHERE DATE_SUB(NOW(), INTERVAL from_age YEAR) > '".$birthday."'
								AND DATE_SUB(NOW(), INTERVAL to_age YEAR) <= '".$birthday."' and is_presentation = '1'
							";
						$this->_db->setQuery($query);
						$rows = $this->_db->loadObjectList ();
						return $rows;
					}
				} else {
					return $rows;
				}
			} else {
				return $rows;
			}
		} else {
			return $rows;
		}
			
	}

	public function get_presentation($user_id) {
		return '0';
	}

	public function get_shopping_credits($package_id) {
		$query = "
				select count(*) as count from #__shopping_credit_plan_detail where package_id = '".$package_id."' 
			";
		$this->_db->setQuery ( $query );
		$rows = $this->_db->loadObjectList();
		return $rows[0]->count;
	}
	
	public function get_donation_history($user_id){	
	$db = JFactory::getDbo();
        $user = &JFactory::getUser();
        $user_id = $user->id;
        $query = "SELECT a.* FROM #__funding_history a 
		          INNER JOIN #__funding_user b ON a.funding_id = b.funding_id 
                   WHERE b.user_id='".$user_id."' AND a.transaction_type='DONATION' ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
		 return $rows;
	}
	
	public function get_shopping_credit_view($package_id) {
		$query = "
				select a.* ,b.*,c.*,d.*
				from #__shopping_credit_from_donation as a
				INNER JOIN #__shopping_credit_plan_detail b ON b.id = a.shopping_credit_plan_id
	            INNER JOIN #__shopping_credit_plan c ON c.sc_plan = b.id
	            INNER JOIN #__shopping_credit_category d ON d.id = c.category				
				 where a.package_id = '".$package_id."' 
			";
		$this->_db->setQuery ( $query );
		$items = $this->_db->loadObjectList();
		return $items;
	}

	public function getAllUserlist($packageId, $limit = 20, $limitstart = 0){
		$arrdata = array();

		$query = $this->_db->getQuery(true);
		$query = " select * from #__ap_useraccounts where package_id = '".$packageId."' order by firstname asc ";
		$this->_db->setQuery($query, $limitstart, $limit);
		$results = $this->_db->loadObjectList();
		foreach ($results as $result){
			$data = new stdClass();
			$rrs =  $this->getAllHistory($result->id, $packageId);
			$data->id = $result->id;
			$data->firstname = $result->firstname;
			$data->lastname = $result->lastname;
			$data->transaction = $rrs;
			$arrdata[] = $data;
		}
		$return['data']  = $arrdata;

		$query = " select count(*) from #__ap_useraccounts where package_id = '".$packageId."' ";
		jimport('joomla.html.pagination');
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();

		$return['pagination'] = new JPagination( $total, $limitstart, $limit );
		$return['lists'] = array(
    			'limitstart'=>$limitstart,
    			'limit'=>$limit    			
		);
		return $return;
	}

	public function getAllHistory($data, $packageId){
		$fundingHis = $this->getFundingHistory($data, $packageId);
		$withdrawalHis = $this->getWithdrawalHistory($data, $packageId);
		$donationHis = $this->getDonationHistory($data, $packageId);

		$results = array();
		foreach ($fundingHis as $funding){
			$result = new stdClass();
			$result->created_date = $funding->created_date;
			$result->description = $funding->description;
			$result->debit = $funding->debit;
			$result->credit = $funding->credit;
			$results[] = $result;
		}

		foreach ($withdrawalHis as $withdrawal){
			$result = new stdClass();
			$result->created_date = $withdrawal->created_date;
			$result->description = $withdrawal->description;
			$result->debit = $withdrawal->debit;
			$result->credit = $withdrawal->credit;
			$results[] = $result;
		}

		foreach ($donationHis as $donation){
			$result = new stdClass();
			$result->created_date = $donation->created_date;
			$result->description = $donation->description;
			$result->debit = $donation->debit;
			$result->credit = $donation->credit;
			$results[] = $result;
		}
		return $results;
	}

function getAllFundingHistory($data, $packageId, $limit=5, $limitstart=0){
		$query = "
		SELECT fh.*, fu.`funding_id`, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
		        FROM #__funding_user fu 
				INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
				INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
				WHERE fu.`package_id` = '" .$packageId. "' 
				".
				($data['user_name'] != "" ? " AND ( (LOWER(a.`firstname`) LIKE '%".strtolower($data['user_name'])."%' OR LOWER(a.`lastname`) LIKE '%".strtolower($data['user_name'])."%' ) " : " AND (1=1").
				($data['start_date'] != "" && $data['end_date'] != "" ?
			" AND fu.funding_last_update between '".$data['start_date']."' AND '".$data['end_date']."' " : "").
			 	 ($data['amount_from'] != "" && $data['amount_to'] != "" ?
			" AND (fh.credit-fh.debit) between '".$data['amount_from']."' AND '".$data['amount_to']."' " : "").
			($data['transaction_type'] != "" ? " AND LOWER(fh.`transaction_type`) = '".strtolower($data['transaction_type'])."'  " : "").
			($data['user_action'] != "" ? " AND LOWER(fh.`transaction_type`) = '".strtolower($data['user_action'])."'  " : "")			
				;
		$query .= ") ORDER BY fh.funding_history_id DESC  LIMIT " .$limitstart. " , " .$limit. " ";
				
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		return $results;
	}
	
	function getFundingHistory($data, $packageId, $limit=5, $limitstart=0, $status){
		$query = "
		SELECT fh.*, fu.`funding_id`, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
		        FROM #__funding_user fu 
				INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
				INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
				INNER JOIN `#__users` u ON u.`id` = a.`id`
				WHERE fu.`package_id` = '" .$packageId. "' AND
				fh.`status` = '" .$status. "'
				".
            ($data['user_name'] != "" ? " AND LOWER(u.`username`) LIKE '%".strtolower($data['user_name'])."%' " : " AND (1=1)").
				($data['start_date'] != "" && $data['end_date'] != "" ?
			" AND fu.funding_last_update between '".$data['start_date']."' AND '".$data['end_date']."' " : "").
			 	 ($data['amount_from'] != "" && $data['amount_to'] != "" ?
			" AND (fh.credit-fh.debit) between '".$data['amount_from']."' AND '".$data['amount_to']."' " : "").
			($data['transaction_type'] != "" ? " AND LOWER(fh.`transaction_type`) = '".strtolower($data['transaction_type'])."'  " : "").
			($data['user_action'] != "" ? " AND LOWER(fh.`transaction_type`) = '".strtolower($data['user_action'])."'  " : "")			
				;
		$query .= " ORDER BY fh.funding_history_id DESC  LIMIT " .$limitstart. " , " .$limit. " ";
				
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		return $results;
	}

		function getFundingView($data, $packageId){
		$query = "
		SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
		        FROM #__funding_user fu 
				INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
				INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
				WHERE fu.`package_id` = '" .$packageId. "' AND fh.`transaction_type` = 'FUNDING'
				" .
				($data['user_name'] != "" ? " AND ( (LOWER(a.`firstname`) LIKE '%".strtolower($data['user_name'])."%' OR LOWER(a.`lastname`) LIKE '%".strtolower($data['user_name'])."%' ) " : " AND (1=1").
				($data['start_date'] != "" && $data['end_date'] != "" ?
			" and fu.funding_last_update between '".$data['start_date']."' and '".$data['end_date']."' " : "").
			 	 ($data['amount_from'] != "" && $data['amount_to'] != "" ?
			" and fh.debit between '".$data['amount_from']."' and '".$data['amount_to']."' " : "").
			($data['user_action'] != "" ? " AND LOWER(fh.`transaction_type`) = '".strtolower($data['user_action'])."'  " : "") 
			
				;
		$query .= ' ) ';
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		return $results;
	}
	
	function getDonationView($data, $packageId){
		$query = "
		SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
		        FROM #__funding_user fu 
				INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
				INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
				WHERE fu.`package_id` = '" .$packageId. "' AND fh.`transaction_type` = 'DONATION'
				" .
				($data['user_name'] != "" ? " AND ( (LOWER(a.`firstname`) LIKE '%".strtolower($data['user_name'])."%' OR LOWER(a.`lastname`) LIKE '%".strtolower($data['user_name'])."%' ) " : " AND (1=1").
				($data['start_date'] != "" && $data['end_date'] != "" ?
			" and fu.funding_last_update between '".$data['start_date']."' and '".$data['end_date']."' " : "").
			 	 ($data['amount_from'] != "" && $data['amount_to'] != "" ?
			" and fh.debit between '".$data['amount_from']."' and '".$data['amount_to']."' " : "").
			($data['user_action'] != "" ? " AND LOWER(fh.`transaction_type`) = '".strtolower($data['user_action'])."'  " : "") 
			
				;
		$query .= ' ) ';
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		return $results;
	}
	
	function getWithdrawalHistory($data, $packageId){
		$query = "
		SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
		        FROM #__funding_user fu 
				INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
				INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
				WHERE fu.`package_id` = '" .$packageId. "' AND fh.`transaction_type` = 'WITHDRAW'
				" .
				($data['user_name'] != "" ? " AND ( (LOWER(a.`firstname`) LIKE '%".strtolower($data['user_name'])."%' OR LOWER(a.`lastname`) LIKE '%".strtolower($data['user_name'])."%' ) " : " AND (1=1").
				($data['start_date'] != "" && $data['end_date'] != "" ?
			" and fu.funding_last_update between '".$data['start_date']."' and '".$data['end_date']."' " : "").
			 	 ($data['amount_from'] != "" && $data['amount_to'] != "" ?
			" and fh.debit between '".$data['amount_from']."' and '".$data['amount_to']."' " : "").
			($data['user_action'] != "" ? " AND LOWER(fh.`transaction_type`) = '".strtolower($data['user_action'])."'  " : "") 
			
				;
		$query .= ' ) ';
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		return $results;
	}

	function getDonationHistory($data, $packageId){
		$query = "
		SELECT fh.*, fu.*, CONCAT(a.`firstname`, ' ', a.`lastname`) AS NAME, a.`firstname` AS firstname, a.`lastname` AS lastname
		        FROM #__funding_user fu 
				INNER JOIN #__funding_history fh ON fh.`funding_id` = fu.`funding_id`
				INNER JOIN `#__ap_useraccounts` a ON a.`id` = fu.`user_id`
				WHERE fu.`package_id` = '" .$packageId. "' AND fh.`transaction_type` = 'DONATION'
				" .
				($data['user_name'] != "" ? " AND ( (LOWER(a.`firstname`) LIKE '%".strtolower($data['user_name'])."%' OR LOWER(a.`lastname`) LIKE '%".strtolower($data['user_name'])."%' ) " : " AND (1=1").
				($data['start_date'] != "" && $data['end_date'] != "" ?
			" and fu.funding_last_update between '".$data['start_date']."' and '".$data['end_date']."' " : "").
			 	 ($data['amount_from'] != "" && $data['amount_to'] != "" ?
			" and fh.debit between '".$data['amount_from']."' and '".$data['amount_to']."' " : "").
			($data['user_action'] != "" ? " AND LOWER(fh.`transaction_type`) = '".strtolower($data['user_action'])."'  " : "") 
			
				;
		$query .= ' ) ';
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		return $results;
	}

	function getPresentationData($presentation){
		$query = "
				SELECT * FROM #__symbol_symbol_prize a
				INNER JOIN #__symbol_prize b ON b.id = a.`id`
				INNER JOIN #__symbol_symbol c ON c.`symbol_id` = a.`symbol_id`
				WHERE presentation_id = '".$presentation."'
			";
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		return $results;
	}
	
	function getProfile($userid){
		$query = "
				SELECT a.*,b.* FROM #__ap_useraccounts a		
				INNER JOIN `#__users` b ON b.`id` = a.`id`		
				WHERE a.id = '".$userid."'
			";
		$this->_db->setQuery ( $query );
		$results = $this->_db->loadObjectList();
		return $results;
	}
	
		function edit_save($data){
		$db 	= JFactory::getDbo();
		$user	= JFactory::getUser();
		$now = JFactory::getDate();
        $yourpass = JUserHelper::hashPassword($data['pasw']);
		$plainpass = $data['pasw'];
		
		/*$query1 = "select u.* from #__users u
				where username = '".$data['username']."' or u.email = '".$data['email']."' ";
		$db->setQuery ( $query1 );
		$result1 = $db->loadObjectList();

		if (!empty($result1)){
					return false;
		}
		
		$query = "select u.*, au.*, u.email as emailRegistered from #__users u
				inner join #__ap_useraccounts au on au.id = u.id 
				where username = '".$data['username']."' or u.email = '".$data['email']."' ";
		$db->setQuery ( $query );
		$result = $db->loadObjectList();*/

		/*if(empty($result)){ 
		$query1 = "INSERT INTO #__users (name, username, email, password, registerDate, lastvisitDate, activation)
                       VALUES ('" . $data['firstname'] . " " . $data['lastname'] . "', '" . $data['username'] . "', '" . $data['email'] . "', '" . $yourpass . "', '".$now."', '" . $now . "', '" .$data['activation']. "')";
					   		$db->setQuery($query1);
							$db->query();
				   			$insertedId = $db->insertId();
			$query2 = "INSERT INTO  #__user_usergroup_map (user_id, group_id)
					  VALUES ('" .$insertedId. "', 4)";
			$db->setQuery($query2);
			$db->query();
		}else {*/
		$query3 = "UPDATE #__users SET name = '" .$data['firstname']. "', email = '" .$data['email']. "' , password = '" .$yourpass. "' WHERE id = ".$data['userId']."  ";
					   		$db->setQuery($query3);
							$db->query();
				   			$insertedId = $data['id'];


		$query = "UPDATE #__ap_useraccounts SET 
		email = '" . $data['email'] . "', 
		firstname = '" . $data['firstname'] . "', 
		lastname = '" . $data['lastname'] . "', 
		gender = '" . $data['gender'] . "', 
		country = '" . $data['country'] . "', 
		paypal_account = '" . $data['paypal_account'] . "', 
		state = '" . $data['pasw'] . "'
		WHERE id = ".$data['userId']."  ";
		$db->setQuery($query);
		if ($db->query()){
			return true;
			}else{
			return false;
			}
		}
		
	function insertUserInfo($data){
		$db = JFactory::getDbo();
		$user= JFactory::getUser();
		$now = JFactory::getDate();
		$query = "insert into #__ap_useraccounts (id, firstname, lastname, birthday, gender, street, city, state, post_code, country,
					phone, paypal_account, package_id, email, is_active)
				  values ('" .$data['userId']. "', '" .$data['firstname']. "', '" .$data['lastname']. "',
				    '" .$data['birthdate']. "', '" .$data['gender']. "', '" .$data['street']. "',
				    '" .$data['city']. "', '" .$data['state']. "', '" .$data['postCode']. "',
				    '" .$data['country']. "', '" .$data['phone']. "', '" .$data['paypal_account']. "',
				    null, '" .$data['email']. "', '1'  ) ";

		$db->setQuery($query);
		if ($db->query()) {
		     $query = "UPDATE #__user_usergroup_map SET group_id = '4' WHERE user_id = ".$data['userId']." AND group_id = '2' ";
			$db->setQuery($query);
			$db->query();
			return true;
		} else {
			return false;
		}
	}


	function updateInfo($data){
		$db = JFactory::getDbo();
		$user= JFactory::getUser();		
		$now = JFactory::getDate();
		/*$userid = $db->insertId();

		$result = $this->getUserId();
		foreach ($result as $row){
		$userid = $row->id;
		}*/

		$query = "update #__ap_useraccounts set 
					birthday = '" .$data['birthdate']. "',
					street = '" .$data['street']. "', 
					phone = '" .$data['phone']. "',
					is_active = '1'
				  where id = '" .$data['userId']. "'
				  ";
		$db->setQuery($query);
		if ($db->query()) {
			return true;
		} else {
			return false;
		}
	}

		function updateData($data){
		$db = JFactory::getDbo();
		$user= JFactory::getUser();		
		$now = JFactory::getDate();
    	 $userid = $user->id;
		
		$query = "update #__ap_useraccounts set 
					firstname = '" .$data['firstname']. "',
					lastname = '" .$data['lastname']. "', 
					email = '" .$data['email']. "',
					gender = '" .$data['gender']. "',
					street = '" .$data['street']. "', 
					city = '" .$data['city']. "',
					post_code = '" .$data['post_code']. "',
					country = '" .$data['country']. "', 
					phone = '" .$data['phone']. "',
					paypal_account = '" .$data['paypal_account']. "',					
					is_active = '1'
				  where id = '".$userid."'
				  ";
		$db->setQuery($query);
		if ($db->query()) {
			return true;
		} else {
			return false;
		}
	}

}