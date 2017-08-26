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
	'BIRTHDAY_CONTROL'	=> 'Controlar los requisitos de cumpleaños',
	'BIRTHDAY_REQUIRE'	=> 'Cumpleaños requerido',
	'BIRTHDAY_REQUIRE_EXPLAIN'	=> 'Requiere que el usuario ingrese la edad para registrarse aquí.',
	'BIRTHDAY_MIN_AGE'	=> 'Edad mínima',
	'BIRTHDAY_MIN_AGE_EXPLAIN'	=> 'Requiere una edad mínima para registrarse en este foro.',
	'BIRTHDAY_SHOW_POST'	=> 'Mostrar edad en los mensajes',
	'BIRTHDAY_SHOW_POST_EXPLAIN'	=> 'Mostrar la edad del usuario en el perfil pequeño viendo la publicación.',

));
