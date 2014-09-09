<?php

/**
*
* Birthday Control extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 Lucifer <http://www.anavaro.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace anavaro\birthdaycontrol\event;

/**
* Event listener
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class acplistener implements EventSubscriberInterface
{
	static public function getSubscribedEvents()
	{
		return array(
			'core.acp_board_config_edit_add'	=>	'add_options',
		);
	}

	/**
	* Constructor
	* NOTE: The parameters of this method must match in order and type with
	* the dependencies defined in the services.yml file for this service.
	*
	* @param \phpbb\auth		$auth		Auth object
	* @param \phpbb\cache\service	$cache		Cache object
	* @param \phpbb\config	$config		Config object
	* @param \phpbb\db\driver	$db		Database object
	* @param \phpbb\request	$request	Request object
	* @param \phpbb\template	$template	Template object
	* @param \phpbb\user		$user		User object
	* @param \phpbb\content_visibility		$content_visibility	Content visibility object
	* @param \phpbb\controller\helper		$helper				Controller helper object
	* @param string			$root_path	phpBB root path
	* @param string			$php_ext	phpEx
	*/
	public function __construct(\phpbb\controller\helper $helper)
	{
		$this->helper = $helper;
	}

	public function add_options($event)
	{
		//$this->var_display($event['display_vars']);
		if ($event['mode'] == 'settings')
		{
			// Store display_vars event in a local variable
			$display_vars = $event['display_vars'];

			// Define my new config vars
			$my_config_vars = array(
				'legend10'	=> 'BIRTHDAY_CONTROL',
				'allow_birthdays' => array('lang' => 'ALLOW_BIRTHDAYS', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'birthday_require' => array('lang' => 'BIRTHDAY_REQUIRE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				'birthday_min_age' => array('lang' => 'BIRTHDAY_MIN_AGE', 'validate' => 'int:0:99', 'type' => 'number:0:99', 'explain' => true),
				'birthday_show_post'	=> array('lang' => 'BIRTHDAY_SHOW_POST', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
			);

			$display_vars['vars'] = phpbb_insert_config_array($display_vars['vars'], $my_config_vars, array('after' =>'warnings_expire_days'));
			// Update the display_vars  event with the new array
			$event['display_vars'] = array('title' => $display_vars['title'], 'vars' => $display_vars['vars']);
			//$this->var_display($display_vars['vars']);
		}

	}

	public function var_display($event)
	{
		echo '<pre>';
		print_r($event);
		echo '</pre>';
	}

}
