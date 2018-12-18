<?php

defined('_JEXEC') or die;

define('DS', '/');

jimport('joomla.application.component.controller');

require_once( JPATH_COMPONENT.'/controller.php');
/*
require(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_awardpackage'.DS.'models'.DS.'configuration.php');
require(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_awardpackage'.DS.'models'.DS.'column.php');
require(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_awardpackage'.DS.'models'.DS.'content.php');
*/
//require(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_awardpackage'.DS.'models'.DS.'field.php');
/*
require(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_awardpackage'.DS.'models'.DS.'adcategory.php');
require(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_awardpackage'.DS.'models'.DS.'aduser.php');
require(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_awardpackage'.DS.'models'.DS.'position.php');
*/
require(JPATH_ADMINISTRATOR.'/components/com_awardpackage/models/category.php');

//require_once(JPATH_COMPONENT.'/lib/core.php');
$controller = JRequest::getWord('controller');
//-- Load language files
$jlang = JFactory::getLanguage();
$jlang->load('com_awardpackage', JPATH_SITE, 'en-GB', true);
$jlang->load('com_awardpackage', JPATH_SITE, null, true);
$jlang->load('com_awardpackage', JPATH_COMPONENT, null, true);

JLoader::register('SearchenginePaginationHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'searchenginepagination.php');

if($controller){
	$controller_path = strtolower($controller);
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller_path.'.php';
	require_once $path;
	$classname    = 'SearchEngineController'.ucfirst($controller);
	$controller   = new $classname( );

}else{
	$controller = JControllerLegacy::getInstance('SearchEngine');
}
	$controller->execute(JRequest::getCmd('task'));
	$controller->redirect();
?>
