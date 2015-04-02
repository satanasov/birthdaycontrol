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
	protected $bday_array;

	protected $viewtopic_show_age = false;

	static public function getSubscribedEvents()
	{
		return array(
			'core.user_add_modify_data'	=> 'user_add_modify',
			'core.user_setup'		=> 'default_configs',
			'core.memberlist_prepare_profile_data'	=> 'viewprofile',
			'core.generate_profile_fields_template_data'	=> 'show_age',
			'core.viewtopic_modify_post_row'	=>	'modify_post_row',
			'core.ucp_register_data_before'		=> 'register_validate',
			'core.ucp_profile_modify_profile_info' => 'change_validate',
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
	public function __construct(\phpbb\auth\auth $auth, \phpbb\cache\service $cache, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user, \phpbb\controller\helper $helper, $root_path, $php_ext, $table_prefix)
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
		if ($register and $this->config['birthday_require'])
		{
			$day = 0;
			$month = 0;
			$year = 0;
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

		$this->user->add_lang_ext('anavaro/birthdaycontrol', 'ucp_lang');
	}

	public function register_validate($event)
	{
		if ($this->config['birthday_require'])
		{
			$day = $this->request->variable('bday_day', 0);
			$month = $this->request->variable('bday_month', 0);
			$year = $this->request->variable('bday_year', 0);
			if ($day === 0 || $month === 0 || $year === 0)
			{
				trigger_error($this->user->lang['BDAY_NO_DATE']);
			}

			else
			{
				$user_birthday = sprintf('%2d-%2d-%4d', trim($day), trim($month), trim($year));
			}
			$age = $this->age($user_birthday);
			if ($age < $this->config['birthday_min_age'])
			{
				trigger_error(sprintf($this->user->lang['BDAY_TO_YOUNG'], $this->config['birthday_min_age']));
			}
		}
	}

	public function change_validate($event)
	{
		if ($this->config['birthday_require'])
		{
			$day = $this->request->variable('bday_day', 0);
			$month = $this->request->variable('bday_month', 0);
			$year = $this->request->variable('bday_year', 0);
			if ($day === 0 || $month === 0 || $year === 0)
			{
				trigger_error($this->user->lang['BDAY_NO_DATE']);
			}
			$user_birthday = sprintf('%2d-%2d-%4d', trim($day), trim($month), trim($year));
			$age = $this->age($user_birthday);
			if ($age < $this->config['birthday_min_age'])
			{
				trigger_error(sprintf($this->user->lang['BDAY_TO_YOUNG'], $this->config['birthday_min_age']));
			}
		}
	}
	public function user_add_modify($event)
	{
		//let's test age
		$this->user->add_lang_ext('anavaro/birthdaycontrol', 'ucp_lang');
		$day = $this->request->variable('bday_day', 0);
		$month = $this->request->variable('bday_month', 0);
		$year = $this->request->variable('bday_year', 0);

		if ($day === 0 || $month === 0 || $year === 0)
		{
			trigger_error($this->user->lang['BDAY_NO_DATE']);
		}

		else
		{
			$user_birthday = sprintf('%2d-%2d-%4d', trim($day), trim($month), trim($year));
		}

		$age = $this->age($user_birthday);
		if ($age < $this->config['birthday_min_age'])
		{
			trigger_error(sprintf($this->user->lang['BDAY_TO_YOUNG'], $this->config['birthday_min_age']));
		}
		else
		{
			$input_array = $event['sql_ary'];
			$input_array['user_birthday'] = $user_birthday;
			$event['sql_ary'] = $input_array;
		}
	}

	public function viewprofile($event)
	{
		$view_user = $event['data'];
		$template_data = $event['template_data'];

		//Let's get state of bc_show_bday
		$sql = 'SELECT pf_bc_show_bday FROM ' . PROFILE_FIELDS_DATA_TABLE . ' WHERE user_id = ' . $view_user['user_id'];
		$result = $this->db->sql_query($sql);

		$state = (int) $this->db->sql_fetchfield('pf_bc_show_bday');

		if ($state == 3)
		{
			$template_data['AGE'] = '';
			$event['template_data'] = $template_data;
		}

	}
	public function show_age($event)
	{
		$tpl_fields = $event['tpl_fields'];
		foreach ($tpl_fields['blockrow'] as $ID => $VAR)
		{
			if ($VAR['PROFILE_FIELD_IDENT'] == 'bc_show_bday')
			{
				if ($VAR['PROFILE_FIELD_VALUE_RAW'] == 2)
				{
					$this->viewtopic_show_age = true;
				}
				unset($tpl_fields['blockrow'][$ID]);
				unset($tpl_fields['row']['PROFILE_BC_SHOW_BDAY_IDENT']);
				unset($tpl_fields['row']['PROFILE_BC_SHOW_BDAY_VALUE']);
				unset($tpl_fields['row']['PROFILE_BC_SHOW_BDAY_VALUE_RAW']);
				unset($tpl_fields['row']['PROFILE_BC_SHOW_BDAY_CONTACT']);
				unset($tpl_fields['row']['PROFILE_BC_SHOW_BDAY_DESC']);
				unset($tpl_fields['row']['PROFILE_BC_SHOW_BDAY_TYPE']);
				unset($tpl_fields['row']['PROFILE_BC_SHOW_BDAY_NAME']);
				unset($tpl_fields['row']['PROFILE_BC_SHOW_BDAY_EXPLAIN']);
				unset($tpl_fields['row']['S_PROFILE_BC_SHOW_BDAY_CONTACT']);
				unset($tpl_fields['row']['S_PROFILE_BC_SHOW_BDAY']);
			}
		}
		$event['tpl_fields'] = $tpl_fields;
	}

	public function modify_post_row($event)
	{
		if ($this->viewtopic_show_age && $this->config['birthday_show_post'])
		{
			$post_row = $event['post_row'];
			$post_row['AGE'] = true;
			$event['post_row'] = $post_row;
			$this->viewtopic_show_age = false;
		}
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
