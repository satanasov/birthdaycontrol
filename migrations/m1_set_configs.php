<?php
/**
*
* Birthday Control extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 Lucifer <http://www.anavaro.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace anavaro\birthdaycontrol\migrations;

/**
* Primary migration
*/

class m1_set_configs extends \phpbb\db\migration\migration
{
	public function update_data()
	{
		return array(
			array('config.add', array('birthday_require', 0)),
			array('config.add', array('birthday_min_age', 0)),
			array('config.add', array('birthday_show_profile', 1)),
			array('config.add', array('birthday_show_profile_uc', 1)),
			array('config.add', array('birthday_show_post', 0)),
			array('config.add', array('birthday_show_post_uc', 0)),
		);
	}

}