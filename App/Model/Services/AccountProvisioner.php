<?php

namespace Model\Services;

use Model\Entities\Account;
use Model\Entities\Interview;

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

    public function provision( $account )
    {
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

    public function refundInterview( Account $account, Interview $interview )
    {
        switch ( $interview->deployment_type_id ) {

            case 1:
                $this->accountRepo->update(
                    [ "sms_interviews" => ( $account->sms_interviews + 1 ) ],
                    [ "id" => $account->id ]
                );
                break;

            case 2:
                // -1 web interviews indicates unlimited. Adding 1 to this will
                // take the account off of unlimited status
                if ( $account->web_interviews != -1 ) {
                    $this->accountRepo->update(
                        [ "web_interviews" => ( $account->web_interviews + 1 ) ],
                        [ "id" => $account->id ]
                    );
                }
                break;
        }
    }
}
