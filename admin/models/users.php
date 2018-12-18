<?php
/**
 * @version		$Id: users.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Quiz
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class AwardpackageModelUsers extends JModelLegacy {
	
	function __construct() {
		 
		parent::__construct();
	}
	
	public function refresh_user_stats(){
		
		$query = 'insert ignore into #__quiz_users (id) select id from #__users';
		$this->_db->setQuery($query);
		$this->_db->query();
		
		$query = 'update #__quiz_users u set u.quizzes = (select count(*) from #__quiz_quizzes q where q.created_by = u.id)';
		$this->_db->setQuery($query);
		$this->_db->query();
		
		$query = 'update #__quiz_users u set u.responses = (select count(*) from #__quiz_responses r where r.created_by = u.id)';
		$this->_db->setQuery($query);
		$this->_db->query();
	}
	
	public function get_all_active_users(){
		
		$query = '
				select 
					au.id, u.name, u.username 
				from 
					#__quiz_users au 
				left join 
					#__users u on au.id = u.id 
				where 
					au.quizzes > 0';
		
		$this->_db->setQuery($query);
		$users = $this->_db->loadObjectList();
		
		return $users;
	}
	
	public function get_users(){
		
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		
		$filter_order = $app->getUserStateFromRequest( A_APP_NAME.'.users.filter_order', 'filter_order', 'au.quizzes', 'cmd' );
		$filter_order_dir = $app->getUserStateFromRequest( A_APP_NAME.'.users.filter_order_dir', 'filter_order_Dir', 'DESC', 'word' );
		$limitstart = $app->getUserStateFromRequest( A_APP_NAME.'.users.limitstart', 'limitstart', '', 'int' );
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit', 20), 'int');

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		
        $search = $app->input->post->getString('search', '');
        
        $wheres = array();
        $return = array();
        
        if($search){
        	
        	$wheres[] = 'u.username like \'%'.$this->_db->escape($search).'%\' or u.name like \'%'.$this->_db->escape($search).'%\'';
        }
        
        $where = ((count($wheres) > 0) ? ' where ('.implode(' ) and ( ', $wheres).')' : '');
        $order = ' order by ' . $filter_order . ' ' . $filter_order_dir;
        
        $query = '
        		select 
        			au.id, au.quizzes, au.responses,
        			u.id as userid, u.name, u.username, u.lastvisitDate
        		from 
        			#__quiz_users as au
        		left join 
        			#__users as u ON au.id = u.id'.
        		$where.
        		$order;
        
        $this->_db->setQuery($query, $limitstart, $limit);
        $return['users'] = $this->_db->loadObjectList();
        
        /************ pagination *****************/
        $query = 'select count(*) from #__quiz_users as au left join #__users as u on au.id = u.id'.$where;
        
        jimport('joomla.html.pagination');
        $this->_db->setQuery($query);
        $total = $this->_db->loadResult();
        
        $return['pagination'] = new JPagination( $total, $limitstart, $limit );
        /************ pagination *****************/
        
        $return['lists'] = array(
        		'limitstart'=>$limitstart, 
        		'limit'=>$limit, 
        		'order'=>$filter_order, 
        		'order_dir'=>$filter_order_dir,
        		'search'=>$search);
        
        return $return;
	}
	
	function delete($ids){

		$id = implode(',', $ids);
		
		$query = 'delete from #__quiz_users where id in ('.$id.')';
		$this->_db->setQuery($query);
		
		if($this->_db->query()){
				
			return true;
		}
		
		return false;
	}
}