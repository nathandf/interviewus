<?php

namespace Model\Models;

use Core\Model;

class Pricing extends Model
{
	private function before()
	{
		$this->userAuth = $this->load( "user-authenticator" );
        $this->countryRepo = $this->load( "country-repository" );
        $this->accountRepo = $this->load( "account-repository" );
        $this->organizationRepo = $this->load( "organization-repository" );
        $this->cartRepo = $this->load( "cart-repository" );
		$this->productRepo = $this->load( "product-repository" );
        $this->planRepo = $this->load( "plan-repository" );
		$this->planDetailsRepo = $this->load( "plan-details-repository" );

		$this->countries = $this->countryRepo->get( [ "*" ] );

        $this->user = $this->userAuth->getAuthenticatedUser();
        $this->account = null;
        $this->organization = null;

		if ( !is_null( $this->user ) ) {
            $this->account = $this->accountRepo->get( [ "*" ], [ "id" => $this->user->current_account_id ], "single" );
            $this->organization = $this->organizationRepo->get( [ "*" ], [ "id" => $this->user->current_organization_id ], "single" );
        }
	}

	public function index()
	{
		$this->before();

		$this->plans = $this->planRepo->get( [ "*" ] );

        foreach ( $this->plans as $plan ) {
            $plan->details = $this->planDetailsRepo->get( [ "*" ], [ "plan_id" => $plan->id ], "single" );
        }
	}

	public function addToCart()
	{
		$this->before();

		// Get existing cart
		$cart = $this->cartRepo->get( [ "*" ], [ "account_id" => $this->account->id ], "single" );

		// If no cart exists, create a new one
		if ( is_null( $cart ) ) {
			$cart = $this->cartRepo->insert([
				"account_id" => $this->account->id
			]);
		}

		// Get all products for this cart
		$cart->products = $this->productRepo->get( [ "*" ], [ "cart_id" => $cart->id ] );

		// Update all products in cart
		foreach ( $cart->products as $product ) {
			$this->productRepo->delete( [ "id" ], [ $product->id ] );
		}

		// Add new product to the cart
		$product = $this->productRepo->insert([
			"cart_id" => $cart->id,
			"plan_id" => $this->request->post( "plan_id" ),
			"billing_frequency" => $this->request->post( "billing_frequency" )
		]);
	}
}
