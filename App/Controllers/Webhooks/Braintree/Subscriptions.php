<?php

namespace Controllers\Webhooks\Braintree;

use \Core\Controller;

class Subscriptions extends Controller
{
    public function indexAction()
    {
        
        $requestValidator = $this->load( "request-validator" );
        $braintreeGatewayInitializer = $this->load( "braintree-gateway-initializer" );
        $accountRepo = $this->load( "account-repository" );
        $accountProvisioner = $this->load( "account-provisioner" );
        $emailBuilder = $this->load( "email-builder" );
        $mailer = $this->load( "mailer" );
        $domainObjectFactory = $this->load( "domain-object-factory" );
        $userRepo = $this->load( "user-repository" );

        if (
            $this->request->is( "post" ) &&
            $requestValidator->validate(
                $this->request,
                [
                    "bt_signature" => [
                        "required" => true
                    ],
                    "bt_payload" => [
                        "required" => true
                    ]
                ],
                "braintree_subscription"
            )
        ) {
            try {
                // Connect to braintree API
                $gateway = $braintreeGatewayInitializer->init();

                // Parse the signature and payload
                $webhookNotification = $gateway->webhookNotification()->parse(
                    $this->request->post( "bt_signature" ),
                    $this->request->post( "bt_payload" )
                );

                // Get the account associated with this notification
                $account = $accountRepo->get(
                    [ "*" ],
                    [ "braintree_subscription_id" => $webhookNotification->subscription->id ],
                    "single"
                );

                // Get the primary user of the account
                $user = $userRepo->get( [ "*" ], [ "id" => $account->user_id ], "single" );

                // Handle for the different notification types
                switch ( $webhookNotification->kind ) {

                    case "subscription_cancelled":
                        // Notify account owner of subscription charge
                        $emailContext = $domainObjectFactory->build( "EmailContext" );
                        $emailContext->addProps([
                            "account_id" => $account->id,
                            "user_name" => $user->getFullName(),
                            "user_email" => $user->email,
                            "datetime" => $webhookNotification->timestamp->format( "D M j G:i:s T Y" )
                        ]);

                        $resp = $mailer->setTo( "interview.us.app@gmail.com", "InterviewUs" )
                            ->setFrom( "noreply@interviewus.net", "InterviewUs" )
                            ->setSubject( "Subscription Cancelled" )
                            ->setContent( $emailBuilder->build( "subscription-cancellation.html", $emailContext ) )
                            ->mail();
                        break;

                    case "subscription_charged_successfully":
                        // Provision account
                        $accountProvisioner->provision( $account );

                        // Notify account owner of subscription charge
                        $emailContext = $domainObjectFactory->build( "EmailContext" );
                        $emailContext->addProps([
                            "transaction_id" => $webhookNotification->subscription->transactions[ 0 ]->id,
                            "amount" => $webhookNotification->subscription->transactions[ 0 ]->amount,
                            "full_name" => $user->getFullName(),
                            "datetime" => $webhookNotification->timestamp->format( "D M j G:i:s T Y" )
                        ]);

                        $resp = $mailer->setTo( $user->email, $user->getFullName() )
                            ->setFrom( "noreply@interviewus.net", "InterviewUs" )
                            ->setSubject( "Account Renewed - Thanks for using InterviewUs!" )
                            ->setContent( $emailBuilder->build( "account-renewal.html", $emailContext ) )
                            ->mail();
                        break;

                    case "subscription_charged_unsuccessfully":
                        // Notify account owner of subscription charge
                        $emailContext = $domainObjectFactory->build( "EmailContext" );
                        $emailContext->addProps([
                            "first_name" => $user->getFirstName(),
                            "issue" => $webhookNotification->subscription->transactions[ 0 ]->processorResponseText,
                            "amount" => $webhookNotification->subscription->transactions[ 0 ]->amount,
                            "datetime" => $webhookNotification->timestamp->format( "D M j G:i:s T Y" )
                        ]);

                        $resp = $mailer->setTo( $user->email, $user->getFullName() )
                            ->setFrom( "customersupport@interviewus.net", "InterviewUs" )
                            ->setSubject( "Uh Oh, {$user->getFirstName()}! - We failed to renew your subscription - InterviewUs" )
                            ->setContent( $emailBuilder->build( "subscription-renewal-failure.html", $emailContext ) )
                            ->mail();
                        break;

                    case "subscription_expired":
                        break;

                    case "subscription_trial_ended":
                        break;

                    case "subscription_went_active":
                        break;

                    case "subscription_went_past_due":
                        break;

                    default:
                        $logger->info( "Braintree Webhook Notification: {$webhookNotification->kind}" );
                        break;
                }

            } catch ( \Exception $e ) {
                $logger->error( $e->getMessage() );
            }

            header( "HTTP/1.1 200 OK" );
        }
    }
}
