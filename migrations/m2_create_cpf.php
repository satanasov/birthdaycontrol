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

class m2_create_cpf extends \phpbb\db\migration\profilefield_base_migration
{
	static public function depends_on()
	{
		return array(
			'\anavaro\birthdaycontrol\migrations\m1_set_configs',
		);
	}

	public function update_data()
	{
		return array(
			array('custom', array(array($this, 'create_custom_field'))),
			array('custom', array(array($this, 'create_language_entries'))),
		);
	}

	protected $profilefield_name = 'bc_show_bday';

	protected $profilefield_database_type = array('UINT:2', 2);

	protected $profilefield_data = array(
		'field_name'	=> 'bc_show_bday',
		'field_type'	=> 'profilefields.type.bool',
		'field_ident'	=> 'bc_show_bday',
		'field_length'	=> 1,
		//'field_minlen'	=> 0,
		//'field_maxlen'	=> 0,
		//'field_novalue'	=> 1,
		'field_default_value'	=> 1,
		//'field_validation'	=> '',
		'field_required'	=> 0,
		'field_show_novalue'	=> 0,
		'field_show_on_reg'	=> 1,
		'field_show_on_pm'	=> 0,
		'field_show_on_vt'	=> 1,
		'field_show_profile'	=> 1,
		'field_show_on_ml'	=> 0,
		'field_hide'	=> 0,
		'field_no_view'	=> 0,
		'field_active'	=> 1,
		'field_is_contact'	=> 0,
		'field_contact_desc'	=> '',
		'field_contact_url'	=> '',
	);

	protected $profilefield_language_data = array(
		array(
			'option_id'	=> 0,
			'field_type'	=> 'profilefields.type.bool',
			'lang_value'	=> 'Yes',
		),
		array(
			'option_id'	=> 1,
			'field_type'	=> 'profilefields.type.bool',
			'lang_value'	=> 'No',
		),
	);

}
