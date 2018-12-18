<?php
//Redirect access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
//jimport(JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'giftcode.php');
class AwardPackageModelAward extends JModelLegacy{
	
	public function getInfo(){
		
		$UserInfo 	= &JFactory::getUser();
		
		$db		= &JFactory::getDBO();
		
		$Query		= "SELECT * FROM ".$db->QuoteName('#__ap_useraccounts')." WHERE id='".$UserInfo->id."'";
		
		$db->setQuery($Query);
		
		$row		= $db->loadObject();
		
		return $row;
	}
  
	function getData()
	{
		$db		= &JFactory::getDBO();
		
		$Query		= "SELECT * FROM #__symbol_symbol_prize a INNER JOIN #__symbol_presentation b ON a.presentation_id=b.presentation_id INNER JOIN #__symbol_symbol c ON a.symbol_id=c.symbol_id LEFT JOIN #__symbol_prize d ON a.id=d.id WHERE b.package_id='".$this->getInfo()->package_id."'";
		
		$db->setQuery($Query);
		
		$row		= $db->loadObjectList();
		
		return $row;
	
	}
	function getDataPiecesReceive(){
               $userid=&JFactory::getUser();
	       $pieces_ids=$this->_getList("select pieces_id from jos_gc_receive_user where user_id=".$userid->id);
	       $strpie="";
	       $tmp="";
	       if(count($pieces)<0){
			foreach($pieces_ids as &$pie){
			 
			 $strpie.=$tmp.$pie->pieces_id;
			 
			 $tmp=",";
			}
	       }
	       if($strpie!=''){
		
		$pieces=explode(',',$strpie);
		}
	     return $pieces;
	}
	function getDataPieces($symbolid){
	       $pieces_ids=$this->_getList("select symbol_pieces_id from jos_symbol_symbol_pieces where symbol_id=".$symbolid);
	       return $pieces_ids;
	}
	
	function getAwardPackage($package_id){
            $db		= &JFactory::getDBO();
            $query      = "SELECT * FROM #__ap_awardpackages WHERE package_id='".$package_id."'";
            $db->setQuery($query);
            $rows       = $db->loadObject();
            return $rows;
        }
}
