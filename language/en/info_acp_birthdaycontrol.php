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
	'BIRTHDAY_CONTROL'	=> 'Control birthday requirements',
	'BIRTHDAY_REQUIRE'	=> 'Require birthday',
	'BIRTHDAY_REQUIRE_EXPLAIN'	=> 'Require user to enter age to register here',
	'BIRTHDAY_MIN_AGE'	=> 'Minimum age',
	'BIRTHDAY_MIN_AGE_EXPLAIN'	=> 'Require minimum age to register in this board',
	'BIRTHDAY_SHOW_POST'	=> 'Show age in posts',
	'BIRTHDAY_SHOW_POST_EXPLAIN'	=> 'Show user age in small profile in postview.',

));
