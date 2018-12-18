<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modeladmin');

class SearchEngineModelUsergroup extends JModelAdmin {

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_id = JRequest::getVar('criteria_id');
        $this->_package_id = JRequest::getVar('package_id');
        $this->_db = JFactory::getDbo();
    }

    public function getForm($data = array(), $loadData = true) {
        // Initialise variables.
        $app = JFactory::getApplication();
        // Get the form.
        $form = $this->loadForm('com_searchengine.usergroup', 'usergroup', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }
        return $form;
    }


	function check_package($package_id){
		$db 	= JFactory::getDBO();
		$query 	= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__search_engine");
		$query->where("package_id='".$package_id."'");
		$db->setQuery($query);
		$accounts = $db->loadObjectList();
		return $accounts;
	}

		/*function registered_users($package_id){
		$db 	= JFactory::getDBO();
		$query 	= $db->getQuery(TRUE);
		$query->select("*");
		$query->from("#__se_useraccounts");
		$query->where("package_id='".$package_id."'");
		$db->setQuery($query);
		$accounts = $db->loadObjectList();
		return $accounts;
		}*/

    function registered_users($package_id,$limitstart,$limit){
        $db  = JFactory::getDBO();
        $app = JFactory::getApplication();

        $query  = $db->getQuery(TRUE);
        $query->select("*");
        $query->from($db->quoteName("#__se_useraccounts",'a'));
        $query->join('INNER', $db->quoteName('#__users', 'b') . ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.id') . ')');
        $query->where("a.package_id='".$package_id."'");
        $db->setQuery($query);
        $this->total=0;
        $this->total = count($db->loadObjectList());

        $db->setQuery($query,$limitstart, $limit);
        $accounts = $db->loadObjectList();
        return $accounts;
    }

    public function getTotal(){
        return $this->total;
    }


    /**
     * Method to get the data that should be injected in the form.
     *
     * @return	mixed	The data for the form.
     * @since	1.6
     */
    protected function loadFormData() {
        $data = "";
        $db = JFactory::getDbo();
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->quoteName('#__se_usergroup'));
        $query->where($this->_db->quoteName('criteria_id') . "='" . $this->_id . "'");
        $this->_db->setQuery($query);
        $rows = $db->loadObject();
        if ($rows) {
            foreach ($rows as $k => $row) {
                $data[$k] = $row;
            }
        }
        return $data;
    }

    /**
     * Method to get a single record.
     *
     * @param	integer	The id of the primary key.
     *
     * @return	mixed	Object on success, false on failure.
     * @since	1.6
     */
    public function getItem($pk = null) {
        if ($item = parent::getItem($pk)) {

            //Do any procesing on fields here if needed
        }

        return $item;
    }

    /**
     * Prepare and sanitise the table prior to saving.
     *
     * @since	1.6
     */
    protected function prepareTable(&$table) {
        jimport('joomla.filter.output');

        if (empty($table->id)) {

            // Set ordering to the last item if not set
            if (@$table->ordering === '') {
                $this->_db->setQuery('SELECT MAX(ordering) FROM #__shopping_package');
                $max = $db->loadResult();
                $table->ordering = $max + 1;
            }
        }
    }

    public function getData() {
        return $this->loadFormData();
    }

    protected function preprocessForm(JForm $form, $data, $group = 'user') {
        parent::preprocessForm($form, $data, $group);
    }

    public function getDataField($field, $package_list_id) {
        $db = &JFactory::getDBO();
        $query = "SELECT * FROM #__shopping_usergroup WHERE " . $db->quoteName('field') . "='" . $field . "' AND " .
                $db->quoteName('package_list_id') . "='" . $package_list_id . "' ORDER BY criteria_id DESC";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
    }

    function insert_criteria_search($data) {
        $command = JRequest::getVar('command');
        $rows = null;
        $row = null;
        //checking if its a main usergroup
        $rows = JRequest::getVar('jform');
		$package_id = JRequest::getVar('package_id');

			
        if (empty($command) && 1 != $command) {
            if (!empty($rows)) {
               //return -1;
            }
        } else {
            if (empty($rows)) {
                return -3;
            }
            $row = $rows[0];
           // $criteria_group_id = $row->criteria_id;
        }

        $firstname = $data->firstname;
        $lastname = $data->lastname;
        $from_age = $data->from_age;
        $to_age = $data->to_age;
        $email = $data->email;
        
        $state = $data->state;
        $country = $data->country;
        $gender = $data->gender;
        $from_age = $data->from_age;
        $to_age = $data->to_age;


        
			$db = JFactory::getDBO();
			$query = "SELECT *
			FROM `#__se_useraccounts`
			WHERE 
			 " . ((!empty($email) && $email != '') ? ' LOWER(email) = "'.strtolower($email).'" ': '') . "  
			 " . ((!empty($firstname) && $firstname != '') ? ' LOWER(firstname) = "'.strtolower($firstname).'" ': '') . " 
			 " . ((!empty($lastname) && $lastname != '') ? ' LOWER(lastname) = "'.strtolower($lastname).'" ': '') . "
			 " . ((!empty($street) && $street != '') ? ' LOWER(state) = '.strtolower($this->_db->Quote($state)).'': '') . " 
			 " . ((!empty($country) && $country != '') ? ' LOWER(country) = "'.strtolower($country).'" ': '') . " 
			 " . ((!empty($gender) && $gender != '') ? ' LOWER(gender) = "'.strtolower($gender).'" ': '') . " 
			";
			$db->setQuery($query);
			$count_data = $db->loadObjectList();

			foreach ($count_data as $count) {
				$apid = $count->ap_account_id;
				$userid = $count->id;
			}

			$db = JFactory::getDBO();
			$query = " SELECT *
			FROM `#__se_usergroup`
			WHERE 
			 " . ((!empty($email) && $email != '') ? ' LOWER(email) = "'.strtolower($email).'" ': '') . "  
			 " . ((!empty($firstname) && $firstname != '') ? ' LOWER(firstname) = "'.strtolower($firstname).'" ': '') . " 
			 " . ((!empty($lastname) && $lastname != '') ? ' LOWER(lastname) = "'.strtolower($lastname).'" ': '') . "
			 " . ((!empty($street) && $street != '') ? ' LOWER(state) = '.strtolower($this->_db->Quote($state)).'': '') . " 
			 " . ((!empty($country) && $country != '') ? ' LOWER(country) = "'.strtolower($country).'" ': '') . " 
			 " . ((!empty($gender) && $gender != '') ? ' LOWER(gender) = "'.strtolower($gender).'" ': '') . " 
			";
			$db->setQuery($query);
			$count_usergroup = $db->loadObjectList();

        foreach ($count_usergroup as $row) {
            $criteria_id = $row->criteria_id;
        }

        if ($count_data) {
            if ($count_usergroup) {
			$usergroups = $this->getUserGroup(JRequest::getVar('title'));
				if ($usergroups == null){
				  $this->updateUserGroup(JRequest::getVar('title'),JRequest::getVar('package_id'));}
				$groups = $this->getLastIdGroup();
					if($groups != null){
						$lastId = $groups->id;
					}
                foreach ($count_data as $rows) {
                    $query = " UPDATE `#__se_usergroup` SET
			population = '$data->population',
			firstname = '$rows->firstname',
			lastname = '$rows->lastname',
			email = '$rows->email',
			from_age = '$data->from_age',
			to_age = '$data->to_age',
			gender = '$rows->gender',			
			state = ".$this->_db->Quote($rows->state).",			
			country = '$rows->country',
			field = '$data->field',
			group_name = '" . JRequest::getVar('title') . "', 
			usergroup_id = '" . $lastId. "', ";
                    $query .= JRequest::getVar('command') == '1' ? " is_presentation = '1',  " : " is_presentation  = NULL,  ";
                    $query .=!empty($parent) ? ' parent_usergroup = \'' . $parent . '\', ' : ' parent_usergroup = NULL, ';
                    $query .= " status = '1'
			WHERE criteria_id = '$criteria_id' ";
                    $this->_db->setQuery($query);
                    $this->_db->query();
                    return -2;
                }
            } else {
                foreach ($count_data as $rows) {
                    $query2 = " UPDATE #__se_useraccounts SET package_id = '$data->package_id' WHERE ap_account_id = '$apid' ";
                    $this->_db->setQuery($query2);
                    $this->_db->query();

                    $parent = JRequest::getVar('parentUserGroup');
                    $query = "INSERT INTO `#__se_usergroup` (package_id,population,firstname,lastname,email,from_age,to_age,useraccount_id,gender,street,city,state,post_code,country,field,status,group_name,is_presentation,parent_usergroup)
		VALUES ('$data->package_id','$data->population','$rows->firstname',
			'$rows->lastname','$rows->email','$data->from_age','$data->to_age',".$userid.",
			'$rows->gender','$rows->street','$rows->city',".$this->_db->Quote($rows->state).",'$rows->post_code',
			'$rows->country','$data->field','1','" . JRequest::getVar('title') . "',
			" . (JRequest::getVar('command') == 1 ? '1' : 'null' ) . ", " . (null != $row ? $row->criteria_id : 'null' ) . " )";
                    $this->_db->setQuery($query);
                    $this->_db->query();					
					$this->get_queue_id($userid);
                    return 1;
                }
            }
        } else {
            return -3;
        }
    }
	
	function insert_criteria($data) {
        $command = JRequest::getVar('command');
        $rows = null;
        $row = null;
        //checking if its a main usergroup
        $rows = JRequest::getVar('jform');
		$package_id = JRequest::getVar('package_id');

        $firstname = $data->firstname;
        $lastname = $data->lastname;
        $from_age = $data->from_age;
        $to_age = $data->to_age;
        $email = $data->email;
        $country = $data->country;
        $gender = $data->gender;
        $from_age = $data->from_age;
        $to_age = $data->to_age;
		
                    $query = "INSERT INTO `#__se_usergroup` (package_id,population,firstname,lastname,email,from_age,to_age,gender,street,city,state,post_code,country,field,status,group_name,is_presentation,parent_usergroup)
		VALUES ('$data->package_id','$data->population','$data->firstname',
			'$data->lastname','$data->email','$data->from_age','$data->to_age',
			'$data->gender','$data->street','$data->city',".$this->_db->Quote($data->state).",'$data->post_code',
			'$data->country','$data->field','1','" . JRequest::getVar('title') . "',
			" . (JRequest::getVar('command') == 1 ? '1' : 'null' ) . ", " . (null != $row ? $row->criteria_id : 'null' ) . " )";
                    $this->_db->setQuery($query);
                    $this->_db->query();					
                    return 1;
               
    }

 function updateUserGroup($name,$packageId){
 		$now = JFactory::getDate();
		 $query = " INSERT INTO  #__se_groups (name, package_id, created_on) VALUES ('".$name."','".$packageId."','".$now."')";
                    $this->_db->setQuery($query);
                    $this->_db->query();	
					return;
				   }
 

 
 function get_queue_id($userid) {
 		$fund = $this->get_last_id();
		if($fund != null){
			$lastId = $fund->queue_id;
		}
		
         $query4 = " UPDATE #__symbol_queue SET user_id = '$userid' WHERE queue_id = '$lastId' ";
                    $this->_db->setQuery($query4);
                    $this->_db->query();	
					return;
				   }
				   
				    function get_last_id() {

				   $query3 = " select queue_id from #__symbol_queue WHERE user_id IS NULL ORDER BY queue_id ";
                   $this->_db->setQuery($query3);
                   $this->_db->query();					    
					return $this->_db->loadObject();
				   }

				    function getLastIdGroup() {
				   $query = " select id from #__se_groups ORDER BY id DESC LIMIT 1";
                   $this->_db->setQuery($query);
                   $this->_db->query();					    
					return $this->_db->loadObject();
				   }

				    function getUserGroup($name) {
				   $query = " select * from #__se_groups WHERE name = '".$name."' ";
                   $this->_db->setQuery($query);
                   $this->_db->query();					    
					return $this->_db->loadObject();
				   }

				   					
    function get_queueuser() {
	$query3 = " select count(*) from #__symbol_queue ";
                   $this->_db->setQuery($query3);
                   $this->_db->query();	
				   return ;
	}					
	
	function get_usergroup() {
	$query3 = " select count(*) from #__se_useraccounts ";
                   $this->_db->setQuery($query3);
                   $this->_db->query();	
				   return ;
	}	
	
    function update_criteria($data, $criteria_id) {
        $parent = JRequest::getVar('parentUserGroup');
        $query = " UPDATE `#__se_usergroup` SET
			population = '$data->population',
			firstname = '$data->firstname',
			lastname = '$data->lastname',
			email = '$data->email',
			from_age = '$data->from_age',
			to_age = '$data->to_age',
			gender = '$data->gender',			
			state = ".$this->_db->Quote($data->state).",			
			country = '$data->country',
			field = '$data->field',
			group_name = '" . JRequest::getVar('title') . "', ";
        $query .= JRequest::getVar('command') == '1' ? " is_presentation = '1',  " : " is_presentation  = NULL,  ";
        $query .=!empty($parent) ? ' parent_usergroup = \'' . $parent . '\', ' : ' parent_usergroup = NULL, ';
        $query .= " status = '1'
			WHERE criteria_id = '$criteria_id' ";
        $this->_db->setQuery($query);
        $this->_db->query();
        return $criteria_id;
    }

    function update_criteria_2($data, $criteria_id, $package_id) {
        $parent = JRequest::getVar('parentUserGroup');
        $query = "UPDATE `#__se_usergroup` SET status = '1', ";
        $query .= JRequest::getVar('command') == '1' ? " is_presentation = '1',  " : " is_presentation  = NULL,  ";
        $query .=!empty($parent) ? ' parent_usergroup = \'' . $parent . '\', ' : ' parent_usergroup = NULL, ';
        $query .= " group_name = '" . JRequest::getVar('title') . "'
			WHERE criteria_id = '$criteria_id'";

        $this->_db->setQuery($query);
        $this->_db->query();
        return $criteria_id;
    }

    function countries_used($package_id) {
        $query = $this->_db->getQuery(true);
        $query->select('country');
        $query->from($this->_db->quoteName('#__se_usergroup'));
        $query->where($this->_db->quoteName('package_id') . "='" . $package_id . "'");
        $query->group_by('country');
        $db->setQuery($query);
        $row = $db->loadResultArray();
        if ($row) {
            //print_r($row);
            return $row;
        }
    }

    function criteria_info($id) {
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->quoteName('#__se_usergroup'));
        $query->where($this->_db->quoteName('criteria_id') . "='" . $id . "'");
        $this->_db->setQuery($query);
        $row = $this->_db->loadObject();
        return $row;
    }

    function delete($criteria_id, $package_id) {
        $query = "
				select * from #__se_usergroup where criteria_id = '" . $criteria_id . "' and package_id = '" . $package_id . "'
			";
        $this->_db->setQuery($query);
        $groups = $this->_db->loadObjectList();
        if (!empty($groups)) {
            $group = $groups[0];
            if ($group->field == 'name') {
                $this->delete_registered_account_by_name($package_id, $group->firstname, $group->lastname);
            }
            if ($group->field == 'email') {
                $this->delete_registered_account_by_email($package_id, $group->email);
            }
            if ($group->field == 'age') {
                $this->delete_registered_account_by_age($package_id, $group->from_age, $group->to_age);
            }
            if ($group->field == 'gender') {
                $this->delete_registered_account_by_gender($package_id, $group->gender);
            }
            if ($group->field == 'location') {
                $this->delete_registered_account_by_location($package_id, $street);
            }
        }
        $query = $this->_db->getQuery(true);
        $query->delete();
        $query->from($this->_db->quoteName('#__se_usergroup'));
        $query->where($this->_db->quoteName('criteria_id') . "='" . (int) $criteria_id . "'");
        $query->where($this->_db->quoteName('package_id') . "='" . (int) $package_id . "'");
        $this->_db->setQuery($query);
        $this->_db->query();
    }

    function delete_registered_account_by_name($package_id, $firstname, $lastname) {
        $query = "
				select ap_account_id from #__se_useraccounts where
					(
						lower(firstname) like '%" . strtolower($firstname) . "%' or
						lower(lastname) like '%" . strtolower($lastname) . "%'
					) and package_id = '" . $package_id . "'
			";
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();
        $data = array();
        $data[] = -1;
        foreach ($rows as $row) {
            $data[] = $row->ap_account_id;
        }

        $db = &JFactory::getDbo();
        $query = '';

        $command = JRequest::getVar('command');
        if ($command == '1') {
            $query = "
				update #__se_useraccounts set is_presentation = null where ap_account_id in (
					" . implode(',', $data) . "
				)
			";
        } else {
            $query = "
				update #__se_useraccounts set package_id = null where ap_account_id in (
					" . implode(',', $data) . "
				)
			";
        }

        $db->setQuery($query);
        $db->query();
    }

    function delete_registered_account_by_email($package_id, $email) {
        $query = "
				select ap_account_id from #__se_useraccounts where lower(email) like '%" . strtolower($email) . "%' and package_id = '" . $package_id . "'
			";
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();
        $data = array();
        $data[] = -1;
        foreach ($rows as $row) {
            $data[] = $row->ap_account_id;
        }

        $db = &JFactory::getDbo();

        $command = JRequest::getVar('command');
        if ($command == '1') {
            $query = "
				update #__se_useraccounts set is_presentation = null where ap_account_id in (
					" . implode(',', $data) . "
				)
			";
        } else {
            $query = "
				update #__se_useraccounts set package_id = null where ap_account_id in (
					" . implode(',', $data) . "
				)
			";
        }

        $db->setQuery($query);
        $db->query();
    }

    function delete_registered_account_by_age($package_id, $from_age, $to_age) {
        $query = "
				SELECT ap_account_id FROM #__se_useraccounts WHERE birthday >= DATE_SUB(NOW(), INTERVAL " . $from_age . " YEAR)
				AND birthday <= DATE_SUB(NOW(), INTERVAL " . $to_age . " YEAR)
				AND package_id = '" . $package_id . "'
			";
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();
        $data = array();
        $data[] = -1;
        foreach ($rows as $row) {
            $data[] = $row->ap_account_id;
        }

        $db = &JFactory::getDbo();

        $query = '';
        $command = JRequest::getVar('command');
        if ($command == '1') {
            $query = "
				update #__se_useraccounts set is_presentation = null where ap_account_id in (
					" . implode(',', $data) . "
				)
			";
        } else {
            $query = "
				update #__se_useraccounts set package_id = null where ap_account_id in (
					" . implode(',', $data) . "
				)
			";
        }

        $db->setQuery($query);
        $db->query();
    }

    function delete_registered_account_by_gender($package_id, $gender) {
        $query = "
				select ap_account_id from #__se_useraccounts where lower(gender) like '%" . strtolower($gender) . "%' and package_id = '" . $package_id . "'
			";
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();
        $data = array();
        $data[] = -1;
        foreach ($rows as $row) {
            $data[] = $row->ap_account_id;
        }

        $db = &JFactory::getDbo();

        $query = '';

        $command = JRequest::getVar('command');
        if ($command == '1') {
            $query = "
				update #__se_useraccounts set is_presentation = null where ap_account_id in (
					" . implode(',', $data) . "
				)
			";
        } else {
            $query = "
				update #__se_useraccounts set package_id = null where ap_account_id in (
					" . implode(',', $data) . "
				)
			";
        }

        $db->setQuery($query);
        $db->query();
    }

    function delete_registered_account_by_location($package_id, $street, $city = "", $state = "", $post_code = "", $country = "") {
        $query = "
				select ap_account_id from #__se_useraccounts where lower(street) like '%" . strtolower($street) . "%' and package_id = '" . $package_id . "'
			";
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();
        $data = array();
        $data[] = -1;
        foreach ($rows as $row) {
            $data[] = $row->ap_account_id;
        }

        $db = &JFactory::getDbo();
        $query = '';

        $command = JRequest::getVar('command');

        if ($command == '1') {
            $query = "
				update #__se_useraccounts set is_presentation = null where ap_account_id in (
					" . implode(',', $data) . "
				)
			";
        } else {
            $query = "
				update #__se_useraccounts set package_id = null where ap_account_id in (
					" . implode(',', $data) . "
				)
			";
        }

        $db->setQuery($query);
        $db->query();
    }

    function filter_list($package_id) {
        $h = "";
        $query = $this->_db->getQuery(true);
        //$query->from($this->_db->quoteName('#__se_usergroup'));
        //$query->where($this->_db->quoteName('package_id')."='".$package_id."'");
        $query = "SELECT * FROM #__se_usergroup WHERE package_id = '" . $package_id . "'";
        $this->_db->setQuery($query);
        //echo $query;
        $rs = $this->_db->loadObjectList();
        if (count($rs) > 0) {
            $h = '<h3>Rules Setting</h3>';
            $h .= '<table align="center" class="adminlist" border="0"><thead>';
            $h .= '<tr><th>ID</th><th>E-mail</th><th>Gender</th><th>City</th><th>State</th><th>Country</th><th colspan="2">Action</th></tr></thead>';
            foreach ($rs as $k => $v) {
                $h .= '<tr><td align="center">' . $v->criteria_id . '</td><td align="center">' . $v->email . '</td><td align="center">' . $v->gender . '</td><td align="center">' . $v->city . '</td><td align="center">' . $v->state . '</td><td align="center">' . $v->country . '</td>
					<td align="center"><a OnClick="if(!confirm(\'Are you sure\')) return false;" href="' . JRoute::_('?option=com_searchengine&controller=usergroup&task=delete&package_id=' . $package_id . '&criteria_id=' . $v->criteria_id) . '">delete</a></td>
					<td align="center"><a href="' . JRoute::_('?option=com_searchengine&controller=usergroup&task=edit&package_id=' . $package_id . '&criteria_id=' . $v->criteria_id) . '">edit</a></td></tr>';
            }
            $h .= '</table>';
        }
        return $h;
    }

    function getDataUserGroup($groupname,$package_id) {
        $query = $this->_db->getQuery(true);
        $query = "SELECT * FROM #__se_usergroup WHERE group_name = '" . $groupname . "' AND package_id = '" . $package_id . "'";
        $this->_db->setQuery($query);
        $rs = $this->_db->loadObjectList();
        return $rs;
    }

    function getAllUsers($groupname, $package_id) {
        $query = $this->_db->getQuery(true);
        $query = "SELECT * FROM #__se_useraccounts as a
		inner join #__users r on r.id = a.id
		inner join #__se_usergroup g on g.useraccount_id = a.id
		where a.package_id = '" . $package_id . "' AND g.group_name ='" . $groupname . "'";
        $this->_db->setQuery($query);
        $rs = $this->_db->loadObjectList();
        return $rs;
    }

    function get_query($field, $v, $population) {
        if ($population > 0) {
            $rand = "ORDER BY RAND() LIMIT " . ceil(($population / 100) * $this->get_total($this->check_field($field, $v)));
        }
        return $this->check_field($field, $v, $rand);
    }

    function filter_per_row($id) {
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->quoteName('#__se_usergroup'));
        $query->where($this->_db->where('criteria_id') . "='" . (int) $id . "'");
        $this->_db->setQuery($query);
        $v = $this->_db->loadObject();
        if ($v) {
            $field = $v->field;
            if ($v->from_age > 0 && $v->to_age > 0) {
                $age = "AND YEAR( NOW( ) ) - YEAR( birthday ) BETWEEN $v->from_age AND $v->to_age";
            }
            $qry = "SELECT * FROM #__se_useraccounts t, #__users u WHERE t.id = u.id $age AND email LIKE '%$v->email%' AND gender LIKE '%$v->gender%' AND street LIKE '%$v->street%' AND city LIKE '%$v->city%' AND state LIKE '%$v->state%' AND country = '$v->country' GROUP BY username";
            $this->_db->setQuery($qry);
            $rs = $this->_db->loadObjectList();
            if (count($rs) > 0) {
                foreach ($rs as $k => $v) {
                    $h .= '<tr><td align="center"><a href="' . JRoute::_('index.php?option=com_searchengine&controller=usergroup&field=' . $field . '&package_id=' . JRequest::getVar('package_id') . '&criteria_id=' . $id) . '">' . $id . '</a></td><td align="center">' . $v->firstname . ' ' . $v->lastname . ' (' . $v->username . ') </td>';
                    if ($v->birthday != '0000-00-00') {
                        $age = date("Y", strtotime(date("r"))) - date("Y", strtotime($v->birthday));
                    } else {
                        $age = '';
                    }
                    $h .= '<td>' . $age . '</td><td align="center">' . $v->email . '</td><td align="center">' . $v->gender . '</td>
					<td align="center">' . $v->city . '</td><td align="center">' . $v->state . '</td><td align="center">' . $v->country . '</td>
					</tr>';
                }
            } else {
                $h .= '<tr><td align="center"><a href="' . JRoute::_('index.php?option=com_searchengine&controller=usergroup&package_id=' . JRequest::getVar('package_id') . '&criteria_id=' . $id) . '">' . $id . '</a></td><td></td><td></td><td></td>
					<td></td><td></td><td></td><td></td>
					</tr>';
            }
            return $h;
        }
    }

    //
    function search_result($package_id) {
        $user = JFactory::getUser();
        $app = JFactory::getApplication();
		$groupname = JRequest::getVar('title');
        $filter_order = $app->getUserStateFromRequest('com_searchengine.usergroup.filter_order', 'filter_order', 't.firstname', 'cmd');
        $filter_order_dir = $app->getUserStateFromRequest('com_searchengine.usergroup.filter_order_dir', 'filter_order_Dir', 'DESC', 'word');

        $db = & JFactory::getDBO();
        /*
          $queryu = $this->_db->getQuery(true);
          $queryu->update($this->_db->quoteName('#__se_useraccounts'));
          $queryu->set($this->_db->quoteName('package_id')."='0'");
          $queryu->where($this->_db->quoteName('package_id')."='".$package_id."'");
          $this->_db->setQuery($queryu);
          $this->_db->query();
         */
        //query
        $query = $this->_db->getQuery(true);
        $query->select('t.*,b.birthday,b.id');
        $query->from($this->_db->quoteName('#__se_usergroup','t'));
		$query->join('INNER', $db->quoteName('#__se_useraccounts', 'b') . ' ON (' . $db->quoteName('t.email') . ' = ' . $db->quoteName('b.email') . ')');
        $query->where($this->_db->quoteName('t.package_id') . "='" . $package_id . "'");
		
		

        if (JRequest::getVar('command') == '1') {
            $query->where($this->_db->quoteName('t.is_presentation') . " = '1' AND ".$this->_db->quoteName('t.group_name') . " = '" . $groupname . "'");
            //$query->where("(" . $this->_db->quoteName('field')." != 'name' OR " . $this->_db->quoteName('field') . " != 'email' ) ");
        } else {
             $query->where($this->_db->quoteName('t.is_presentation') . " is null ");
        }

        $this->_db->setQuery($query);
        $rs = $this->_db->loadObjectList();
        if (count($rs) > 0) {
            foreach ($rs as $k => $v) {
                $num = ceil(($v->population / 100) * $this->get_total($this->check_field($package_id, $v->field, $v)));
                $usr[] = $this->get_result($this->check_field($package_id, $v->field, $v, $num));
            }
            $new_array = array();
            foreach ($usr as $array) {
                foreach ($array as $k => $v) {
                    if (!array_key_exists($k, $new_array)) {
                        $new_array[$k] = $v;
                    }
                }
            }
            foreach ($new_array as $k => $v) {
                // $this->update_user_package_id($package_id, $k);
            }

            $order = ' order by ' . $filter_order . ' ' . $filter_order_dir;

            $query = $this->_db->getQuery(true);
            $query->select('t.*,b.*');
            $query->from($this->_db->quoteName('#__se_usergroup') . ' AS t');
			$query->join('INNER', $db->quoteName('#__se_useraccounts', 'b') . ' ON (' . $db->quoteName('t.email') . ' = ' . $db->quoteName('b.email') . ')');

            if (JRequest::getVar('command') == '1') {
            $query->where($this->_db->quoteName('t.is_presentation') . " = '1' AND ".$this->_db->quoteName('t.group_name') . " = '" . $groupname . "'");
            }

            $query->where("t.package_id='" . (int) $package_id . "' " );


            $this->_db->setQuery($query);
            $row = $this->_db->loadAssocList();

            $query = '
            		SELECT count(*) FROM #__se_useraccounts AS t,
					#__users  AS u
					WHERE t.`id` = u.`id`
					AND package_id = ' . $package_id . '
            	';

            if (JRequest::getVar('command') == '1') {
                $query .= " AND is_presentation = '1' ";
            }

            $this->_db->setQuery($query);
            $total = $this->_db->loadResult();
            $return['lists'] = array(
                'order' => $filter_order,
                'order_dir' => $filter_order_dir,
                'data' => $row,
                'total' => $total
            );
            return $return;

            //return ($row);
        }
    }

    function update_user_package_id($package_id, $id) {
        //set query
        $query = $this->_db->getQuery(true);
        $query->update($this->_db->quoteName('#__se_useraccounts'));

        if (JRequest::getVar('command') == '1') {
            $query->set($this->_db->quoteName('is_presentation') . " = '1' ");
        } else {
            $query->set($this->_db->quoteName('package_id') . "='" . $package_id . "'");
        }
        $query->where($this->_db->quoteName('id') . "='" . (int) $id . "'");

        //build query
        $this->_db->setQuery($query);

        $result = $this->_db->query();
    }

    function get_result($qry) {
        //set query
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->quoteName('#__se_useraccounts') . ' AS t');
        $query->from($this->_db->quoteName('#__users') . ' AS u');

        //$where = "package_id = '".JRequest::getVar('package_id')."' ";

        if (JRequest::getVar('command') == '1') {
            $where = "package_id = '" . JRequest::getVar('package_id') . "' ";
        } else {
            $where = "1=1";
        }


        if (!empty($qry) || $qry != "") {
            $where .= " AND " . $qry;
        }
        $query->where($where);

        $this->_db->setQuery($query);
        $rs = $this->_db->loadObjectList();
        if (count($rs) > 0) {
            foreach ($rs as $k => $v) {
                $user[$v->id] = array(
                    'package_id' => $v->package_id,
                    'id' => $v->id,
                    'username' => $v->username,
                    'firstname' => $v->firstname,
                    'lastname' => $v->lastname,
                    'birthday' => $v->birthday,
                    'email' => $v->email,
                    'gender' => $v->gender,
                    'street' => $v->street,
                    'city' => $v->city,
                    'state' => $v->state,
                    'post_code' => $v->post_code,
                    'country' => $v->country,
                    'phone' => $v->phone,
                );
            }
            return $user;
        }
    }

    function get_total($qry) {
        //set query
        $query = $this->_db->getQuery(true);
        $query->select('t.email');
        $query->from($this->_db->quoteName('#__se_useraccounts') . ' AS t');
        $query->from($this->_db->quoteName('#__users') . ' AS u');
        if (!empty($qry) || $qry != "") {
            $query->where($qry);
        }

        $this->_db->setQuery($query);
        $rs = $this->_db->loadObjectList();
        return count($rs);
    }

    function check_field($package_id, $field, $v, $limit = 0) {
        if ($limit > 0) {
            $rand = "ORDER BY RAND() LIMIT $limit";
        }
        switch ($field) {
            case 'name':
                if (JRequest::getVar('command') != '1') {
                    $qry = "1=1";
                } else {
                    $qry = "t.id = u.id AND lower(t.firstname) LIKE '%" . strtolower($v->firstname) . "%'
						AND lower(lastname) LIKE '%" . strtolower($v->lastname) . "%' ";
                }
                break;
            case 'email':
                if (JRequest::getVar('command') != '1') {
                    $qry = "1=1";
                } else {
                    $qry = "t.id = u.id AND lower(t.email) LIKE '%" . strtolower($v->email) . "%' ";
                }
                break;
            case 'age':
                $qry = "t.id = u.id AND YEAR( NOW( ) ) - YEAR( t.birthday ) BETWEEN $v->from_age AND $v->to_age ";
                break;
            case 'gender':
                $qry = "t.id = u.id AND t.gender = '$v->gender' ";
                break;
            case 'location':
                //$qry = "t.id = u.id AND lower(t.street) LIKE '%" . strtolower($v->street) . "%' AND lower(city) LIKE '%" . strtolower($v->city) . "%' AND state LIKE '%$v->state%' AND post_code LIKE '%$v->post_code%' AND country = '$v->country' ";

                $qry = "t.id = u.id  AND state LIKE ".$this->_db->Quote('%'.$v->state.'%')." AND post_code LIKE '%$v->post_code%' AND country = '$v->country' ";
                break;
            case 'New' :
                $qry = '1=1';
                break;
        }

        $qry .= " AND (package_id IS NULL OR package_id = '" . $package_id . "') " . $rand;
        return $qry;
    }

    function filter_field($package_id, $field) {
		$groupname = JRequest::getVar('title');
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->quoteName('#__se_usergroup'));
        $query->where($this->_db->quoteName('package_id') . "='" . $package_id . "'");
        $query->where($this->_db->quoteName('field') . "='" . $field . "'");

        if (JRequest::getVar('command') == '1') {
            $query->where($this->_db->quoteName('is_presentation') . " = '1' AND ".$this->_db->quoteName('group_name') . " = '" . $groupname . "'");
        } else {
            $query->where($this->_db->quoteName('is_presentation') . " is null ");
        }

        $this->_db->setQuery($query);
        $fields = $this->_db->loadObjectList();
        //return data
        return $fields;
    }

    function filter_field_crit($package_id, $field) {
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->quoteName('#__se_usergroup'));
        $query->where($this->_db->quoteName('package_id') . "='" . $package_id . "'");
        $query->where($this->_db->quoteName('field') . "='" . $field . "'");

        if (JRequest::getVar('command') == '1') {
            $query->where($this->_db->quoteName('is_presentation') . " in ('1','0') ");
        }

        $this->_db->setQuery($query);
        $fields = $this->_db->loadObjectList();
        //return data
        return $fields;
    }

    function getParentUserGroup($package_id, $field) {
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->quoteName('#__se_usergroup'));
        $query->where($this->_db->quoteName('package_id') . "='" . $package_id . "'");
        //$query->where($this->_db->quoteName('field')."='".$field."'");
        $query->where($this->_db->quoteName('is_presentation') . " IS NULL");
        $query->where($this->_db->quoteName('parent_usergroup') . " IS NULL");
        $this->_db->setQuery($query);
        $fields = $this->_db->loadObjectList();
        //return data
        return $fields;
    }

    function getNameUserGroupPresentation($package_id, $field, $criteria) {
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->quoteName('#__se_usergroup'));
        $query->where($this->_db->quoteName('package_id') . "='" . $package_id . "'");
        $query->where($this->_db->quoteName('criteria_id') . "='" . $criteria . "'");
        $query->where($this->_db->quoteName('field') . "='" . $field . "'");
        $query->where($this->_db->quoteName('is_presentation') . " IS NOT NULL");
//        $query->where($this->_db->quoteName('parent_usergroup') . " IS NOT NULL");
        $this->_db->setQuery($query);
        $fields = $this->_db->loadObjectList();
        //return data
        return $fields;
    }

    function isRegistered($data) {
        $query = "select * from #__se_usergroup where package_id = '" . $data->package_id .
                "' and is_presentation is null";
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();
        return $rows;
    }

    function isExistsGroup($data) {
        $query = "select * from #__se_usergroup where ( 1!=1
                " . ((!empty($data['firstname']) && $data['firstname'] != '') ? ' and lower(firstname) like \'%' . strtolower($data['firstname']) . '%\' ' : '') . "
                " . ((!empty($data['lastname']) && $data['lastname'] != '') ? ' and lower(lastname) like \'%' . strtolower($data['lastname']) . '%\' ' : '') . "
                " . ((!empty($data['email']) && $data['email'] != '') ? ' and lower(email) like \'%' . strtolower($data['email']) . '%\' ' : '') . "
                " . ((!empty($data['from_age']) && $data['from_age'] != '') ? ' and from_age = \'' . $data['from_age'] . '\' ' : '') . "
                " . ((!empty($data['to_age']) && $data['to_age'] != '') ? ' and to_age = \'' . $data['to_age'] . '\' ' : '') . "
                " . ((!empty($data['gender']) && $data['gender'] != '') ? ' and lower(gender) = \'' . strtolower($data['gender']) . '\' ' : '') . "
                " . ((!empty($data['street']) && $data['street'] != '') ? ' and lower(street) like \'%' . strtolower($data['street']) . '%\' ' : '') . "
                )
                and package_id = '" . $data['package_id'] . "'
                " . (JRequest::getVar('command') == 1 ? ' and is_presentation = \'1\'  ' : ' and is_presentation is null ' ) . ";
			";
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();
        if (!empty($rows)) {
            return true;
        } else {
            return false;
        }
    }

    function checkUserIsExist($data) {
        $email = $data['email'];
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__se_useraccounts'));
        $query->where('email like ' . $db->quote("%$email%"));
        $db->setQuery($query);
        $result = $db->loadObjectList();

        if (!empty($result)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function checkUserIsRegstered($data) {
        $email = $data['email'];
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__se_usergroup');
        $query->where('email like ' . $db->quote("%$email%"));
        $db->setQuery($query);
        $result = $db->loadObjectList();

        if (!empty($result)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function isGroupExist($data) {
        $query = "select * from #__se_usergroup where ( 1!=1
                " . ((!empty($data['firstname']) && $data['firstname'] != '') ? ' and lower(firstname) like \'%' . strtolower($data['firstname']) . '%\' ' : '') . "
                " . ((!empty($data['lastname']) && $data['lastname'] != '') ? ' and lower(lastname) like \'%' . strtolower($data['lastname']) . '%\' ' : '') . "
                " . ((!empty($data['email']) && $data['email'] != '') ? ' and lower(email) like \'%' . strtolower($data['email']) . '%\' ' : '') . "
                " . ((!empty($data['from_age']) && $data['from_age'] != '') ? ' and from_age = \'' . $data['from_age'] . '\' ' : '') . "
                " . ((!empty($data['to_age']) && $data['to_age'] != '') ? ' and to_age = \'' . $data['to_age'] . '\' ' : '') . "
                " . ((!empty($data['gender']) && $data['gender'] != '') ? ' and lower(gender) = \'' . strtolower($data['gender']) . '\' ' : '') . "

                " . ((!empty($data['street']) && $data['street'] != '') ? ' and lower(street) like \'%' . strtolower($data['street']) . '%\' ' : '') . "
                )
                and package_id = '" . JRequest::getInt("package_id") . "'";
        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();
        if (!empty($rows)) {
            return $rows;
        } else {
            return false;
        }
    }

    function updatePackageForUserAccounts($package_id, $account_id) {
        $query = "update #__se_useraccounts set is_presentation = '1' where ap_account_id in (" . $account_id . ") and package_id = '" . $package_id . "' ";
        $this->_db->setQuery($query);
        $this->_db->query();
    }

    function selectCountryForUserGroup($package_id) {
        $query = "SELECT DISTINCT country from #__se_useraccounts where package_id = '" . $package_id . "'";
        $this->_db->setQuery($query);
        $fields = $this->_db->loadObjectList();
        $countries = array();
        foreach ($fields as $field) {
            $countries[] = $field->country;
        }
        //return data
        //var_dump($countries);
        return $countries;
    }

    function selectGenderForUserGroup($package_id) {
        $query = "SELECT DISTINCT gender FROM #__se_useraccounts where package_id = '$package_id' ";
//		SELECT gender FROM `f2skf_ap_useraccounts` where `package_id`='5'
        $this->_db->setQuery($query);
        $fields = $this->_db->loadObjectList();
        $genders = array();
        foreach ($fields as $field) {
            $genders[] = $field->gender;
        }
        //return data
        $genders = $fields;
        return $genders;
    }

    function addItem($data) {
        //instantiate db class
        $db = JFactory::getDbo();

        //get column and values
        $columns = '';
        $values = '';

        foreach ($data as $col => $val) {
            $columns .= $db->quoteName($col) . ',';
            $values .= $db->quote($val) . ',';
        }

        $query = $db->getQuery(true);
        $query->insert('#__se_usergroup');
        $query->columns(trim($columns, ","));
        $query->values(substr_replace($values, "", -1));

        $db->setQuery($query);

        if (!$db->query()) {
            return false;
        }
        return true;
    }

}
