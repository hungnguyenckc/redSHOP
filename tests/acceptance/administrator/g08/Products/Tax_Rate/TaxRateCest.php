<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\TaxRateSteps;
use AcceptanceTester\TaxGroupSteps;
use Codeception\Scenario;

/**
 * Class TaxRateCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class TaxRateCest
{
	/**
	 * @var  string
	 * @since 1.4.0
	 */
	public $faker;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $taxRateName = '';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public $taxRateName2 = '';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $taxRateNameEdit = '';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $taxGroupName = '';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $taxRateValue = '';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $countryName = '';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $stateName = '';

	/**
	 * @var integer
	 * @since 1.4.0
	 */
	public $taxRateValueNegative;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $taxRateValueString;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public $shopperGroup;

	/**
	 * TaxRateCest constructor.
	 * @since 1.4.0
	 */
	public function __construct()
	{
		$this->taxRateName          = 'Testing Tax Rates Groups' . rand(1, 199);
		$this->taxRateName2         = 'Testing Tax Rates Groups2' . rand(1, 199);
		$this->taxRateNameEdit      = $this->taxRateName . 'Edit';
		$this->taxGroupName         = 'Testing VAT Groups690';
		$this->taxRateValue         = rand(0, 1);
		$this->countryName          = 'United States';
		$this->stateName            = 'Alabama';
		$this->taxRateValueNegative = -1;
		$this->taxRateValueString   = 'Test';
		$this->shopperGroup         = 'All';
	}

	/**
	 * Create VAT Group with
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function createVATGroupSave(AcceptanceTester $client, $scenario)
	{
		$client->wantTo('VAT Groups - Save creation in Administrator');
		$client->doAdministratorLogin();
		$client = new TaxGroupSteps($scenario);
		$client->addVATGroupsSave($this->taxGroupName);

		$client->wantTo('Test TAX Rates Save creation in Administrator');
		$client = new TaxRateSteps($scenario);
		$client->addTAXRatesSave($this->taxRateName, $this->taxGroupName, $this->taxRateValue, $this->countryName, $this->stateName, $this->shopperGroup);
	}

	/**
	 * @param TaxRateSteps $I
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function checkButton(TaxRateSteps $I)
	{
		$I->doAdministratorLogin();
		$I->wantTo("Check button");
		$I->checkEdit($this->taxRateName);
		$I->addTAXRatesSave($this->taxRateName2, $this->taxGroupName, $this->taxRateValue, $this->countryName, $this->stateName, $this->shopperGroup);
		$I->checkResetButton($this->taxRateName, $this->taxRateName2);
		$I->deleteTAXRatesOK($this->taxRateName2);
		$I->checkSearchToolsEUCountry();
		$I->checkInButton($this->taxRateName);
	}

	/**
	 * Edit Tax Rates name try to clicks on name of TAX Rates
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 * @throws \Exception
	 * @depends createVATGroupSave
	 * @since 1.4.0
	 */
	public function editTAXRatesName(AcceptanceTester $client, $scenario)
	{
		$client->doAdministratorLogin();
		$client->wantTo('Test TAX Rates Save creation in Administrator');
		$client = new TaxRateSteps($scenario);
		$client->editTAXRatesName($this->taxRateName, $this->taxRateNameEdit);

		$client->wantTo('Test TAX Rates edit with Edit button Save creation in Administrator');
		$client->editTAXRatesName($this->taxRateNameEdit, $this->taxRateName);

		$client->wantTo('Edit TAX missing name in Administrator');
		$client->editTAXRatesMissingName($this->taxRateName);
	}

	/**
	 * Create tax rate with Save & Close
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addTAXRatesSaveClose(AcceptanceTester $client, $scenario)
	{
		$client->doAdministratorLogin();
		$client->wantTo('Test TAX Rates Save and Close creation in Administrator');
		$client = new TaxRateSteps($scenario);
		$client->addTAXRatesSaveClose($this->taxRateName, $this->taxGroupName, $this->taxRateValue, $this->countryName, $this->shopperGroup);
		$client->see(TaxRatePage::$namePage, TaxRatePage::$selectorPageTitle);
		$client->searchTAXRates($this->taxRateName);
	}

	/**
	 * Check cancel button
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function checkCancel(AcceptanceTester $client, $scenario)
	{
		$client->doAdministratorLogin();
		$client->wantTo('check Cancel creation in Administrator');
		$client = new TaxRateSteps($scenario);
		$client->checkCancel();
		$client->see(TaxRatePage::$namePage, TaxRatePage::$selectorPageTitle);

		$client->wantTo('Test delete button in Administrator');
		$client->deleteButton();
		$client->see(TaxRatePage::$namePage, TaxRatePage::$selectorPageTitle);

		$client->wantTo('Test delete button in Administrator');
		$client->deleteTAXRatesOK($this->taxRateName);
		$client->see(TaxRatePage::$namePage, TaxRatePage::$selectorPageTitle);
	}

	/**
	 * Create TAX Rates missing name
	 *
	 * @param   AcceptanceTester  $client    Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addTAXRatesMissingNameSave(AcceptanceTester $client, $scenario)
	{
		$client->doAdministratorLogin();
		$client->wantTo('Test TAX Rates Save missing name creation in Administrator');
		$client = new TaxRateSteps($scenario);
		$client->addTAXRatesMissingNameSave($this->taxGroupName, $this->taxRateValue, $this->countryName, $this->stateName);

		$client->wantTo('Test TAX Rates missing groups save creation in Administrator');
		$client->addTAXRatesMissingGroupsSave($this->taxRateName, $this->taxRateValue);

		$client->wantTo('Test TAX missing tax value Save creation in Administrator');
		$client->addTAXRatesMissingTaxValueSave($this->taxRateName, $this->taxGroupName);

		$client->wantTo('Test TAX amount less zero Save creation in Administrator');
		$client->addTAXRatesValueAmountLessZeroSave($this->taxRateName, $this->taxGroupName, $this->taxRateValueNegative);

		$client->wantTo('Test TAX Rates with amount is string  Save creation in Administrator');
		$client->addTAXRatesValueAmountString(
			$this->taxRateValueString, $this->taxGroupName, $this->taxRateValueString, $this->countryName, $this->shopperGroup, 'Save'
		);
	}

	/**
	 * @param TaxRateSteps $I
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function addTAXRatesMissingSaveCloseAndSaveNew(TaxRateSteps $I)
	{
		$I->doAdministratorLogin();
		$I->wantTo('Test TAX Rates missing name with Save & Close and Save & New');
		$I->addTAXRatesMissingNameSaveCloseAndSaveNew($this->taxGroupName, $this->taxRateValue, $this->countryName);

		$I->wantTo('Test TAX Rates missing groups with Save & Close and Save & New');
		$I->addTAXRatesMissingGroupsSaveCloseAndSaveNew($this->taxRateName, $this->taxRateValue);

		$I->wantTo("Test TAX Rates value amount string with Save Close");
		$I->addTAXRatesValueAmountString($this->taxRateName, $this->taxGroupName, $this->taxRateValue, $this->countryName, $this->shopperGroup);

		$I->wantTo("Test TAX Rates value amount string with Save New");
		$I->addTAXRatesValueAmountString($this->taxRateName, $this->taxGroupName, $this->taxRateValue, $this->countryName, $this->shopperGroup, 'SaveNew');

		$I->wantTo('Test TAX Rates with rates value amount less zero Save & Close and Save & New');
		$I->addTAXRatesValueAmountLessZeroSaveCloseAndSaveNew($this->taxRateName, $this->taxGroupName, $this->taxRateValueNegative, $this->shopperGroup);
	}
}
