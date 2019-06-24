<?php

namespace Controllers\Webhooks\Braintree;

use \Core\Controller;

class Disputes extends Controller
{
    public function indexAction()
    {
        public function indexAction()
        {
            $input = $this->load( "input" );
            $inputValidator = $this->load( "input-validator" );
            $braintreeGatewayInitializer = $this->load( "braintree-gateway-initializer" );
            $accountRepo = $this->load( "account-repository" );
            $accountProvisioner = $this->load( "account-provisioner" );
            $emailBuilder = $this->load( "email-builder" );
            $mailer = $this->load( "mailer" );
            $domainObjectFactory = $this->load( "domain-object-factory" );
            $userRepo = $this->load( "user-repository" );

            if (
                $input->exists() &&
                $inputValidator->validate(
                    $input,
                    [
                        "bt_signature" => [
                            "required" => true
                        ],
                        "bt_payload" => [
                            "required" => true
                        ]
                    ],
                    "braintree_dispute"
                )
            ) {
                try {
                    // Connect to braintree API
                    $gateway = $braintreeGatewayInitializer->init();

                    // Parse the signature and payload
                    $webhookNotification = $gateway->webhookNotification()->parse(
                        $input->get( "bt_signature" ),
                        $input->get( "bt_payload" )
                    );

                    // Get the account associated with this notification
                    $account = $accountRepo->get(
                        [ "*" ],
                        [ "braintree_customer_id" => $webhookNotification->dispute->transaction->customerDetails->id ],
                        "single"
                    );

                    // Get the primary user of the account
                    $user = $userRepo->get( [ "*" ], [ "id" => $account->user_id ], "single" );

                    // Handle for the different notification types
                    switch ( $webhookNotification->kind ) {

                        case "dispute_opened":
                            // Log the dispute details
                            $logger->info( "Dispute Opened:\nInterviewUs Account ID - {$account->id}\nUser ID: {$user->id}\nUser Full Name: {$user->getFullName()}\nDispute ID - {$webhookNotification->dispute->id}\nAmount disputed: {$webhookNotification->dispute->amount}" );

                            // Send dispute opened email
                            $emailContext = $domainObjectFactory->build( "EmailContext" );

                            $emailContext->addProps([
                                "dispute_id" => $webhookNotification->dispute->id
                                "amount" => $webhookNotification->dispute->transaction->amount
                                "account_id" => $account->id,
                                "customer_id" => $webhookNotification->dispute->transaction->customerDetails->id,
                                "full_name" => $user->getFullName(),
                                "email" => $user->email,
                                "datetime" => $webhookNotification->timestamp->format( "D M j G:i:s T Y" )
                            ]);

                            $resp = $mailer->setTo( "interview.us.app@gmail.com", "InterviewUs" )
                                ->setFrom( "disputes@interviewus.net", "InterviewUs" )
                                ->setSubject( "Dispute Opened" )
                                ->setContent( $emailBuilder->build( "dipute-opened.html", $emailContext ) )
                                ->mail();
                            break;

                        case "dispute_won":
                            // Log dispute resolution result
                            $logger->info( "Dispute Won: Dispute ID - {$webhookNotification->dispute->id}" );

                            // Send dispute won email
                            $emailContext = $domainObjectFactory->build( "EmailContext" );

                            $emailContext->addProps([
                                "dispute_id" => $webhookNotification->dispute->id
                                "amount" => $webhookNotification->dispute->transaction->amount
                                "datetime" => $webhookNotification->timestamp->format( "D M j G:i:s T Y" )
                            ]);

                            $resp = $mailer->setTo( "interview.us.app@gmail.com", "InterviewUs" )
                                ->setFrom( "disputes@interviewus.net", "InterviewUs" )
                                ->setSubject( "Dispute Won" )
                                ->setContent( $emailBuilder->build( "dipute-won.html", $emailContext ) )
                                ->mail();
                            break;

                        case "dispute_lost":
                            // Log dispute resolution result
                            $logger->info( "Dispute Lost: Dispute ID - {$webhookNotification->dispute->id}" );

                            // Send dispute won email
                            $emailContext = $domainObjectFactory->build( "EmailContext" );

                            $emailContext->addProps([
                                "dispute_id" => $webhookNotification->dispute->id
                                "amount" => $webhookNotification->dispute->transaction->amount
                                "datetime" => $webhookNotification->timestamp->format( "D M j G:i:s T Y" )
                            ]);

                            $resp = $mailer->setTo( "interview.us.app@gmail.com", "InterviewUs" )
                                ->setFrom( "disputes@interviewus.net", "InterviewUs" )
                                ->setSubject( "Dispute Lost" )
                                ->setContent( $emailBuilder->build( "dipute-lost.html", $emailContext ) )
                                ->mail();
                            break;

                        default:
                            $logger->info( "Braintree Dispute: {$webhookNotification->kind}" );
                            break;
                    }

                } catch ( \Exception $e ) {
                    $logger->error( $e->getMessage() );
                }

                header( "HTTP/1.1 200 OK" );
            }
        }
    }
}
