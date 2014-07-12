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
class birthdaycontrol_acp_test extends birthdaycontrol_base
{
	public function acp_pages_data()
	{
		return array(
			array('acp_board&mode=settings'), // Load the advanced forum settings ACP page
		);
	}
	/**
	* @dataProvider acp_pages_data
	*/
	public function test_acp_pages($mode)
	{
		$this->login();
		$this->admin_login();

		$this->add_lang_ext('anavar/birthdaycontrol', 'info_acp_birthdaycontrol');

		$crawler = self::request('GET', 'adm/index.php?i=' . $mode . '&sid=' . $this->sid);
		$this->assertContainsLang('BIRTHDAY_REQUIRE', $crawler->text());
		$this->assertContainsLang('BIRTHDAY_MIN_AGE', $crawler->text());
		$this->assertContainsLang('BIRTHDAY_SHOW_POST', $crawler->text());
	}
}