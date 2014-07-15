<?php
/**
*
* Birthday Control extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 Lucifer <http://www.anavaro.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'BDAY_NO_DATE'	=> 'Please select a date of birth. You can not register if you do not add day of birth.',
	'BDAY_TO_YOUNG'	=> 'You are not old enough to register to this board. <br /> This board has minum age of %1$d years.',
	'BC_SHOW_BDAY'	=> 'Show age',
	'BDAY_NA'	=> 'n/a',

));
