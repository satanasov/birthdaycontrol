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
}