<?php
/**
*
* Advanced Board Announcements extension for the phpBB Forum Software package.
*
* @copyright (c) 2015 Lucifer <https://www.anavaro.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace anavaro\birthdaycontrol\tests\event;

use Facebook\WebDriver\Exception\ExpectedException;

/**
* @group event
*/
class main_event_test extends \phpbb_database_test_case
{
	/**
	* Define the extensions to be tested
	*
	* @return array vendor/name of extension(s) to test
	*/
	static protected function setup_extensions()
	{
		return array('anavaro/birthdaycontrol');
	}

	protected $db;

	/**
	* Get data set fixtures
	*/
	public function getDataSet()
	{
		return $this->createXMLDataSet(dirname(__FILE__) . '/fixtures/users.xml');
	}
	/**
	* Setup test environment
	*/
	public function setUp()
	{
		parent::setUp();

		global $phpbb_dispatcher, $phpbb_root_path, $phpEx;


		$this->config = new \phpbb\config\config(array(
		));
		$this->db = $this->new_dbal();
		$this->request = $this->getMock('\phpbb\request\request');

		$this->template = $this->getMockBuilder('\phpbb\template\template')
			->getMock();
		// Event dispatcher
/*
		$this->user = $this->getMock('\phpbb\user', array(), array(
			new \phpbb\language\language(new \phpbb\language\language_file_loader($phpbb_root_path, $phpEx)),
			'\phpbb\datetime'
		));
		$this->user->optionset('viewcensors', false);
		$this->user->style['style_path'] = 'prosilver';*/

		$this->user = new \phpbb\user(
			new \phpbb\language\language(new \phpbb\language\language_file_loader($phpbb_root_path, $phpEx)),
			'\phpbb\datetime'
		);
		$phpbb_dispatcher = new \phpbb_mock_event_dispatcher();
		$this->language = $this->getMockBuilder('\phpbb\language\language')
			->disableOriginalConstructor()
			->getMock();
		$this->language->method('lang')
			->will($this->returnArgument(0));
	}

	// Let's create listener
	protected function set_listener()
	{
		$this->listener = new \anavaro\birthdaycontrol\event\mainlistener(
			$this->config,
			$this->db,
			$this->request,
			$this->template,
			$this->user,
			$this->language
		);
	}
	/**
	* Test the event listener is subscribing events
	*/
	public function test_getSubscribedEvents()
	{
		$this->assertEquals(array(
			'core.user_add_modify_data',
			'core.user_setup',
			'core.memberlist_prepare_profile_data',
			'core.generate_profile_fields_template_data',
			'core.viewtopic_modify_post_row',
			'core.ucp_register_data_before',
			'core.ucp_profile_modify_profile_info',
		), array_keys(\anavaro\birthdaycontrol\event\mainlistener::getSubscribedEvents()));
	}
	public function default_configs_data()
	{
		return array(
			'all_true' => array(
				1, // Allow BDays
				1, // Req BDays
				0, // Registered
				0, // Bot
				1, // Expected
			),
			'ab_false' => array(
				0, // Allow BDays
				1, // Req BDays
				0, // Registered
				0, // Bot
				0, // Expected
			),
			'br_false' => array(
				1, // Allow BDays
				0, // Req BDays
				0, // Registered
				0, // Bot
				0, // Expected
			),
		);
	}
	/**
	* Test default_configs
	*
	* @dataProvider default_configs_data
	*/
	public function test_default_configs($allow, $require, $registered, $is_bot, $expected)
	{
		$this->config['allow_birthdays'] = $allow;
		$this->config['birthday_require'] = $require;
		$this->set_listener();
		$this->template->expects($this->exactly($expected))
			->method('assign_vars');
		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.user_setup', array($this->listener, 'default_configs'));
		$event_data = array(
			'user_data' => array(
				'is_bot'	=> $is_bot,
				'is_registered'	=> $registered,
			)
		);
		$event = new \phpbb\event\data(compact($event_data));
		$dispatcher->dispatch('core.user_setup', $event);
	}

	// TEST core.ucp_register_data_before

	/**
	 * @expectedException PHPUnit_Framework_Error_Notice
	 * @expectedExceptionMessage BDAY_NO_DATE
	 */
	public function test_register_validate_no_date()
	{
		$this->request->method('variable')
			->will($this->returnValue(0));
		$this->config['allow_birthdays'] = 1;
		$this->config['birthday_require'] = 1;
		$this->config['birthday_min_age'] = 18;
		$this->set_listener();
		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.ucp_register_data_before', array($this->listener, 'register_validate'));
		$event_data = array(
			'submit' => true,
		);
		$event = new \phpbb\event\data(compact($event_data));

		$dispatcher->dispatch('core.ucp_register_data_before', $event);
	}

	/**
	 * @expectedException PHPUnit_Framework_Error_Notice
	 * @expectedExceptionMessage BDAY_TO_YOUNG
	 */
	public function test_register_validate_young()
	{
		$this->config['allow_birthdays'] = 1;
		$this->config['birthday_require'] = 1;
		$this->config['birthday_min_age'] = 18;
		$this->set_listener();
		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.ucp_register_data_before', array($this->listener, 'register_validate'));
		$event_data = array(
			'submit' => true,
		);
		$event = new \phpbb\event\data(compact($event_data));

		$dispatcher->dispatch('core.ucp_register_data_before', $event);
	}

	public function test_register_validate_valid()
	{
		$this->request->method('variable')
			->will(
				$this->returnValue(10)
			);
		$this->config['allow_birthdays'] = 1;
		$this->config['birthday_require'] = 1;
		$this->config['birthday_min_age'] = 18;
		$this->set_listener();
		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.ucp_register_data_before', array($this->listener, 'register_validate'));
		$event_data = array(
			'submit' => true,
		);
		$event = new \phpbb\event\data(compact($event_data));

		$dispatcher->dispatch('core.ucp_register_data_before', $event);
	}

	public function test_register_not_rq()
	{
		$this->config['allow_birthdays'] = 1;
		$this->config['birthday_require'] = 0;
		$this->config['birthday_min_age'] = 18;
		$this->set_listener();
		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.ucp_register_data_before', array($this->listener, 'register_validate'));
		$event_data = array(
			'submit' => true,
		);
		$event = new \phpbb\event\data(compact($event_data));

		$dispatcher->dispatch('core.ucp_register_data_before', $event);
	}

	public function test_register_not_allowed()
	{
		$this->config['allow_birthdays'] = 0;
		$this->config['birthday_require'] = 1;
		$this->config['birthday_min_age'] = 18;
		$this->set_listener();
		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.ucp_register_data_before', array($this->listener, 'register_validate'));
		$event_data = array(
			'submit' => true,
		);
		$event = new \phpbb\event\data(compact($event_data));

		$dispatcher->dispatch('core.ucp_register_data_before', $event);
	}

	// TEST core.ucp_profile_modify_profile_info

	/**
	 * @expectedException PHPUnit_Framework_Error_Notice
	 * @expectedExceptionMessage BDAY_NO_DATE
	 */
	public function test_update_validate_no_date()
	{
		$this->request->method('variable')
			->will($this->returnValue(0));
		$this->config['allow_birthdays'] = 1;
		$this->config['birthday_require'] = 1;
		$this->config['birthday_min_age'] = 18;
		$this->set_listener();
		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.ucp_profile_modify_profile_info', array($this->listener, 'register_validate'));
		$event_data = array(
			'submit' => true,
		);
		$event = new \phpbb\event\data(compact($event_data));

		$dispatcher->dispatch('core.ucp_profile_modify_profile_info', $event);
	}

	/**
	 * @expectedException PHPUnit_Framework_Error_Notice
	 * @expectedExceptionMessage BDAY_TO_YOUNG
	 */
	public function test_update_validate_young()
	{
		$this->config['allow_birthdays'] = 1;
		$this->config['birthday_require'] = 1;
		$this->config['birthday_min_age'] = 18;
		$this->set_listener();
		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.ucp_profile_modify_profile_info', array($this->listener, 'register_validate'));
		$event_data = array(
			'submit' => true,
		);
		$event = new \phpbb\event\data(compact($event_data));

		$dispatcher->dispatch('core.ucp_profile_modify_profile_info', $event);
	}

	public function test_update_validate_valid()
	{
		$this->request->method('variable')
			->will(
				$this->returnValue(10)
			);
		$this->config['allow_birthdays'] = 1;
		$this->config['birthday_require'] = 1;
		$this->config['birthday_min_age'] = 18;
		$this->set_listener();
		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.ucp_profile_modify_profile_info', array($this->listener, 'register_validate'));
		$event_data = array(
			'submit' => true,
		);
		$event = new \phpbb\event\data(compact($event_data));

		$dispatcher->dispatch('core.ucp_profile_modify_profile_info', $event);
	}

	public function test_update_not_rq()
	{
		$this->config['allow_birthdays'] = 1;
		$this->config['birthday_require'] = 0;
		$this->config['birthday_min_age'] = 18;
		$this->set_listener();
		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.ucp_profile_modify_profile_info', array($this->listener, 'register_validate'));
		$event_data = array(
			'submit' => true,
		);
		$event = new \phpbb\event\data(compact($event_data));

		$dispatcher->dispatch('core.ucp_profile_modify_profile_info', $event);
	}

	public function test_update_not_allowed()
	{
		$this->config['allow_birthdays'] = 0;
		$this->config['birthday_require'] = 1;
		$this->config['birthday_min_age'] = 18;
		$this->set_listener();
		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.ucp_profile_modify_profile_info', array($this->listener, 'register_validate'));
		$event_data = array(
			'submit' => true,
		);
		$event = new \phpbb\event\data(compact($event_data));

		$dispatcher->dispatch('core.ucp_profile_modify_profile_info', $event);
	}
}
