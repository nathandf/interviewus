<?php

namespace Model\Services;

class AccountProvisioner
{
    private $accountRepo;
    private $planRepo;
    private $planDetailsRepo;

    public function __construct(
        AccountRepository $accountRepo,
        PlanRepository $planRepo,
        PlanDetailsRepository $planDetailsRepo
    ) {
        $this->accountRepo = $accountRepo;
        $this->planRepo = $planRepo;
        $this->planDetailsRepo = $planDetailsRepo;
    }

    public function provision( $account_id )
    {
        $account = $this->accountRepo->get( [ "*" ], [ "id" => $account_id ], "single" );
        $account->plan = $this->planRepo->get( [ "*" ], [ "id" => $account->plan_id ], "single" );
        $account->plan->details = $this->planDetailsRepo->get( [ "*" ], [ "plan_id" => $account->plan->id ], "single" );

        // Update account details based on the current plan
        $this->accountRepo->update(
            [
                "sms_interviews" => $account->plan->details->sms_interviews,
                "web_interviews" => $account->plan->details->web_interviews,
                "users" => $account->plan->details->users
            ],
            [ "id" => $account->id ]
        );
    }
}
