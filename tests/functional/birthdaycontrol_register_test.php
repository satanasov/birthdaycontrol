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
class birthdaycontrol_register_test extends birthdaycontrol_base
{
	public function test_register_agreement_invalid_date()
	{
		$this->add_lang_ext('anavaro/birthdaycontrol', 'ucp_lang');
		$this->add_lang('common');
		
		$this->login();
		$this->admin_login();
		// Change option
		$crawler = self::request('GET', 'adm/index.php?i=acp_board&mode=settings&sid=' . $this->sid);
		$form = $crawler->selectButton('submit')->form();
		$form->setValues(array(
			'config[allow_birthdays]'	=> 1,
			'config[birthday_require]'	=> 1,
			'config[birthday_min_age]'	=> 18,
		));
		$crawler = self::submit($form);
		// Should be updated
		$this->assertContainsLang('CONFIG_UPDATED', $crawler->text());
		$this->logout();
		$this->logout();
		
		// test
		$this->add_lang('ucp');
		
		$crawler = self::request('GET', 'ucp.php?mode=register&sid='. $this->sid);
		
		$this->assertContainsLang('BIRTH_DATE', $crawler->filter('html')->text());
		
		$form = $crawler->selectButton($this->lang('AGREE'))->form();
		
		$form['bday_day'] = 2;
		$form['bday_month'] = 5;
		$form['bday_year'] = date('Y') - 13;
		
		$crawler = self::submit($form);
		
		$this->assertContains('You are not old enough to register to this board.', $crawler->text());

		$this->login();
		$this->admin_login();
		// Change option
		$crawler = self::request('GET', 'adm/index.php?i=acp_board&mode=settings&sid=' . $this->sid);
		$form = $crawler->selectButton('submit')->form();
		$form->setValues(array(
			'config[allow_birthdays]'	=> 0,
			'config[birthday_require]'	=> 0,
			'config[birthday_min_age]'	=> 0,
		));
		$crawler = self::submit($form);
		// Should be updated
		$this->assertContainsLang('CONFIG_UPDATED', $crawler->text());
		$this->logout();
		$this->logout();
	}/*
	public function test_register_agreement_no_date()
	{
		//firstly set all
		$this->force_allow_birthday();
		$this->require_birthday();
		$this->set_birthday_min_age(18);
		
		$this->add_lang_ext('anavaro/birthdaycontrol', 'ucp_lang');
		$this->add_lang('ucp');
		
		$crawler = self::request('GET', 'ucp.php?mode=register&sid='. $this->sid);
		
		$this->assertContainsLang('BIRTH_DATE', $crawler->text());
		
		$form = $crawler->selectButton($this->lang('AGREE'))->form();
		
		$crawler = self::submit($form);
		
		$this->assertContainsLang('BDAY_NO_DATE', $crawler->text());
	}*/
	public function test_register_agreement_valid_date()
	{
		$this->force_allow_birthday();
		$this->require_birthday();
		//firstly set all
		$this->set_birthday_min_age(18);
		
		$this->add_lang_ext('anavaro/birthdaycontrol', 'ucp_lang');
		$this->add_lang('ucp');
		$this->add_lang('common');
		
		$crawler = self::request('GET', 'ucp.php?mode=register&sid='. $this->sid);
		
		$this->assertContainsLang('BIRTH_DATE', $crawler->filter('html')->text());
		
		$form = $crawler->selectButton($this->lang('AGREE'))->form();
		
		$form['bday_day'] = 2;
		$form['bday_month'] = 5;
		$form['bday_year'] = date('Y') - 19;
		
		$crawler = self::submit($form);
		
		$this->assertContainsLang('USERNAME', $crawler->filter('html')->text());
		
		$this->force_allow_birthday(0);
		$this->require_birthday(0);
	}
}