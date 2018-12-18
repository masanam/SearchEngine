<?php

/**
 *  Component Donation for Jomla 1.6 & 1.7
 *  Version : 1.0.0
 *  This software is a property of Shane Fang
 *  Developer: Fatah Iskandar Akbar
 *  Email : info@kaiogroup.com
 *  Date: August 2011
 *
 * */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class SearchEngineViewUsergroup extends JViewLegacy {

	function __construct($config = array()) {

		parent::__construct($config);

		$this->criteria_id = JRequest::getVar('criteria_id');

		$this->processPresentation = JRequest::getVar('processPresentation');

		$this->var_id = JRequest::getVar('var_id');

		$this->model       = JModelLegacy::getInstance('main', 'SearchEngineModel');

		$this->ug          = JModelLegacy::getInstance('usergroup', 'SearchEngineModel');

		$this->class       = ' class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"';

		$this->package_id  = JRequest::getVar('package_id');

		$this->field       = JRequest::getVar('field');

	}

	function display($tpl = null) {
		 
		/*
		 if(JRequest::getVar('command') != '1'){
		 AwardpackagesHelper::addSubmenu(JRequest::getCmd('view', 'referral'), 'donation');
		 }
		 */
		$app = JFactory::getApplication();
		$this->form = $this->get('Form');
        $task = JRequest::getVar('task');
		$CountryList = new AwardpackagesHelper;
		//$countries = $CountryList->Countries_list();		
		/* Change to below code by sushil on 30-11-2015 */
		$country_list = $CountryList->Countries_list();
		
		$country_presentation = $this->ug->selectCountryForUserGroup($this->package_id);
		$gender_presentation = $this->ug->selectGenderForUserGroup($this->package_id);
		
		
		$limit = $app->getUserStateFromRequest("com_searchengine.limit", 'limit', 50, 'int');
        $limitstart = $app->input->get('limitstart', 0, 'INT');
		$registeredUser = $this->ug->registered_users($this->package_id,$limitstart,$limit);
		jimport('joomla.html.pagination');
		$pagination= new JPagination($this->ug->getTotal(), $limitstart, $limit);
		$this->assignRef('pagination', $pagination);

		$countries = (JRequest::getVar('command') == '1' ? $country_presentation : $country_list);
		
		if ($task == 'create')
		{
		
		} else {	

		$groupname = JRequest::getVar('title');

		//$filter_list = $this->ug->filter_list($this->package_id);
		
		$user_group = $this->ug->getDataUserGroup($groupname,$this->package_id);

		$users = $this->ug->getAllUsers($groupname, $this->package_id);

		$search_result = @($this->ug->search_result($this->package_id));
		
		$package_status = $this->ug->check_package($this->package_id);
		
		//print_r($package_status);
		
		foreach ($package_status as $status) {
		$published = $status->published;
		}

		$this->assignRef('published', $published);
		$this->assignRef('users', $users);		
		$this->assignRef('user_group', $user_group);
		
		$this->assignRef('task', $task);
		
		$this->assignRef('package', $package);


		//$this->assignRef('filter_list', $filter_list);

		$this->assignRef('search_result', $search_result);
		$this->assignRef('registeredUser', $registeredUser);
		$this->assignRef('countries', $countries);
		
		$this->assignRef('genders', $gender_presentation);
		
		/*foreach ( $user_group as $row ){
		if (!empty($row->group_name)){
		$groupname = $row->group_name;
		}  }*/
		
		$this->assignRef('groupname', $groupname);
		$this->data = $this->get('Data');
		
		$p_firstname = '';
		$p_lastname = '';
		$p_email = '';
		$p_from_age = '';
		$p_to_age = '';
		$p_gender = '';
		$p_street = '';
		$p_city = '';
		$p_state = '';
		$p_post_code = '';
		$p_country = '';
		$p_id = '';
		$p_birthday = '';
		foreach ($user_group as $grp){
			$p_firstname = !empty($grp->firstname) && $grp->firstname != '' ? $grp->firstname : $p_firstname;
			$p_lastname = !empty($grp->lastname) && $grp->lastname != '' ? $grp->lastname : $p_firstname;
			$p_email = !empty($grp->email) && $grp->email != '' ? $grp->email : $p_email;
			$p_from_age = !empty($grp->from_age) && $grp->from_age != '0' ? $grp->from_age : $p_from_age;
			$p_to_age = !empty($grp->to_age) && $grp->to_age != '0' ? $grp->to_age : $p_to_age;
			$p_gender = !empty($grp->gender) && $grp->gender != '' ? $grp->gender : $p_gender;
			$p_street = !empty($grp->street) && $grp->street != '' ? $grp->street : $p_street;
			$p_city = !empty($grp->city) && $grp->city != '' ? $grp->city : $p_city;
			$p_state = !empty($grp->state) && $grp->state != '' ? $grp->state : $p_state;
			$p_post_code = !empty($grp->post_code) && $grp->post_code != '' ? $grp->post_code : $p_post_code;
			$p_country = !empty($grp->country) && $grp->country != '' ? $grp->country : $p_country;		
			
	
		}		
		$this->assignRef('p_firstname', $p_firstname);
		$this->assignRef('p_lastname', $p_lastname);
		$this->assignRef('p_email', $p_email);
		$this->assignRef('p_from_age', $p_from_age);
		$this->assignRef('p_to_age', $p_to_age);
		$this->assignRef('p_gender', $p_gender);
		$this->assignRef('p_street', $p_street);
		$this->assignRef('p_city', $p_city);
		$this->assignRef('p_state', $p_state);
		$this->assignRef('p_post_code', $p_post_code);
		$this->assignRef('p_country', $p_country);
		
		if ($this->criteria_id) {
			$rs = $this->ug->criteria_info($this->creteria_id);
			if ($rs) {
				foreach ($rs as $k => $v) {
					${$k} = $rs->{$k};
					$this->assignRef($k, ${$k});
				}
			}
		}
		//add toolbar
		$total_user = $this->ug->get_usergroup();
		$total_queue = $this->ug->get_queueuser();
		$this->assignRef('total_user', $total_user);
		$this->assignRef('total_queue', $total_queue);
		}
		
		$this->addToolBar();
		//display template
		parent::display($tpl);
	}

	function addToolBar() {

		$package = $this->model->info($this->package_id)->package_name;

		$document = JFactory::getDocument();

		$document->addScript(JURI::base() . 'components/com_searchengine/assets/js/jquery.min.js');

		$document->addStyleSheet(JURI::base() . 'components/com_searchengine/assets/css/jquery.ui.all.css');

		$document->addScript(JURI::base() . 'components/com_searchengine/assets/js/jquery.ui.core.js');

		$document->addScript(JURI::base() . 'components/com_searchengine/assets/js/jquery.ui.widget.js');

		$document->addScript(JURI::base() . 'components/com_searchengine/assets/js/jquery.ui.tabs.js');

		$document->addScript(JURI::base() . 'components/com_searchengine/assets/js/tabs.js');

		$document->addScript(JURI::base() . 'components/com_searchengine/assets/js/country.js');

		JToolBarHelper::title(JText::_('User group filter ( Search Engine package : ' . $package . ' )'), 'generic.png');

		 if (JRequest::getVar('command') == '1') {
            JToolBarHelper::title(JText::_('Presentation User Group ( Search Engine package  : ' . $package . ' )'), 'generic.png');
            JToolbarHelper::back('Back', 'index.php?option=com_searchengine&view=apresentationlist&task=apresentationlist.initiate&package_id=' . JRequest::getVar('package_id') . '&idUserGroupsId=' . $this->criteria_id . '&var_id=' . $this->var_id);
		
		JToolbarHelper::back('Save & Close ', 'index.php?option=com_searchengine&view=apresentationlist&task=apresentationlist.initiate&package_id=' . JRequest::getVar('package_id') . '&title=' . $this->groupname . '&idUserGroupsId=' . $this->criteria_id . '&var_id=' . $this->var_id. '&command=1');		
			//JToolBarHelper::custom('usergroup.save_create', 'copy', 'copy', 'Save & Close', false);
			//JToolBarHelper::custom('usergroup.display', 'copy', 'copy', 'Save', false);


			
        } else {
            JToolBarHelper::title(JText::_('User Group Filter( Search Engine package  : ' . $package . ' )'), 'generic.png');
            JToolbarHelper::back('Back', 'index.php?option=com_searchengine&package_id=' . JRequest::getVar('package_id'));
        }

		//JSubMenuHelper::addEntry(JText::_('Award package'), 'index.php?option=com_awardpackage', $submenu == 'donation');
	}
}
