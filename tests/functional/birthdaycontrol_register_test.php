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
		$this->force_allow_birthday();
		$this->require_birthday();
		//firstly set all
		$this->set_birthday_min_age(18);
		
		$this->add_lang_ext('anavaro/birthdaycontrol', 'ucp_lang');
		$this->add_lang('ucp');
		
		$crawler = self::request('GET', 'ucp.php?mode=register&sid='. $this->sid);
		
		$this->assertContainsLang('BIRTH_DATE', $crawler->filter('html')->text());
		
		$form = $crawler->selectButton($this->lang('AGREE'))->form();
		
		$form['bday_day'] = 2;
		$form['bday_month'] = 5;
		$form['bday_year'] = date('Y') - 13;
		
		$crawler = self::submit($form);
		
		$this->assertContainsLang('BDAY_TO_YOUNG', $crawler->text());
		
		$this->force_allow_birthday(0);
		$this->require_birthday(0);
	}
	public function test_register_agreement_no_date()
	{
		//firstly set all
		$this->set_birthday_min_age(18);
		
		$this->add_lang_ext('anavaro/birthdaycontrol', 'ucp_lang');
		$this->add_lang('ucp');
		
		$crawler = self::request('GET', 'ucp.php?mode=register&sid='. $this->sid);
		
		$this->assertContainsLang('BIRTH_DATE', $crawler->text());
		
		$form = $crawler->selectButton($this->lang('AGREE'))->form();
		
		$crawler = self::submit($form);
		
		$this->assertContainsLang('BC_SHOW_BDAY', $crawler->text());
	}
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