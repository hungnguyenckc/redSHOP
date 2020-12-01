<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use AcceptanceTester\UserManagerJoomla3Steps;
use Configuration\ConfigurationSteps;

/**
 * Class RatingManagerCest
 * @since 3.0.2
 */
class RatingManagerCest
{
	/**
	 * @var \Faker\Generator
	 * @since 3.0.2
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $country;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $categoryName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $productName;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $randomProductNumber;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $randomProductPrice;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $user;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $rating;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $ratingFrontEnd;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $configRating;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $statePublish;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $stateUnpublish;

	/**
	 * @var array
	 * @since 3.0.2
	 */
	protected $ratingFrontEndUserLogin;

	/**
	 * RatingManagerCest constructor.
	 * @since 3.0.2
	 */
	public function __construct()
	{
		$this->faker               = Faker\Factory::create();
		$this->categoryName        = $this->faker->bothify('Category Name ?###?');
		$this->productName         = $this->faker->bothify('Name Product ?###?');
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice  = 100;
		$this->statePublish        = "Publish";
		$this->stateUnpublish      = "unpublish";

		$this->user = array(
			"userName"     => $this->faker->bothify('User name ?####?'),
			"password"     => $this->faker->bothify('Password ?##?'),
			"email"        => $this->faker->email,
			"group"        => 'Registered',
			"shopperGroup" => 'Default Private',
			"firstName"    => $this->faker->bothify('First name ?##?'),
			"lastName"     => $this->faker->bothify('LastName ?####?'),
			"address"      => $this->faker->address,
			"zipcode"      => $this->faker->postcode,
			"city"         => 'Ho Chi Minh',
			"phone"        => $this->faker->phoneNumber,
			"country"      => 'Viet Nam'
		);

		$this->rating = array(
			"title"        => $this->faker->bothify('Title rating ?##?'),
			"numberRating" => 5,
			"comment"      => $this->faker->bothify('Comment rating ?##?'),
			"user"         => $this->user['firstName'],
			"product"      => $this->productName,
			"favoured"     => 'yes',
			"published"    => 'yes'
		);

		$this->ratingFrontEnd = array(
			"title"        => $this->faker->bothify('Title rating ?##?'),
			"numberRating" => 5,
			"comment"      => $this->faker->bothify('Comment rating ?##?'),
			"product"      => $this->productName,
			"email"        => $this->faker->email,
			"userName"     => $this->faker->name,
		);

		$this->configRating =
			array(
				"numberRating"  => 3,
				"loginRequired" => 'no',
			);

		$this->ratingFrontEndUserLogin = array(
			"title"        => $this->faker->bothify('Title rating ?##?'),
			"numberRating" => 5,
			"comment"      => $this->faker->bothify('Comment rating ?##?'),
			"product"      => $this->productName,
			"userName"     => $this->user['userName'],
			"password"     => $this->user['password'],
		);
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param CategoryManagerJoomla3Steps $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function createData(CategoryManagerJoomla3Steps $I, $scenario)
	{
		$I->wantTo('Create category in administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->wantTo('I want to add product inside the category');
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);

		$I = new UserManagerJoomla3Steps($scenario);
		$I->wantTo('I want to add user');
		$I->addUser($this->user['userName'], $this->user['password'], $this->user['email'], $this->user['group'], $this->user['shopperGroup'], $this->user['firstName'], $this->user['lastName'], 'save');
	}

	/**
	 * @param RatingManagerSteps $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function checkRating(RatingManagerSteps $I, $scenario)
	{
		$I->wantTo("I want to create new rating on backend");
		$I->createRating($this->rating);
		$I->wantTo("I want to delete rating on backend");
		$I->deleteRating($this->rating);

		$I = new ConfigurationSteps($scenario);
		$I->wantTo("I want to change config with rating");
		$I->configRating($this->configRating);
		$I = new RatingManagerSteps($scenario);
		$I->wantTo("I want to create new rating on front end");
		$I->createRatingOnFrontEnd($this->ratingFrontEnd, $this->categoryName, 'no');
		$I->wantTo("I want to change state rating to publish");
		$I->changeStateRating($this->ratingFrontEnd, $this->statePublish);
		$I->wantTo("I want to check rating display on front end");
		$I->checkDisplayRatingOnFrontEnd($this->ratingFrontEnd);
		$I->wantTo("I want to delete rating on backend");
		$I->deleteRating($this->ratingFrontEnd);

		$I = new ConfigurationSteps($scenario);
		$I->wantTo("I want to change config with rating");
		$this->configRating['loginRequired'] = 'yes';
		$I->configRating($this->configRating);
		$I = new RatingManagerSteps($scenario);
		$I->wantTo("I want to create new rating on front end");
		$I->createRatingOnFrontEnd($this->ratingFrontEndUserLogin, $this->categoryName, 'yes');
		$I->deleteRating($this->ratingFrontEndUserLogin);
	}

	/**
	 * @param ProductManagerJoomla3Steps $I
	 * @param $scenario
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function deleteAllData(ProductManagerJoomla3Steps $I, $scenario)
	{
		$I->wantTo("delete a product");
		$I->deleteProduct($this->productName);

		$I->wantTo("Delete a category");
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);

		$I->wantTo('Delete a user');
		$I = new UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->user['firstName']);
	}
}
