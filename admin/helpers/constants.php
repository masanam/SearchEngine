<?php
/**
 * @version		$Id: constants.php 01 2011-11-08 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted access');

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

// Please do not touch these until and unless you know what you are doing.
define('S_CURR_VERSION',				'3.6.2');
define('S_CJLIB_VER',					'2.0.0');

define('S_USER_NAME',					'user_name' );
define('S_USER_AVTAR',					'user_avatar' );
define('S_DEFAULT_EDITOR',				'default_editor' );
define('S_HIDE_TEMPLATE',				'hide_template' );
define('S_ENABLE_DEBUGGING',			'enable_debugging' );
define('S_LIST_LIMIT',					'list_limit' );
define('S_PERM_GUEST_ACCESS',			'permission_guest_access' );
define('S_PERM_ACCESS',					'permission_access' );
define('S_PERM_CREATE',					'permission_create' );
define('S_PERM_WYSIWYG',				'permission_wysiwyg' );
define('S_PERM_MANAGE',					'permission_manage' );
define('S_SENDER_NAME',					'email_sender_name' );
define('S_SENDER_EMAIL',				'email_sender_email' );
define('S_ADMIN_EMAIL',					'notif_admin_email' );
define('S_NOTIF_ADMIN_RESPONSES',		'notif_admin_responses');
define('S_NOTIF_USER_RESPONSES',		'notif_user_responses');
define('S_POINTS_SYSTEM',				'points_system' );
define('S_POINTS_PER_CREDIT',			'points_per_credit' );
define('S_ENABLE_POINTS_FOR_RESPONSE',	'enable_points_for_response');
define('S_ENABLE_RELATED_SURVEYS',		'enable_related_surveys');
define('S_FILTERED_KEYWORDS',			'filtered_keywords');
define('S_ENABLE_INVITE_JS_GROUPS',		'enable_invite_jomsocial_groups');

define('S_COOKIE_PREFIX',				'CSCKRSPNCE');
define('S_AUP_CREDITS',					'sysplgaup_survey_credits');
define('S_AUP_RESPONSE_POINTS',			'sysplgaup_response_points');
define('S_JOMSOCIAL_CREDITS',			'com_communitysurveys.credits');
define('S_JOMSOCIAL_RESPONSE_POINTS',	'com_communitysurveys.response');
define('S_QUEUE_INVITE_REGISTERED',		'com_communitysurveys.invite.registered');
define('S_QUEUE_INVITE_GROUPS',			'com_communitysurveys.invite.groups');
define('S_QUEUE_INVITE_JSGROUPS',		'com_communitysurveys.invite.jsgroups');
define('S_PAGE_HEADER', 				1);
define('S_CHOICE_RADIO', 				2);
define('S_CHOICE_CHECKBOX', 			3);
define('S_CHOICE_SELECT', 				4);
define('S_GRID_RADIO',					5);
define('S_GRID_CHECKBOX', 				6);
define('S_FREE_TEXT_SINGLE_LINE',		7);
define('S_FREE_TEXT_MULTILINE',			8);
define('S_FREE_TEXT_PASSWORD',			9);
define('S_FREE_TEXT_RICH_TEXT',			10);
define('S_IMAGE_CHOOSE_IMAGE',			11);
define('S_IMAGE_CHOOSE_IMAGES',			12);
define('S_SPECIAL_NAME',				13);
define('S_SPECIAL_EMAIL',				14);
define('S_SPECIAL_CALENDAR',			15);
define('S_SPECIAL_ADDRESS',				16);

define("S_MEDIA_URI",					JURI::root(true).'/media/'.'S_APP_NAME'.'/');
define('S_TEMP_STORE',					JPATH_ROOT.DS.'media'.DS.'communitysurveys'.DS.'tmp');
define('S_TEMP_STORE_URI',				JURI::root(false).'media/communitysurveys/tmp/');
define('S_IMAGES_UPLOAD_DIR',			JPATH_ROOT.DS.'media'.DS.'communitysurveys'.DS.'images');
define('S_IMAGES_URI',					JURI::root(false).'media/communitysurveys/images/');

define("CQ_CURR_VERSION",               "3.3.1");
define('CQ_CJLIB_VER',					'2.0.0');
define("CQ_ASSET_ID",					4);

define("T_QUIZ_CONFIG",					"#__quiz_config");
define("T_QUIZ_CATEGORIES",				"#__quiz_categories");
define("T_QUIZ_QUIZZES",				"#__quiz_quizzes");
define("T_QUIZ_PAGES",					"#__quiz_pages");
define("T_QUIZ_QUESTIONS",				"#__quiz_questions");
define("T_QUIZ_ANSWERS",				"#__quiz_answers");
define("T_QUIZ_RESPONSES",				"#__quiz_responses");
define("T_QUIZ_RESPONSE_DETAILS",		"#__quiz_response_details");
define("T_QUIZ_COUNTRIES",				"#__quiz_countries");
define("T_QUIZ_USERS",					"#__quiz_users");
define('T_QUIZ_TAGS',					'#__quiz_tags');
define('T_QUIZ_TAGS_MAP',				'#__quiz_tagmap');
define('T_QUIZ_TAGS_STATS',				'#__quiz_tags_stats');

define("CQ_SESSION_CONFIG",				"quiz_session_config");
define("CQ_JSP_NEW_QUIZ",				'Q_APP_NAME'.".new_quiz");
define("CQ_JSP_QUIZ_RESPONSE",			'Q_APP_NAME'.".quiz_response");
define("CQ_MEDIA_URI",					JURI::root(true).'/media/'.'Q_APP_NAME'.'/');

define("CQ_MEDIA_URI_2",				JURI::root(true).'/administrator/components/'.'Q_APP_NAME'.'/assets/');
define('CQ_TEMP_STORE',					JPATH_ROOT.DS.'media'.DS.'communityquiz'.DS.'tmp');
define('CQ_TEMP_STORE_URI',				JURI::root(false).'media/communityquiz/tmp/');
define('CQ_IMAGES_UPLOAD_DIR',			JPATH_ROOT.DS.'media'.DS.'communityquiz'.DS.'images');
define('CQ_IMAGES_URI',					JURI::root(false).'media/com_awardpackage/images/');
define('CQ_IMAGES_URI_2',				JURI::root(false).'administrator/components/com_awardpackage/assets/images/');
define('PRIZE_IMAGES_URI',				JURI::root(false).'administrator/components/com_awardpackage/asset/prize/');
define('SYMBOL_IMAGES_URI',				JURI::root(false).'administrator/components/com_awardpackage/asset/symbol/');
define('SYMBOL_IMAGES_PIECES_URI',		JURI::root(false).'administrator/components/com_awardpackage/asset/symbol/pieces/');

define("CQ_PAGE_HEADER",				1);
define("CQ_CHOICE_RADIO", 				2);
define("CQ_CHOICE_CHECKBOX", 			3);
define("CQ_CHOICE_SELECT", 				4);
define("CQ_GRID_RADIO", 				5);
define("CQ_GRID_CHECKBOX", 				6);
define("CQ_FREE_TEXT_SINGLE_LINE",		7);
define("CQ_FREE_TEXT_MULTILINE",		8);
define("CQ_FREE_TEXT_PASSWORD",			9);
define("CQ_FREE_TEXT_RICH_TEXT",		10);
define("CQ_IMAGE_CHOOSE_IMAGE",			11);
define("CQ_IMAGE_CHOOSE_IMAGES",		12);
?>
