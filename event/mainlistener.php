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

class mainlistener implements EventSubscriberInterface
{	
	static public function getSubscribedEvents()
    {
		return array(
			'core.common'	=>	'default_configs',
			'core.user_add_modify_data'	=> 'user_add_modify',
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

	public function default_configs($event)
	{
		$register = ($this->request->variable('mode', '') == 'register' ? true : false);
		if ($register)
		{
			if ($this->config['birthday_require'])
			{
				$day = $this->request->variable('bday_day', 0);
				$month = $this->request->variable('bday_month', 0);
				$year = $this->request->variable('bday_year', 0);

				$s_birthday_day_options = '<option value="0"' . (($day == 0) ? ' selected="selected"' : '') . '>--</option>';
				for ($i = 1; $i < 32; $i++)
				{
					$selected = ($i == $day) ? ' selected="selected"' : '';
					$s_birthday_day_options .= "<option value=\"$i\"$selected>$i</option>";
				}
				$s_birthday_month_options = '<option value="0"' . (($month == 0) ? ' selected="selected"' : '') . '>--</option>';
				for ($i = 1; $i < 13; $i++)
				{
					$selected = ($i == $month) ? ' selected="selected"' : '';
					$s_birthday_month_options .= "<option value=\"$i\"$selected>$i</option>";
				}
				$s_birthday_year_options = '';
				$now = getdate();
				$s_birthday_year_options = '<option value="0"' . (($year == 0) ? ' selected="selected"' : '') . '>--</option>';
				for ($i = $now['year'] - 100; $i <= $now['year']; $i++)
				{
					$selected = ($i == $year) ? ' selected="selected"' : '';
					$s_birthday_year_options .= "<option value=\"$i\"$selected>$i</option>";
				}
				unset($now);

				$this->template->assign_vars(array(
					'S_BIRTHDAY_DAY_OPTIONS'        => $s_birthday_day_options,
					'S_BIRTHDAY_MONTH_OPTIONS'      => $s_birthday_month_options,
					'S_BIRTHDAY_YEAR_OPTIONS'       => $s_birthday_year_options,
					'S_BIRTHDAYS_ENABLED'           => true,
					'S_MIN_BIRTHDAY'				=> $this->config['birthday_min_age'],
					'IS_BIRTHDAY_REQUIRE'	=>	true,
				));

			}
		}
	}
	
	public function user_add_modify($event)
	{
		//let's test age
		$day = $this->request->variable('bday_day', 0);
		$month = $this->request->variable('bday_month', 0);
		$year = $this->request->variable('bday_year', 0);
		
		if ($day === 0 OR $month === 0 OR $year === 0)
		{
			trigger_error('BDAY_NO_DATE');
		}

		else 
		{
			$user_birthday = sprintf('%2d-%2d-%4d', trim($day), trim($month), trim($year));
		}
		
		$age = $this->age($user_birthday);
		if ($age < $this->config['birthday_min_age'])
		{
			trigger_error('BDAY_TO_YOUNG');
		}
		else
		{
			$input_array = $event['sql_ary'];
			$input_array['user_birthday'] = $user_birthday;
			$event['sql_ary'] = $input_array;
		}
	}
	
	public function var_display($event)
	{
		echo '<pre>';
		print_r($event);
		echo '</pre>';
	}
	
	public function age ($user_birthday)
	{
		list($bday_day, $bday_month, $bday_year) = array_map('intval', explode('-', $user_birthday));
		if ($bday_year)
		{
			$now = $this->user->create_datetime();
			$now = phpbb_gmgetdate($now->getTimestamp() + $now->getOffset());

			$diff = $now['mon'] - $bday_month;
			if ($diff == 0)
			{
				$diff = ($now['mday'] - $bday_day < 0) ? 1 : 0;
			}
			else
			{
				$diff = ($diff < 0) ? 1 : 0;
			}
			$age = max(0, (int) ($now['year'] - $bday_year - $diff));
		}
		return $age;
	}
	
}
