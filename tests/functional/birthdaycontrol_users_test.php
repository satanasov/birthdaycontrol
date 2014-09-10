<?php
/**
*
* Birthday Control test
*
* @copyright (c) 2014 Stanislav Atanasov
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace anavaro\birthdaycontrol\tests\functional;

/**
* @group functional
*/
class birthdaycontrol_users_test extends birthdaycontrol_base
{
	public function test_viewbday_in_topic()
	{
	
		$this->create_user('testuser1');
		$this->add_user_group('NEWLY_REGISTERED', array('testuser1'));
		$this->set_show_birthday($this->get_user_id('admin'), 2);
		
		$this->login('testuser1');
		$this->add_lang('ucp');
		$this->add_lang('common');
		
		$crawler = self::request('GET', 'viewtopic.php?t=1&sid=' . $this->sid);
		$this->assertContains('This is a test topic posted by the testing framework.', $crawler->filter('html')->text());
		
		$this->assertContainsLang('AGE', $crawler->filter('#bc_age')->text());
		
		$this->logout();
	}
	
	public function test_viewbday_in_profile()
	{
		
		$this->set_show_birthday($this->get_user_id('admin'), 2);
		
		$this->login('testuser1');
		$this->add_lang('ucp');
		$this->add_lang('common');
		
		$crawler = self::request('GET', 'memberlist.php?mode=viewprofile&u=' . $this->get_user_id('admin') . '&sid=' . $this->sid);
		$this->assertContainsLang('AGE', $crawler->filter('html')->text());
		
		$this->logout();
	}
}