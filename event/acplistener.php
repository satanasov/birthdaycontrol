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
	public function __construct(\phpbb\auth\auth $auth, \phpbb\cache\service $cache, \phpbb\config\config $config, \phpbb\db\driver\driver $db, \phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user, \phpbb\controller\helper $helper, $root_path, $php_ext, $table_prefix)
	{
		$this->auth = $auth;
		$this->cache = $cache;
		$this->config = $config;
		$this->db = $db;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->helper = $helper;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
		$this->table_prefix = $table_prefix;
	}

	public function add_options($event)
	{
		if ($event['mode'] == 'settings')
		{
			// Store display_vars event in a local variable
			$display_vars = $event['display_vars'];

			// Define my new config vars
			$my_config_vars = array(
				'legend10'	=> 'BIRTHDAY_CONTROL',
				'birthday_require' => array('lang' => 'BIRTHDAY_REQUIRE', 'validate' => 'bool', 'type' => 'radio: yes_no', 'explain' => true),
				'birthday_min_age' => array('lang' => 'BIRTHDAY_MIN_AGE', 'validate' => 'int:0:99', 'type' => 'number:0:99', 'explain' => true),
				'birthday_show_profile'	=> array('lang' => 'BIRTHDAY_SHOW_PROFILE', 'validate' => 'bool', 'type' => 'radio: yes_no', 'explain' => true),
				'birthday_show_profile_uc'	=> array('lang' => 'BIRTHDAY_SHOW_PROFILE_UC', 'validate' => 'bool', 'type' => 'radio: yes_no', 'explain' => true),
				'birthday_show_post'	=> array('lang' => 'BIRTHDAY_SHOW_POST', 'validate' => 'bool', 'type' => 'radio: yes_no', 'explain' => true),
				'birthday_show_post_uc'	=> array('lang' => 'BIRTHDAY_SHOW_PROFILE', 'validate' => 'bool', 'type' => 'radio: yes_no', 'explain' => true),
			);

			// Insert my config vars after...
			$insert_after = 'legend3';
			
			// Rebuild new config var array
			$position = array_search($insert_before, array_keys($display_vars['vars'])) - 1;
			$display_vars['vars'] = array_merge(
				array_slice($display_vars['vars'], 0, $position),
				$my_config_vars,
				array_slice($display_vars['vars'], $position)
			);

			// Update the display_vars  event with the new array
			$event['display_vars'] = array('title' => $display_vars['title'], 'vars' => $display_vars['vars']);
		}
		
	}
	
	public function var_display($event)
	{
		echo '<pre>';
		print_r($event);
		echo '</pre>';
	}
	
}
