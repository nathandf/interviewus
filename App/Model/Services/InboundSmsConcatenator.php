<?php

namespace Model\Services;

use Model\Entities\InboundSms;

class InboundSmsConcatenator
{
	private $concatenatedSmsRepo;
	private $inboundSmsRepo;
	private $logger;

	public function __construct(
		ConcatenatedSmsRepository $concatenatedSmsRepo,
		InboundSmsRepository $inboundSmsRepo,
		$logger
	) {
		$this->concatenatedSmsRepo = $concatenatedSmsRepo;
		$this->inboundSmsRepo = $inboundSmsRepo;
		$this->logger = $logger;
	}

	public function concatenate( InboundSms $inboundSms )
	{
		// Get the concatenated sms related to the convesation of this inbound sms
		$concatenatedSms = $this->concatenatedSmsRepo->get(
			[ "*" ],
			[ "conversation_id" => $inboundSms->conversation_id ],
			"single"
		);


		// If concatenated sms doesn't exist, then create a new one. If it does,
		// concatenate the inbound sms body to the concatenated sms body
		if ( !is_null( $concatenatedSms ) ) {

			// Concatenate inbound sms to concatenated sms
			$concatenatedSms->body = $concatenatedSms->body . $inboundSms->body;

			// Update concatenated sms
			$this->concatenatedSmsRepo->update(
				[
					"body" => $concatenatedSms->body,
					"updated_at" => time()
				],
				[ "id" => $concatenatedSms->id ]
			);

			// Delete inbound sms
			$this->deleteInboundSms( $inboundSms );

			return $concatenatedSms;
		}

		// Create a new concatenated sms from the inbound sms
		$concatenatedSms = $this->newConcatenatedSms( $inboundSms );

		return $concatenatedSms;
	}

	public function newConcatenatedSms( InboundSms $inboundSms )
	{
		$now = time();
		$concatenatedSms = $this->concatenatedSmsRepo->insert([
			"conversation_id" => $inboundSms->conversation_id,
			"body" => $inboundSms->body,
			"created_at" => $now,
			"updated_at" => $now
		]);

		// Delete inbound sms
		$this->deleteInboundSms( $inboundSms );

		return $concatenatedSms;
	}

	private function deleteInboundSms( InboundSms $inboundSms )
	{
		$this->inboundSmsRepo->deleteEntities( $inboundSms );
	}
}
