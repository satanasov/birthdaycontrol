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
	'BIRTHDAY_CONTROL'	=> 'Настройки на възрастта',
	'BIRTHDAY_REQUIRE'	=> 'Изисквай възраст',
	'BIRTHDAY_REQUIRE_EXPLAIN'	=> 'Изисвай възраст за регистрация на потребителя',
	'BIRTHDAY_MIN_AGE'	=> 'Минимална възраст',
	'BIRTHDAY_MIN_AGE_EXPLAIN'	=> 'Минимална изисквана възраст за регистрация.',
	'BIRTHDAY_SHOW_POST'	=> 'Показвай възраст в постовете',
	'BIRTHDAY_SHOW_POST_EXPLAIN'	=> 'Показвай възрастта на потребителя в постовете му.',

));
