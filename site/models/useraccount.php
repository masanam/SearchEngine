<?php
defined('_JEXEC') or die();

jimport('joomla.application.component.modelitem');
jimport('joomla.user.helper');

class SearchEngineUsersModelUseraccount extends JModelLegacy {

	function __construct() {
		parent::__construct ();
	}
	
	public function updteUsersave($data){
		$db 	= JFactory::getDbo();
		$checkSetting = $this->exist_criteria($data);
		$yourpass = JUserHelper::hashPassword($data['pasw']);
		$plainpass = $data['pasw'];

		$checkSetting = $this->exist_criteria($data);		
		if ($checkSetting > 0){
			foreach ($checkSetting as $rows){
				$package_id = $rows->package_id;
			}
		}

		$query = "select u.* from #__users u where id = '".$data['userId']."'";
		$db->setQuery ( $query );
		$result = $db->loadObjectList();
		
		if(!$result){
			return false;
		}

		$query = "select u.* from #__users u
				where (username = '".$data['username']."' or u.email = '".$data['email']."') AND id != '".$data['userId']."'";
		$db->setQuery ( $query );
		
		$result = $db->loadObjectList();
		if($result){
			return false;
		}

		$query3 = "UPDATE #__users SET name = '" .$data['firstname']. "', email = '" .$data['email']. "' , password = '" .$yourpass. "' WHERE id = ".$data['userId']."  ";
   		$db->setQuery($query3);
		$db->query();
		$insertedId = $data['id'];
		$this->insertedId  = $insertedId;


		// Modifiction to assing register user automtically to one of the packge pug
		$criteria_id_list = array();
		$pug_id_list = array();
		$pug_id=0;

		if($checkSetting){
			
			$pugs = $this->getPackageUserGroupByPackageId($package_id);
			
			foreach($pugs as $pug){
				$pug_id_list[] = $pug->id;				
			}

			if(count($pug_id_list)>0) {
				foreach($checkSetting as $criteria){
					$criteriaPugs = $this->getPugByCriteria($pug_id_list,$criteria->criteria_id);
					if(is_array( $criteriaPugs)){
						$index = array_rand($criteriaPugs, 1);	
						$pug_id = $criteriaPugs[0];
						$criteriaID= $criteria->criteria_id;
						if($pug_id >0){
							break;
						}
					}					
				}
			}
		}


		if($pug_id>0){

			$fields[]= $db->quoteName('email') . ' = ' . $db->quote( $data['email']);
			$fields[]= $db->quoteName('firstname') . ' = ' . $db->quote($data['firstname']);
			$fields[]= $db->quoteName('lastname') . ' = ' . $db->quote($data['lastname']);
			$fields[]= $db->quoteName('gender') . ' = ' . $db->quote($data['gender']);
			$fields[]= $db->quoteName('country') . ' = ' . $db->quote($data['country']);
			$fields[]= $db->quoteName('state') . ' = ' . $db->quote($data['state']);
			$fields[]= $db->quoteName('password') . ' = ' . $db->quote($data['pasw']);
			$fields[]= $db->quoteName('package_id') . ' = ' . $db->quote($package_id);
			$fields[]= $db->quoteName('is_active') . ' = ' . $db->quote(1);
			$fields[]= $db->quoteName('pug_id') . ' = ' . $db->quote($pug_id);
			$fields[]= $db->quoteName('is_presentation') . ' = ' . $db->quote(1);

			
		
		}else{

			$fields[]= $db->quoteName('email') . ' = ' . $db->quote( $data['email']);
			$fields[]= $db->quoteName('firstname') . ' = ' . $db->quote($data['firstname']);
			$fields[]= $db->quoteName('lastname') . ' = ' . $db->quote($data['lastname']);
			$fields[]= $db->quoteName('gender') . ' = ' . $db->quote($data['gender']);
			$fields[]= $db->quoteName('country') . ' = ' . $db->quote($data['country']);
			$fields[]= $db->quoteName('state') . ' = ' . $db->quote($data['state']);
			$fields[]= $db->quoteName('password') . ' = ' . $db->quote($data['pasw']);
			$fields[]= $db->quoteName('package_id') . ' = ' . $db->quote($package_id);
			$fields[]= $db->quoteName('is_active') . ' = ' . $db->quote(1);			
		
		}
		$query = $db->getQuery(true);

		$conditions = array(
				$db->quoteName('id') . ' = ' . $db->quote($data['userId'])
		);		
		$query->update($db->quoteName('#__se_useraccounts'))->set($fields)->where($conditions);
		$db->setQuery($query);
		if ($db->execute()){
			if($pug_id>0){
				$this->UpdateToSymbolQueue($pug_id,$data['userId'],$criteriaID);	
			}
			return true;
		}else{
			return false;
		}		
	}

	function save($data){
		$db 	= JFactory::getDbo();
		$user	= JFactory::getUser();
		$now = JFactory::getDate();
        $yourpass = JUserHelper::hashPassword($data['pasw']);
		$plainpass = $data['pasw'];
		
		$checkSetting = $this->exist_criteria($data);		
		if ($checkSetting > 0){
			foreach ($checkSetting as $rows){
			$package_id = $rows->package_id;
			}
		}
		
		$query1 = "select u.* from #__users u
				where username = '".$data['username']."' or u.email = '".$data['email']."' ";
		$db->setQuery ( $query1 );
		$result1 = $db->loadObjectList();

		if (!empty($result1)){
					return false;
		}
		
		$query = "select u.*, au.*, u.email as emailRegistered from #__users u
				inner join #__se_useraccounts au on au.id = u.id 
				where username = '".$data['username']."' or u.email = '".$data['email']."' ";
		$db->setQuery ( $query );
		$result = $db->loadObjectList();

		if(empty($result)){ 
		$query1 = "INSERT INTO #__users (name, username, email, password, registerDate, lastvisitDate, activation)
                       VALUES ('" . $data['firstname'] . " " . $data['lastname'] . "', '" . $data['username'] . "', '" . $data['email'] . "', '" . $yourpass . "', '".$now."', '" . $now . "', '" .$data['activation']. "')";
					   		$db->setQuery($query1);
							$db->query();
				   			$insertedId = $db->insertId();
				   			$this->insertedId  = $insertedId;
			$query2 = "INSERT INTO  #__user_usergroup_map (user_id, group_id)
					  VALUES ('" .$insertedId. "', 4)";
			$db->setQuery($query2);
			$db->query();
		}else {
		$query3 = "UPDATE #__users SET name = '" .$data['firstname']. "', email = '" .$data['email']. "' , password = '" .$yourpass. "' WHERE id = ".$data['id']."  ";
					   		$db->setQuery($query3);
							$db->query();
				   			$insertedId = $data['id'];
				   			$this->insertedId  = $insertedId;

		}

		// Modifiction to assing register user automtically to one of the packge pug
		$criteria_id_list = array();
		$pug_id_list = array();
		$pug_id=0;

		if($checkSetting){
			
			$pugs = $this->getPackageUserGroupByPackageId($package_id);
			
			foreach($pugs as $pug){
				$pug_id_list[] = $pug->id;				
			}

			if(count($pug_id_list)>0) {
				foreach($checkSetting as $criteria){
					$criteriaPugs = $this->getPugByCriteria($pug_id_list,$criteria->criteria_id);
					if(is_array( $criteriaPugs)){
						$index = array_rand($criteriaPugs, 1);	
						$pug_id = $criteriaPugs[0];
						$criteriaID= $criteria->criteria_id;
						if($pug_id >0){
							break;
						}
					}					
				}
			}
		}

		/*if($pug_id>0){

			$query = "INSERT INTO #__se_useraccounts (id, email, firstname, lastname, gender, country, paypal_account, state, is_active, package_id,pug_id, is_presentation)
					  VALUES ('" .$insertedId. "', '" . $data['email'] . "','" . $data['firstname'] . "','" . $data['lastname'] . "','" . $data['gender'] . "','" . $data['country'] . "','" . $data['paypal_account'] . "','" . $data['pasw'] . "',1, '" . $package_id . "','" . $pug_id . "', '1')";

		}else{
			$query = "INSERT INTO #__se_useraccounts (id, email, firstname, lastname, gender, country, paypal_account, state, is_active, package_id)
					  VALUES ('" .$insertedId. "', '" . $data['email'] . "','" . $data['firstname'] . "','" . $data['lastname'] . "','" . $data['gender'] . "','" . $data['country'] . "','" . $data['paypal_account'] . "','" . $data['pasw'] . "',1, '" . $package_id . "')";
		}*/

		if($pug_id>0){

			$query = "INSERT INTO #__se_useraccounts (id, email, firstname, lastname, gender, country, state, paypal_account, password, is_active, package_id,pug_id, is_presentation)
					  VALUES ('" .$insertedId. "', '" . $data['email'] . "','" . $data['firstname'] . "','" . $data['lastname'] . "','" . $data['gender'] . "','" . $data['country'] . "'," . $db->Quote($data['state']) . ",'" . $data['paypal_account'] . "','" . $data['pasw'] . "',1, '" . $package_id . "','" . $pug_id . "', '1')";

		}else{
			$query = "INSERT INTO #__se_useraccounts (id, email, firstname, lastname, gender, country, state, paypal_account, password, is_active, package_id)
					  VALUES ('" .$insertedId. "', '" . $data['email'] . "','" . $data['firstname'] . "','" . $data['lastname'] . "','" . $data['gender'] . "','" . $data['country'] . "'," . $db->Quote($data['state']) . ",'" . $data['paypal_account'] . "','" . $data['pasw'] . "',1, '" . $package_id . "')";
		}
		$db->setQuery($query);
			
		if ($db->query()){
			if($pug_id>0){
				$this->UpdateToSymbolQueue($pug_id,$insertedId,$criteriaID);	
			}
			$this->assignFreeUsergorupID($package_id,$insertedId);
			
			return true;
			}else{
			return false;
			}
		
		
		}

		public function UpdateToSymbolQueue($pug_id,$user_id,$id){
	        $query = "SELECT a.* from #__symbol_queue as a INNER JOIN #__symbol_queue_group as b on a.groupId = b.id
	                    where  b.pug_id='".$pug_id."' and (a.user_id is null  OR a.user_id='0') ORDER BY `a`.`queue_id` ASC limit 1";
	        $this->_db->setQuery($query);
	        $fields = $this->_db->loadObject();

	        $query = "SELECT * from #__usergroup_presentation where id='".$id."'";
	        $this->_db->setQuery($query);
	        $selected  = $this->_db->loadObject();
	        
        	$query = "update #__symbol_queue set user_id = '".$user_id."' , selected_presentation = '".$selected->presentation_id."' where queue_id = '" . $fields->queue_id . "' ";
        	$this->_db->setQuery($query);
        	$this->_db->query();	        
        
    	}

		
		
		function exist_criteria($data) {

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
			
	function insertUserInfo($data){
		$db = JFactory::getDbo();
		$user= JFactory::getUser();
		$now = JFactory::getDate();
		$query = "insert into #__se_useraccounts (id, firstname, lastname, birthday, gender, street, city, state, post_code, country,
					phone, paypal_account, package_id, email, is_active)
				  values ('" .$data['userId']. "', '" .$data['firstname']. "', '" .$data['lastname']. "',
				    '" .$data['birthdate']. "', '" .$data['gender']. "', '" .$data['street']. "',
				    '" .$data['city']. "', " .$db->Quote($data['state']). ", '" .$data['postCode']. "',
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


	function getAge($then) {
		$then_ts = strtotime($then);
		$then_year = date('Y', $then_ts);
		$age = date('Y') - $then_year;
		if(strtotime('+' . $age . ' years', $then_ts) > time()) $age--;
		return $age;
	}

	function updateInfo($data){
		//echo '<pre>';print_r($data);
		$db = JFactory::getDbo();
		$user= JFactory::getUser();		
		$now = JFactory::getDate();
		$age = $this->getAge($data['birthdate']);
		$package_id=null;

		//echo $age;exit;
		/*if((int)$age<1){
			return false;
		}*/

		if((int)$age>0){
			$query = $db->getQuery(true);
			$query->select('a.*')->from('#__se_usergroup as a');
			$query->select("p.package_name AS packagename");
			$query->join("INNER", "#__search_engine AS p ON p.package_id=a.package_id");
			$_age = $age;
			$query->where('('.$_age .' BETWEEN a.from_age and a.to_age) AND a.from_age>=0 AND a.to_age>0');
			$query->order('a.package_id asc');
			
	        $db->setQuery($query);
			$checkSetting = $db->loadObjectList();			
			$package_id = @$checkSetting[0]->package_id;
		}

		$result = $this->getUserId();
		foreach ($result as $row){
			$userid = $row->id;
		}

		if((int)$package_id){
			$pug_id = 0;
			if($checkSetting){
				
				$pugs = $this->getPackageUserGroupByPackageId($package_id);

				if(count($pugs)){
					foreach($pugs as $pug){
						$pug_id_list[] = $pug->id;				
					}

					if(count($pug_id_list)>0) {
						foreach($checkSetting as $criteria){
							$criteriaPugs = $this->getPugByCriteria($pug_id_list,$criteria->criteria_id);
							if(is_array( $criteriaPugs)){
								$index = array_rand($criteriaPugs, 1);	
								$pug_id = $criteriaPugs[0];
								$criteriaID= $criteria->criteria_id;
								if($pug_id >0){
									break;
								}
							}					
						}
					}
				}
			}

			if($pug_id>0){
				$query = "update #__se_useraccounts set 
						pug_id = '" .$pug_id. "',
						package_id = '" .$package_id. "',						
						street = '" .$data['street']. "', 
						phone = '" .$data['phone']. "',
						is_active = '1'";
						
						if($data['birthdate']){
							$query .= ", birthday = '" .$data['birthdate']. "'";	
						}
					   $query .= "where id = '".$userid."'";
			}
			else{
				$query = "update #__se_useraccounts set 
						package_id = '" .$package_id. "',						
						street = '" .$data['street']. "', 
						phone = '" .$data['phone']. "',
						is_active = '1'";

						if($data['birthdate']){
							$query .= ", birthday = '" .$data['birthdate']. "'";	
						}

					   $query .= "where id = '".$userid."'";
			}
		}
		else{
			$query = "update #__se_useraccounts set 						
						street = '" .$data['street']. "', 
						phone = '" .$data['phone']. "',
						is_active = '1'";
			 if($data['birthdate']){
					$query .= ", birthday = '" .$data['birthdate']. "'";	
				}

		   $query .= "where id = '".$userid."'";
		}

		$db->setQuery($query);
		if ($db->query()) {
			if($pug_id>0){
				$this->UpdateToSymbolQueue($pug_id,$userid,$criteriaID);	
			}
			return true;
		} else {
			return false;
		}
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
				inner join #__se_useraccounts au on au.id = u.id 
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


		$query = "UPDATE #__se_useraccounts SET 
		email = '" . $data['email'] . "', 
		firstname = '" . $data['firstname'] . "', 
		lastname = '" . $data['lastname'] . "', 
		gender = '" . $data['gender'] . "', 
		country = '" . $data['country'] . "', 
		paypal_account = '" . $data['paypal_account'] . "', 
		state = " . $db->Quote($data['state']) . ",
		password = '$plainpass'
		WHERE id = ".$data['userId']."  ";
		$db->setQuery($query);
		if ($db->query()){
			return true;
			}else{
			return false;
			}
		}
		
	function updateData($data){
		$db = JFactory::getDbo();
		$user= JFactory::getUser();		
		$now = JFactory::getDate();
		/*$userid = $db->insertId();

		$result = $this->getUserId();
		foreach ($result as $row){
		$userid = $row->id;
		}*/

		$query = "update #__se_useraccounts set 
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


	function login($data){
		$query = "select u.*, au.*, u.email as emailRegistered from #__users u
				left join #__se_useraccounts au on au.id = u.id 
				where username = '".$data['username']."' and password='".base64_encode($data['password'])."' ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		if(!empty($result)){
			return $result[0];
		}
		return null;
	}
	
	function getUserId(){
		$query = "select * from #__users ORDER BY ID DESC LIMIT 1 ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
			return $result;
	}

	function getPackageId($package_id){
		$query = "select * from #__search_engine WHERE package_id = '".$package_id."' ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
			return $result;
	}
	
	function checkUserDetailInfo($username) {
		$query = "select u.*, au.*, u.email as emailRegistered from #__users u
				inner join #__ap_useraccounts au on au.id = u.id 
				where u.id = '".$username."' ";

		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();

		if(!empty($result)){
			$user = $result[0];
			if($user->package_id == '' || $user->package_id == '0'){
				return 'no_package';
			}
				
			return $user;
		}
		return null;
	}
	function checkUserDetailInfo1($username) {
		$query = "select u.*, au.*, u.email as emailRegistered from #__users u
				inner join #__se_useraccounts au on au.id = u.id 
				where u.id = '".$username."' ";

		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		if(!empty($result)){
			$user = $result[0];
				
			if($user->package_id == '' || $user->package_id == '0'){
				return 'no_package';
			}
				
			return $result;
		}
		return null;
	}
	function getSessionData($userId){
		$query = "select u.*, au.*, u.email as emailRegistered from #__users u
				left join #__se_useraccounts au on au.id = u.id 
				where u.id = '".$userId."'  ";
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadObjectList();
		if(!empty($result)){
			return $result[0];
		}
		return null;
	}

	public function getPackageUserGroupByPackageId($package_id){

    	$query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->quoteName('#__usergroup_presentation'));
        $query->where($this->_db->quoteName('package_id') . "='" . $package_id . "'");        
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();               
    }

    public function getPugByCriteria($pug_id,$criteria_id){
    	
    	$query = "select p. * from #__pug_criteria AS p			  
				  where p.criteria_id = '".$criteria_id."' AND p.pug_id IN(".implode(',',$pug_id).") ";

		$this->_db->setQuery($query);
		$result= $this->_db->loadObjectList();  
		$pug_id_list = null;
		if($result){
			$pug_id_list = array();
			foreach ($result as $key => $value) {
				$pug_id_list[]= $value->pug_id;
			}
		}

		return $pug_id_list;
    }

     public function getPenalty($user_id,$package_id){
    	$data = $this->checkUserDetailInfo($user_id);
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->quoteName('#__user_penalty'));
        $query->where($this->_db->quoteName('user_id') . "='" . $user_id . "'"); 
         $query->where($this->_db->quoteName('package_id') . "='" . $data->package_id . "'"); 
        $query->where($this->_db->quoteName('status') . "='1'");        
        $this->_db->setQuery($query);
        $result = $this->_db->loadObject();     
        
        if($result){
        	
        	$query = $this->_db->getQuery(true);
	        $query->select('*');
	        $query->from($this->_db->quoteName('#__penalties'));
	        $query->where($this->_db->quoteName('id') . "='" . $result->penalty_form_id . "'"); 	        
	        $this->_db->setQuery($query);
	        $penalty = $this->_db->loadObject(); 
	        $penalty->created_at = $result->created_at;
	        return $penalty;
	       
		}

    }
    
    function assignFreeUsergorupID($package_id, $user_id){
		
		if(!$package_id || !$user_id)
			return false;

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);	
		$query
			->select('*')
			->from($db->quoteName('#__se_useraccounts'))
			->where('free_usergroup_id > 0')
			->where('id='. $db->quote($user_id));
		$db->setQuery($query);
		$result = $db->loadObject();	
		


		if(	$result){
			return false;
		}	


		$query = $db->getQuery(true);	
    	$query
			->select('*')
			->from($db->quoteName('#__ap_free_gift_code_rewards_list'))
			->where('package_id='. $db->quote($package_id))
			->where('CURDATE() between start_date and end_date')
			->where('status=1');			
		$db->setQuery($query);
		$results = $db->loadObjectList();
		$user_group_id = array();
		
		if(empty($results)){
			return false;
		}else{

			foreach($results as $result){
				$user_group_id[] = $result->user_group_id;
			}
		}

		if( !empty($user_group_id) && count($user_group_id)>=1) {
			$query = $db->getQuery(true);
			$query
				->select('*')
				->from($db->quoteName('#__ap_free_giftcode_usergroup'))
				->where('package_id='. $db->quote($package_id))
				->where('free_usergroup_id IN('.implode(',', $user_group_id).')');				
			//echo $query;
			$db->setQuery($query);			
			$results = $db->loadObjectList();
			//var_dump($results);

			if(empty($results)){
				return false;
			}else{

				foreach ($results as $key => $result) {
					$users = $this->getUserByCreteria($result,$package_id);
					if($users){						
						if( in_array($user_id, $users) ){
							$query = $db->getQuery(true);
							$fields = array(
								$db->quoteName('free_usergroup_id') . ' = ' . $result->free_usergroup_id
							);

							$conditions = array(
								$db->quoteName('id') . ' = ' . $db->quote($user_id)
							);
							$query->update($db->quoteName('#__se_useraccounts'))->set($fields)->where($conditions);
							$db->setQuery($query);
							$db->execute();	
							break;
						}
						
					}
					
				}
				return false;
			}			
		}

    }


    public function getUserByCreteria($criteria,$package_id){

			
			$db 	= JFactory::getDBO();
			$query 	= $db->getQuery(true);
			
			$where = "1!=1 ";
			$where.= ((!empty($criteria->firstname) && $criteria->firstname != '') ? 'OR lower(firstname) like \'%' . strtolower($criteria->firstname) . '%\' ' : '') ;
			$where.= ((!empty($criteria->lastname) && $criteria->lastname != '') ? ' OR lower(lastname) like \'%' . strtolower($criteria->lastname) . '%\' ' : '') ;
			$where.= ((!empty($criteria->email) && $criteria->email != '') ? ' OR lower(\'email\') like \'%' . strtolower($criteria->email) . '%\' ' : '') ;
			$where.= ((!empty($criteria->gender) && $criteria->gender != '') ? ' OR lower(gender) like \'%' . strtolower($criteria->gender) . '%\' ' : '') ;				
            $where.= ((!empty($criteria->state) && $criteria->state != '') ? ' OR lower(state) like ' . $db->Quote('%'.strtolower($criteria->state).'%') : '') ;
			$where.= ((!empty($criteria->country) && $criteria->country != '') ? ' OR lower(country) like \'%' . strtolower($criteria->country) . '%\' ' : '') ;
			$whereand =' AND ( a.package_id='.$db->quote($package_id).')';
		
			echo $query = "SELECT a.id from #__se_useraccounts a 
			INNER JOIN ".$db->quoteName('#__users', 'b'). ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.id') . ')'." where (".$where.") ".$whereand;	

			$db->setQuery($query);
			$accounts = $db->loadObjectList();			
			$usersid = null;
			if($accounts){
				$usersid = array();	
				foreach ($accounts as $key => $account) {
					$usersid[] = $account->id;
				}
			}
			return $usersid;
	}
}
