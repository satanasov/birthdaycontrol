<?php
/**
*
* Birthday Control extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 Lucifer <http://www.anavaro.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
* Übersetzt von franki (http://dieahnen.de/ahnenforum/)
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
	'BIRTHDAY_CONTROL'				=> 'Alters-Kontrolle',
	'BIRTHDAY_REQUIRE'				=> 'Geburtstag erfordern',
	'BIRTHDAY_REQUIRE_EXPLAIN'		=> 'Benötigen Benutzer, gib dein Alter ein um dich zu registrieren',
	'BIRTHDAY_MIN_AGE'				=> 'Mindestalter',
	'BIRTHDAY_MIN_AGE_EXPLAIN'		=> 'Erfordert ein Mindestalter um dich in diesem Board zu registrieren',
	'BIRTHDAY_SHOW_POST'			=> 'Alter in Beiträgen anzeigen',
	'BIRTHDAY_SHOW_POST_EXPLAIN'	=> 'Zeigt das Alter im kleinen Profil in der Postview.',

));
