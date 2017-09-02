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
	'BIRTH_DATE'	=> 'Fecha de nacimiento',
	'BDAY_NO_DATE'	=> 'Por favor, seleccione una fecha de nacimiento. No puede registrarse si no añade el día de nacimiento.',
	'BDAY_TO_YOUNG'	=> 'No tiene la edad suficiente para registrarse en este foro.<br />Este foro tiene una edad mínima de %1$d años.',
	'BC_SHOW_BDAY'	=> 'Mostrar edad',
	'BDAY_NA'	=> 'n/a',

));
