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

abstract class m2_set_cpf extends \phpbb\db\migration\migration
{
	protected $profilefield_name;

	protected $profilefield_database_type;

	protected $profilefield_data;

	protected $user_column_name;

	/**
	* Assign migration file dependencies for this migration
	*
	* @return array Array of migration files
	* @static
	* @access public
	*/
	static public function depends_on()
	{
		return array('anavaro\birthdaycontrol\migrations\m1_set_configs');
	}
	
	public function effectively_installed()
	{
		return $this->db_tools->sql_column_exists($this->table_prefix . 'profile_fields_data', 'pf_' . $this->profilefield_name);
	}

	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'profile_fields_data'			=> array(
					'pf_' . $this->profilefield_name		=> $this->profilefield_database_type,
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				$this->table_prefix . 'profile_fields_data'			=> array(
					'pf_' . $this->profilefield_name,
				),
			),
		);
	}

	public function update_data()
	{
		return array(
			array('custom', array(array($this, 'create_custom_field'))),
			//array('custom', array(array($this, 'make_age'))),
		);
	}

	public function create_custom_field()
	{
		$sql = 'SELECT MAX(field_order) as max_field_order
			FROM ' . PROFILE_FIELDS_TABLE;
		$result = $this->db->sql_query($sql);
		$max_field_order = (int) $this->db->sql_fetchfield('max_field_order');
		$this->db->sql_freeresult($result);

		$sql_ary = array_merge($this->profilefield_data, array(
			'field_order'			=> $max_field_order + 1,
		));

		$sql = 'INSERT INTO ' . PROFILE_FIELDS_TABLE . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
		$this->db->sql_query($sql);
		$field_id = (int) $this->db->sql_nextid();

		$insert_buffer = new \phpbb\db\sql_insert_buffer($this->db, PROFILE_LANG_TABLE);

		$sql = 'SELECT lang_id
			FROM ' . LANG_TABLE;
		$result = $this->db->sql_query($sql);
		while ($lang_id = (int) $this->db->sql_fetchfield('lang_id'))
		{
			$insert_buffer->insert(array(
				'field_id'				=> $field_id,
				'lang_id'				=> $lang_id,
				'lang_name'				=> strtoupper(substr($this->profilefield_name, 6)),// Remove phpbb_ from field name
				'lang_explain'			=> '',
				'lang_default_value'	=> '',
			));
		}
		$this->db->sql_freeresult($result);

		$insert_buffer->flush();
	}
	
	public function make_age($start)
	{
		$insert_buffer = new \phpbb\db\sql_insert_buffer($this->db, $this->table_prefix . 'profile_fields_data');
		$limit = 250;
		$converted_users = 0;

		$sql = 'SELECT user_id, user_birthday
			FROM ' . $this->table_prefix . 'users
			WHERE ' . $this->user_column_name . " <> ''
			ORDER BY user_id";
		$result = $this->db->sql_query_limit($sql, $limit, $start);
		
		if ($this->profilefield_name == 'bc_age')
		{
			while ($row = $this->db->sql_fetchrow($result))
			{
				$converted_users++;

				$cp_data = array(
					'pf_' . $this->profilefield_name		=> $row[$this->user_column_name],
				);

				$sql = 'UPDATE ' . $this->table_prefix . 'profile_fields_data
					SET ' . $this->db->sql_build_array('UPDATE', $cp_data) . '
					WHERE user_id = ' . (int) $row['user_id'];
				$this->db->sql_query($sql);

				if (!$this->db->sql_affectedrows())
				{
					$cp_data['user_id'] = (int) $row['user_id'];
					$cp_data = array_merge($this->get_insert_sql_array(), $cp_data);
					$insert_buffer->insert($cp_data);
				}
			}
			$this->db->sql_freeresult($result);

			$insert_buffer->flush();

			if ($converted_users < $limit)
			{
				// No more users left, we are done...
				return;
			}

			return $start + $limit;
		}
	}

	protected function get_insert_sql_array()
	{
		static $profile_row;

		if ($profile_row === null)
		{
			global $phpbb_container;
			$manager = $phpbb_container->get('profilefields.manager');
			$profile_row = $manager->build_insert_sql_array(array());
		}

		return $profile_row;
	}
}