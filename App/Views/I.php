<?php

namespace Views;

class I extends Page // Page inherits from View
{
	public function index( $errors = [] )
	{
		$this->showFacebookPixel();
		
		$this->assign( "interview", $this->model->interview );
        $this->assign( "organization", $this->model->organization );
        $this->assign( "error_messages", $errors );

        $this->setTemplate( "i/index.tpl" );
		$this->render();
	}

	public function start()
	{
		// If the interview is an sms interview, redirect the deployment
		// successful page
		if ( $this->model->interview->deployment_type_id == 1 ) {
			$this->redirect( "i/{$this->model->interview->token}/deployment-successful" );
		}
	}

	public function invalid()
	{
		$this->setTemplate( "i/invalid-interview.tpl" );
		$this->render();
	}

	public function deploymentSuccessful()
	{
		$this->showFacebookPixel( [ "ViewContent" ] );
		
		if ( isset( $this->model->interview ) && !is_null( $this->model->interview ) ) {
			if ( $this->model->interview->status == "pending" ) {
				$this->redirect( "i/{$this->model->interview->token}/" );

				return;
			}

			$this->setTemplate( "i/sms-interview-deployment-success.tpl" );
			$this->render();

			return;
		}

		$this->setTemplate( "i/invalid-interview.tpl" );
		$this->render();

		return;
	}

	public function interviewComplete()
	{
		$this->showFacebookPixel( [ "ViewContent" ] );
		
		$this->setTemplate( "i/interview-complete.tpl" );
		$this->render();
	}
}
