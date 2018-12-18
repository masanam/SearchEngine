<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class AwardPackageModelUser extends JModelLegacy{

	var $_data;
	var $_jml;
	var $_detail;

	function _getDataQuery()
	{

		$query='SELECT a.queue_id,a.user_id, b.name, a.date_created, Count(c.queuedetail_id) as Jumlah FROM #__symbol_queue a Inner Join #__symbol_user b on a.user_id=b.user_id  Inner Join #__symbol_queue_detail c on a.queue_id=c.queue_id GROUP BY a.queue_id ORDER BY a.queue_id DESC';
		return $query;

	}

	function getData()
	{

		if(empty($this->_data))
		{

			$qry = "select * from #__symbol_user";
			$this->_data=$this->_getList( $qry );

		}

		return $this->_data;

	}


	function getDataDetail($gcid)
	{

		if(empty($this->_data))
		{
				
			$this->_data=$this->_getList("SELECT a.queuedetail_id, b.symbol_pieces_image, c.symbol_image FROM #__symbol_queue_detail  a
            Inner Join #__symbol_symbol_pieces b on a.symbol_pieces_id=b.symbol_pieces_id 
            INNER JOIN #__symbol_symbol  c on b.symbol_id=c.symbol_id INNER JOIN #__symbol_queue d on a.queue_id=d.queue_id  
            WHERE d.user_id=".$gcid." AND a.status='0'");

		}

		return $this->_data;

	}

	function saveData($data){
		$row =& $this->getTable('Symbolprize');

		if (!$row->bind($data)) {

			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Make sure the hello record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );
			return false;
		}
		if($data['symbol_prize_id'] == ''){
			$id = $this->_db->insertid();
		}else{
			$id = $data['symbol_prize_id'];
		}
		return $id;
	}

	function deleteData($id){
		$row =& $this->getTable('Symbolprize');
		$row->delete($id);
	}





}