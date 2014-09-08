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
	'BIRTH_DATE'	=> 'Дата на раждане',
	'BDAY_NO_DATE'	=> 'Моля изберете дата на раждане. Не може датата на раждане да е празна.',
	'BDAY_TO_YOUNG'	=> 'Вие нямате право да се регистрирате в този форум. <br /> Форума е за лица над %1$d години.',
	'BC_SHOW_BDAY'	=> 'Показвай възраст',
	'BDAY_NA'	=> 'n/a',

));
