<?php
/**
 * @package     RedSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\ConfigurationSteps as ConfigurationSteps;

/**
 * Class OnePageCheckoutMissingDataCest
 * @since 2.2.0
 */
class OnePageCheckoutMissingDataCest
{
	public function __construct()
	{
		$this->faker                  = Faker\Factory::create();
		$this->ProductName            =  $this->faker->bothify('ProductName ?####?');
		$this->CategoryName           =  $this->faker->bothify('CategoryName ?####?');
		$this->ManufactureName        = $this->faker->bothify('ManufactureName ?#####');
		$this->MassDiscountAmoutTotal = 90;
		$this->MassDiscountPercent    = 0.3;
		$this->minimumPerProduct      = 1;
		$this->minimumQuantity        = 1;
		$this->maximumQuantity        = $this->faker->numberBetween(100, 1000);
		$this->discountStart          = "12-12-2016";
		$this->discountEnd            = "23-05-2017";
		$this->randomProductNumber    = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice     = 100;

		$this->business              = "business";
		$this->private               = "private";
		$this->createAccount         = "createAccount";

		$this->userName        = $this->faker->bothify('OnePageCest ?####?');
		$this->password        = $this->faker->bothify('Password ?##?');
		$this->email           = $this->faker->email;
		$this->shopperGroup    = 'Default Private';
		$this->group           = 'Registered';
		$this->firstName       = $this->faker->bothify('OnePageCest FN ?#####?');
		$this->updateFirstName = 'Updating ' . $this->firstName;
		$this->lastName        = 'Last';
		$this->address         = '14 Phan Ton';
		$this->zipcode         = 7000;
		$this->city            = 'Ho Chi Minh';
		$this->phone           = 010101010;

		$this->customerInformation = array(
			"email"      => "test@test" . rand() . ".com",
			"firstName"  => $this->faker->bothify('firstNameCustomer ?####?'),
			"lastName"   => $this->faker->bothify('lastNameCustomer ?####?'),
			"address"    => "Some Place in the World",
			"postalCode" => "23456",
			"city"       => "Bangalore",
			"country"    => "India",
			"state"      => "Karnataka",
			"phone"      => "8787878787"
		);

		$this->customerBussinesInformation = array(
			"email"          => "test@test" . rand() . ".com",
			"companyName"    => "CompanyName",
			"businessNumber" => 1231312,
			"firstName"      => $this->faker->bothify('firstName ?####?'),
			"lastName"       => $this->faker->bothify('lastName ?####?'),
			"address"        => "Some Place in the World",
			"postalCode"     => "23456",
			"city"           => "Bangalore",
			"country"        => "India",
			"state"          => "Karnataka",
			"phone"          => "8787878787",
			"eanNumber"      => 1212331331231,
		);

		//configuration enable one page checkout
		$this->addcart          = 'product';
		$this->allowPreOrder    = 'yes';
		$this->cartTimeOut      = $this->faker->numberBetween(100, 10000);
		$this->enabldAjax       = 'no';
		$this->defaultCart      = null;
		$this->buttonCartLead   = 'Back to current view';
		$this->onePage          = 'yes';
		$this->showShippingCart = 'no';
		$this->attributeImage   = 'no';
		$this->quantityChange   = 'no';
		$this->quantityInCart   = 0;
		$this->minimunOrder     = 0;
		$this->enableQuation    = 'no';
		$this->onePageNo        = 'no';
		$this->onePageYes       = 'yes';

		$this->buttonCartLeadEdit = 'Back to current view';
		$this->shippingWithVat    = "DKK 0,00";

		$this->shippingMethod = 'redSHOP - Standard Shipping';
		$this->shipping       = array(
			'shippingName' => $this->faker->bothify('TestingShippingRate ?##?'),
			'shippingRate' => 10
		);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 *
	 * @since 2.2.0
	 * @throws Exception
	 */
	public function onePageCheckoutMissing(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();
		$I->enablePlugin('PayPal');

		$I->wantTo('setup up one page checkout at admin');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->addcart, $this->allowPreOrder, $this->enableQuation, $this->cartTimeOut, $this->enabldAjax, $this->defaultCart, $this->buttonCartLead,
			$this->onePageYes, $this->showShippingCart, $this->attributeImage, $this->quantityChange, $this->quantityInCart, $this->minimunOrder);

		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->CategoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I Want to add product inside the category');
		$I->createProductSaveClose($this->ProductName, $this->CategoryName, $this->randomProductNumber, $this->randomProductPrice);

		$I = new CheckoutOnFrontEnd($scenario);
		$I->wantToTest('Check out with missing user');
		$I->onePageCheckoutMissing($this->ProductName, $this->CategoryName,$this->customerInformation, 'user', $this->createAccount);
		$I->onePageCheckoutMissing($this->ProductName, $this->CategoryName,$this->customerInformation, 'user', $this->private);
		$I->onePageCheckoutMissing($this->ProductName, $this->CategoryName,$this->customerBussinesInformation,'user', $this->business);

		$I->wantToTest('Check out with missing click accept Terms');
		$I->onePageCheckoutMissing($this->ProductName, $this->CategoryName,$this->customerInformation, 'acceptTerms', $this->private);
		$I->onePageCheckoutMissing($this->ProductName, $this->CategoryName,$this->customerBussinesInformation, 'acceptTerms', $this->business);

		$I->wantToTest('Check out with missing click payment');
		$I->onePageCheckoutMissing($this->ProductName, $this->CategoryName,$this->customerInformation, 'payment', $this->private);
		$I->onePageCheckoutMissing($this->ProductName, $this->CategoryName,$this->customerBussinesInformation, 'payment', $this->business);

		$I->wantToTest('Check out with wrong address email');
		$this->customerInformation['email'] = "test";
		$I->onePageCheckoutMissing($this->ProductName, $this->CategoryName,$this->customerInformation, 'wrongEmail', $this->private);
		$this->customerBussinesInformation['email'] = "test";
		$I->onePageCheckoutMissing($this->ProductName, $this->CategoryName,$this->customerBussinesInformation, 'wrongEmail', $this->business);

		$I->wantToTest('Check out with wrong phone number');
		$this->customerInformation['phone'] = "test";
		$I->onePageCheckoutMissing( $this->ProductName, $this->CategoryName, $this->customerInformation, 'wrongPhone', $this->private);
		$this->customerBussinesInformation['phone'] = "test";
		$I->onePageCheckoutMissing( $this->ProductName, $this->CategoryName, $this->customerBussinesInformation, 'wrongPhone', $this->business);

		$I->wantToTest('Check out with wrong EAN Number');
		$this->customerBussinesInformation['eanNumber'] = "test";
		$I->onePageCheckoutMissing( $this->ProductName, $this->CategoryName, $this->customerBussinesInformation, 'wrongEAN', $this->business);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 *
	 * @since 2.2.0
	 * @throws Exception
	 */
	public function clearUpDatabase(AcceptanceTester $I, $scenario)
	{
		$I->doAdministratorLogin();
		$I->wantTo('setup up one page checkout is no at admin');
		$I = new ConfigurationSteps($scenario);
		$I->cartSetting($this->addcart, $this->allowPreOrder, $this->enableQuation, $this->cartTimeOut, $this->enabldAjax, $this->defaultCart, $this->buttonCartLeadEdit, $this->onePageNo, $this->showShippingCart, $this->attributeImage, $this->quantityChange, $this->quantityInCart, $this->minimunOrder);

		$I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Product  in Administrator');
		$I->deleteProduct($this->ProductName);

		$I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Delete Category in Administrator');
		$I->deleteCategory($this->CategoryName);
	}
}
