<?php
/**
 * @version		$Id: header.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

class DateHelper {
	
	public static function dateDifference($date1, $date2, $format ='d:H:i:s'){
		$date1=strtotime($date1);
		$date2=strtotime($date2); 
		$diff = abs($date1 - $date2);
		
		$day = $diff/(60*60*24); // in day
		$dayFix = floor($day);
		$dayPen = $day - $dayFix;
		if($dayPen > 0)
		{
			$hour = $dayPen*(24); // in hour (1 day = 24 hour)
			$hourFix = floor($hour);
			$hourPen = $hour - $hourFix;
			if($hourPen > 0)
			{
				$min = $hourPen*(60); // in hour (1 hour = 60 min)
				$minFix = floor($min);
				$minPen = $min - $minFix;
				if($minPen > 0)
				{
					$sec = $minPen*(60); // in sec (1 min = 60 sec)
					$secFix = floor($sec);
				}
			}
		}
		$str = "";
		/*if($dayFix > 0)
			$str.= $dayFix." day ";
		if($hourFix > 0)
			$str.= $hourFix." hour ";
		if($minFix > 0)
			$str.= $minFix." min ";
		if($secFix > 0)
			$str.= $secFix." sec ";*/

		switch ($format) {
			case 'd:H:i:s':
				$str.= ($dayFix > 0)?$dayFix.':':'00:' ;
				
				$str.= ($hourFix > 0)?$hourFix.':':'00:' ;
				
				$str.= ($minFix > 0)?$minFix.':':'00:' ;
				
				$str.=($secFix > 0)?$secFix:'00';

				break;
			
			case 'd':
				 $str = $dayFix;
				break;

			case 'H':
				 $str = $dayFix;
				break;

			case 'i':
				 $str = $dayFix;
				break;

			case 's':
				 $str = $dayFix;
				break;

			case 'All':
				$str = $dayFix.':'.$hourFix.':'.$minFix.':'.$secFix;
				break;

		}
		

		return $str;
		
	}

	
}