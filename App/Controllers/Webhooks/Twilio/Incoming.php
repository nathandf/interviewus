<?php

namespace Controllers\Webhooks\Twilio;

use \Core\Controller;

class Incoming extends Controller
{
    public $organization;
    public $organization_phone = null;

    public function before()
    {
        $this->requireParam( "sid" );
        $organizationRepo = $this->load( "organization-repository" );
        $phoneRepo = $this->load( "phone-repository" );
        $this->twilioServiceDispatcher = $this->load( "twilio-service-dispatcher" );
        $twilioPhoneNumberRepo = $this->load( "twilio-phone-number-repository" );

        $this->twilioPhoneNumber = $twilioPhoneNumberRepo->get( [ "*" ], [ "sid" => $this->params[ "sid" ] ], "single" );

        // If no twilio phone number exists
        if ( is_null( $this->twilioPhoneNumber ) ) {
            return;
        }

        $this->organization = $organizationRepo->get( [ "*" ], [ "id" => $this->twilioPhoneNumber->organization_id ], "single" );
        if ( !is_null( $this->organization ) ) {
            $this->organization_phone = $phoneRepo->get( [ "*" ], [ "id" => $this->organization->phone_id ], "single" );
        }
    }

    public function smsAction()
    {
        $input = $this->load( "input" );
        $inputValidator = $this->load( "input-validator" );
        $intervieweeRepo = $this->load( "interviewee-repository" );
        $interviewRepo = $this->load( "interview-repository" );
        $interviewQuestionRepo = $this->load( "interview-question-repository" );
        $intervieweeAnswerRepo = $this->load( "interviewee-answer-repository" );
        $phoneRepo = $this->load( "phone-repository" );
        $interviewDispatcher = $this->load( "interview-dispatcher" );

        // Get the phone
        $phone = $phoneRepo->get( [ "*" ], [ "e164_phone_number" => $input->get( "from" ) ], "single" );

        $interviewee = $intervieweeRepo->get( [ "*" ], [ "phone_id" => $phone->id ], "single" );

        $interview = $interviewRepo->get(
            [ "*" ],
            [
                "interviewee_id" => $interviewee->id,
                "status" => "active",
                "deployment_type" => 1
            ],
            "single"
        );

        if ( !is_null( $interview ) ) {
            // Interview questions will be orderd in placement in ascending order
            $interview->questions = $interviewQuestionRepo->getAllByInterviewID(
                [ "*" ],
                [ "interview_id" => $interview->id ]
            );

            // Retrieve the interviewee's anwers to the interview questions.
            foreach ( $interview->questions as $question ) {
                $question->answer = $intervieweeAnswerRepo->get(
                    [ "*" ],
                    [ "interview_question_id" => $question->id ],
                    "single"
                );

                // The first interview question for which the answer comes up null
                // is the next answerable question in the interview.
                if ( is_null( $question->answer ) ) {
                    // Save the sms message body as the nterviewee answer
                    $intervieweeAnswerRepo->insert([
                        "interview_question_id" => $question->id,
                        "body" => $input->get( "message" )
                    ]);
                    // Once the interviewee's answer is saved. Break the loop and
                    // dispatch the interview.
                    break;
                }
            }

            // If there are more questions, they will be dispatched. If not, then
            // this interview's status will be updated to "complete"
            $interviewDispatcher->dispatch( $interview->id );
        }

        return;
    }

    public function voiceAction()
    {
        // Forward the call the organization's phone number
        if ( !is_null( $this->organization_phone ) ) {
            $this->twilioServiceDispatcher->forwardCall(
                $this->organization_phone->getE164FormattedPhoneNumber()
            );
        }
    }
}
