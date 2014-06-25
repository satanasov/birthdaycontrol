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
* lets create some CPFs
*/

class cpf_age extends \phpbb\db\migration\profilefield_base_migration
{
	static public function depends_on()
	{
		return array(
			'anavaro\birthdaycontrol\migrations\m1_set_configs',
			//'anavaro\birthdaycontrol\migrations\m2_set_cpf',
			
		);
	}

	public function update_data()
	{
		return array(
			array('custom', array(array($this, 'create_custom_field'))),
		);
	}

	protected $profilefield_name = 'bc_age';

	protected $profilefield_database_type = array('UINT:2', NULL);

	protected $profilefield_data = array(
		'field_name'	=> 'bc_age',
		'field_type'	=> 'profilefields.type.int',
		'field_ident'	=> 'bc_age',
		'field_length'	=> '2',
		'field_minlen'	=> '0',
		'field_maxlen'	=> '15',
		'field_novalue'	=> '0',
		'field_default_value'	=> '',
		'field_validation'	=> '',
		'field_required'	=> 0,
		'field_show_novalue'	=> 0,
		'field_show_on_reg'	=> 0,
		'field_show_on_pm'	=> 1,
		'field_show_on_vt'	=> 1,
		'field_show_profile'	=> 0,
		'field_hide'	=> 0,
		'field_no_view'	=> 0,
		'field_active'	=> 1,
		'field_is_contact'	=> 0,
		'field_contact_desc'	=> '',
		'field_contact_url'	=> '',
	);
}