<?php
/**
*
* Birthday Control
*
* @copyright (c) 2014 Stanislav Atanasov
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace anavaro\birthdaycontrol\tests\functional;

/**
* @group functional
*/
class birthdaycontrol_base extends \phpbb_functional_test_case
{
	static protected function setup_extensions()
	{
		return array('anavaro/birthdaycontrol');
	}

	public function setUp()
	{
		parent::setUp();
		$this->force_allow_birthday();
		$this->require_birthday();
	}

	/**
	* Allow birthday (just to be sure) 
	*/
	public function force_allow_birthday()
	{
		$this->get_db();

		$sql = "UPDATE phpbb_config
			SET config_value = 1
			WHERE config_name = 'allow_birthdays'";

		$this->db->sql_query($sql);

		$this->purge_cache();
	}

	/**
	* Require birthday (it's not required on install) 
	*/
	public function require_birthday()
	{
		$this->get_db();

		$sql = "UPDATE phpbb_config
			SET config_value = 1
			WHERE config_name = 'birthday_require'";

		$this->db->sql_query($sql);

		$this->purge_cache();
	}
	/**
	* Set age (default is 0)
	*/
	public function set_birthday_min_age($var)
	{
		$this->get_db();

		$sql = "UPDATE phpbb_config
			SET config_value = $var
			WHERE config_name = 'birthday_min_age'";

		$this->db->sql_query($sql);

		$this->purge_cache();
	}
	/**
	* Change show birthday
	*/
	public function set_show_birthday($userid, $state)
	{
		$sql = "UPDATE phpbb_profile_fields_data
			SET pf_bc_show_bday = $state
			WHERE user_id = $userid";
			
		$this->db->sql_query($sql);

		$this->purge_cache();
	}
	
	public function get_topic_id($topic_title)
	{
		$sql = 'SELECT topic_id
				FROM ' . TOPICS_TABLE . '
				WHERE topic_title = \'' . $topic_title . '\'';
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		return $row['topic_id'];
	}
	
	public function get_user_id($username)
	{
		$sql = 'SELECT user_id, username
				FROM ' . USERS_TABLE . '
				WHERE username_clean = \''.$this->db->sql_escape($username).'\'';
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		return $row['user_id'];
	}
}