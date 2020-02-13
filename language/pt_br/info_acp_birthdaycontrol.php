<?php
/**
*
* Birthday Control extension for the phpBB Forum Software package.
* Brazilian Portuguese  translation by eunaumtenhoid (c) 2017 [ver 1.0.2] (https://github.com/phpBBTraducoes)
* @copyright (c) 2014 Lucifer <http://www.anavaro.com>
* @license GNU General Public License, version 2 (GPL-2.0)

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
	'BIRTHDAY_CONTROL'	=> 'Requisitos do controle de aniversário',
	'BIRTHDAY_REQUIRE'	=> 'Exigir aniversário',
	'BIRTHDAY_REQUIRE_EXPLAIN'	=> 'Exigir que o usuario digite sua idade para se registrar aqui.',
	'BIRTHDAY_MIN_AGE'	=> 'Idade mínima',
	'BIRTHDAY_MIN_AGE_EXPLAIN'	=> 'Exigir idade mínima para que se registrem neste forum.',
	'BIRTHDAY_SHOW_POST'	=> 'Mostrar idade nas mensagens',
	'BIRTHDAY_SHOW_POST_EXPLAIN'	=> 'Mostrar a idade do usuario no mini perfil em postview.',

));
