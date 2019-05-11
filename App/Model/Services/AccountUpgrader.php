<?php

namespace Model\Services;

class AccountUpgrader
{
    private $accountRepo;
    private $accountProvisioner;
    private $planRepo;

    public function __construct(
        AccountRepository $accountRepo,
        AccountProvisioner $accountProvisioner,
        PlanRepository $planRepo
    ) {
        $this->accountRepo = $accountRepo;
        $this->accountProvisioner = $accountProvisioner;
        $this->planRepo = $planRepo;
    }

    public function upgrade( $account_id, $plan_id, $provision = true )
    {
        if ( in_array( $plan_id, $this->planRepo->get( [ "id" ], [], "raw" ) ) ) {
            // Update the current plan of the account
            $this->accountRepo->update(
                [ "plan_id" => $plan_id ],
                [ "id" => $account_id ]
            );

            // Refill the account
            if ( $provision ) {
                $this->accountProvisioner->provision( $account_id );
            }

            return true;
        }

        return false;
    }
}
