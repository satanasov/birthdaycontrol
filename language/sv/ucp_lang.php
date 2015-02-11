<?php
/**
*
* Birthday Control extension for the phpBB Forum Software package.
* Swedish translation by Holger (http://www.maskinisten.net)
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
	'BIRTH_DATE'	=> 'Födelsedag',
	'BDAY_NO_DATE'	=> 'Ange din födelsedag. Du kan ej registrera dig utan att ange din födelsedag.',
	'BDAY_TO_YOUNG'	=> 'Du är ej gammal nog för att registrera dig i detta forum. <br />Erforderlig ålder för registrering är %1$d år.',
	'BC_SHOW_BDAY'	=> 'Visa ålder',
	'BDAY_NA'	=> 'ej tillg.',

));
