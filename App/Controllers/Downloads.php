<?php

namespace Controllers;

use \Core\Controller;

class Downloads extends Controller
{
    public function interviewCSV()
    {
        $input = $this->load( "input" );
        $inputValidator = $this->load( "input-validator" );

        if (
            $input->exists() &&
            $inputValidator->validate(
                $input,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->session->getSession( "csrf-token" )
                    ],
                    "user_id" => [
                        "required" => true
                    ],
                    "account_id" => [
                        "required" => true
                    ],
                    "organization_id" => [
                        "required" => true
                    ],
                    "interview_id" => [
                        "required" => true
                    ]
                ],
                "interview_csv"
            )
        ) {
            $accountRepo = $this->load( "account-repository" );
            $userRepo = $this->load( "user-repository" );
            $organizationRepo = $this->load( "organization-repository" );
            $interviewRepo = $this->load( "interview-repository" );
            $interviewQuestionRepo = $this->load( "interview-question-repository" );
            $intervieweeAnswerRepo = $this->load( "interviewee-answer-repository" );
            $intervieweeRepo = $this->load( "interviewee-repository" );
            $positionRepo = $this->load( "position-repository" );
            $csvGenerator = $this->load( "csv-generator" );

            // Verify that the submitted information belongs to the user
            $user = $userRepo->get(
                [ "*" ],
                [
                    "id" => $input->get( "user_id" ),
                    "current_account_id" => $input->get( "account_id" ),
                    "current_organization_id" => $input->get( "organization_id" )
                ]
            );

            // Verfiy that the interview being retrieved belongs to the organization
            // with this organization_id
            //
            // If both the user and the interview belong to the same organiztion,
            // it is highly unlikely that they're trying to get their hands on
            // interview data that isn't already theirs
            if ( is_null( $user ) ) {
                throw new \Exception( "User either does not exist or the information provided to retrieve the user doesn't match any user's data" );
            }

            $interview = $interviewRepo->get(
                [ "*" ],
                [
                    "id" => $input->get( "interview_id" ),
                    "organization_id" => $input->get( "organization_id" )
                ],
                "single"
            );

            // Retrieve this interview's interview questions and their answers
            if ( is_null( $interview ) ) {
                throw new \Exception( "Interview either does not exist or the information provided to retrieve the interview doesn't match any interview's data" );
            }

            $interview->questions = $interviewQuestionRepo->get(
                [ "*" ],
                [ "interview_id" => $interview->id ]
            );

            // Retrieve the answers for these questions
            foreach ( $interview->questions as $question ) {
                $question->answer = $intervieweeAnswerRepo->get(
                    [ "*" ],
                    [ "interview_question_id" => $question->id ],
                    "single"
                );
            }

            // Get the interviewee for this interview
            $interviewee = $intervieweeRepo->get( [ "*" ], [ "id" => $interview->interviewee_id ], "single" );

            // Get the position for this interview
            $interview->position = $positionRepo->get( [ "*" ], [ "id" => $interview->position_id ], "single" );

            // Create entries in the csv
            $csvGenerator->addColumns( [ "question", "answer" ] );
            foreach ( $interview->questions as $question ) {
                if ( is_null(  $question->answer ) ) {
                    $csvGenerator->addEntry( [ $question->body, "Not Answered" ] );
                    continue;
                }

                $csvGenerator->addEntry( [ $question->body, $question->answer->body ] );
            }

            // Create file name from interviewee full name and position name.
            $filename = str_replace( " ", "", str_replace( "'", "", $interviewee->getFullName() . $interview->position->name ) );

            // Download the csv
            $csvGenerator->download( $filename );
        }
    }
}
