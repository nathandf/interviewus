<?php

namespace Model\Services;

class CartDestroyer
{
    private $cartRepo;
    private $productRepo;

    public function __construct(
        CartRepository $cartRepo,
        ProductRepository $productRepo
    ) {
        $this->cartRepo = $cartRepo;
        $this->productRepo = $productRepo;
    }

    public function destroy( $cart_id )
    {
        $this->productRepo->delete( [ "id" ], [ $cart_id ] );
        $this->cartRepo->delete( [ "id" ], [ $cart_id ] );
    }
}
