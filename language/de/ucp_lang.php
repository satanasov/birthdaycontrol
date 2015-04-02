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
	'BIRTH_DATE'	=> 'Geburtsdatum',
	'BDAY_NO_DATE'	=> 'Bitte wähle ein Geburtsdatum. Du kannst dich nicht registrieren, wenn Du nicht dein Geburtsdatum hinzufügst.',
	'BDAY_TO_YOUNG'	=> 'Du bist nicht alt genug, um dich in diesem Forum zu registrieren. <br /> Für dieses Forum musst Du mindestens %1$d alt sein.',
	'BC_SHOW_BDAY'	=> 'Alter anzeigen',
	'BDAY_NA'		=> 'n/a',

));
