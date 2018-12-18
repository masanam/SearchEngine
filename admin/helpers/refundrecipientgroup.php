<?php
/**
 * @version     1.0.0
 * @package     com_refund
 * @copyright   Kadeyasa
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      kadeyasa <asayedaki@yahoo.co.id> - http://kadeyasa.wordpress.com
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Refund helper.
 */
class RefundRecipientgroup
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '')
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_REFUND_TAB_NAME'),
			'index.php?option=com_refund&view=refundpackageslist',
			$vName == 'refundpackageslist'
		);
		
		JSubMenuHelper::addEntry(
			JText::_('COM_REFUND_REFUND_PACKAGE'),
			'index.php?option=com_refund&view=refundpackages',
			$vName == 'refundpackage'
		);

	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_refund';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}
	
	public static function tabs($aktif){
		echo '<div id="refund-tabs">';
			echo '<ul class="tabs">';
				echo '<li ';
					if($aktif=='name'){ echo 'class="aktif"';}
				echo '>';	
				echo '<a href=?option=com_refund&view=refundrecipientgroupname>';
				echo JText::_('COM_REFUND_TAB_NAME');
				echo '</a>';
				echo '</li>';
				echo '<li ';
					if($aktif=='location'){ echo 'class="aktif"';}
				echo '>';	
				echo '<a href=?option=com_refund&view=refundrecipientgrouplocation>';
				echo JText::_('COM_REFUND_TAB_LOCATION');
				echo '</a>';
				echo '</li>';
				echo '<li ';
					if($aktif=='email'){ echo 'class="aktif"';}
				echo '>';	
				echo '<a href=?option=com_refund&view=refundrecipientgroupemail>';
				echo JText::_('COM_REFUND_TAB_EMAIL');
				echo '</a>';
				echo '</li>';
				echo '<li ';
					if($aktif=='gender'){ echo 'class="aktif"';}
				echo '>';	
				echo '<a href=?option=com_refund&view=refundrecipientgroupgender>';
				echo JText::_('COM_REFUND_TAB_GENDER');
				echo '</a>';
				echo '</li>';
				echo '<li ';
					if($aktif=='age'){ echo 'class="aktif"';}
				echo '>';	
				echo '<a href=?option=com_refund&view=refundrecipientgroup>';
				echo JText::_('COM_REFUND_TAB_AGE');
				echo '</a>';
				echo '</li>';
			echo '</ul>';
		echo '</div>';
	}
}
