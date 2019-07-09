<?php
/*
* Services and dependency injection on instanciation
*/

// Core
$container->register( "config", function() {
	$config = new Conf\Config;
	return $config;
} );

$container->register( "error", function() use ( $container ) {
	$error = new Core\Error( $container );
	return $error;
} );

$container->register( "session", function() {
    $session = new Core\Session;
    return $session;
} );

$container->register( "request", function() use ( $container ) {
	$request = new Core\Request;
	return $request;
} );

$container->register( "request-validator", function() {
    $obj = new \Core\RequestValidator;
    return $obj;
} );

$container->register( "router", function() use ( $container ) {
	$router = new Core\Router( $container->getService( "config" ) );
	return $router;
} );

$container->register( "view", function() use( $container ) {
	$view = new Core\View( $container );
	return $view;
} );

$container->register( "smarty", function() {
	$smarty = new Smarty();
	return $smarty;
} );

$container->register( "templating-engine", function() use ( $container ) {
	$templatingEngine = $container->getService( "smarty" );
	return $templatingEngine;
} );

$container->register( "logger", function() use ( $container ) {
	$Config = $container->getService( "config" );
	$logsDir = $Config->configs[ "logs_directory" ];
	$logger = new Katzgrau\KLogger\Logger( $logsDir );
	return $logger;
} );

// Database access object

$container->register( "pdo", function() use ( $container ) {
	$conf = $container->getService( "config" );
	$pdo = new \PDO(
		"mysql:host={$conf->configs[ "db" ][ "{$conf->getEnv()}" ][ "host" ]}; dbname={$conf->configs[ "db" ][ "{$conf->getEnv()}" ][ "dbname" ]};",
		$conf->configs[ "db" ][ "{$conf->getEnv()}" ][ "user" ],
		$conf->configs[ "db" ][ "{$conf->getEnv()}" ][ "password" ]
	);
	$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	return $pdo;
} );

$container->register( "dao", function() use ( $container ) {
	$databaseAccessObject = $container->getService( "pdo" );
	return $databaseAccessObject;
} );

// Services

$container->register( "entity-factory", function() {
	$factory = new \Model\Services\EntityFactory;
	return $factory;
} );

$container->register( "domain-object-factory", function() {
	$factory = new \Model\Services\DomainObjectFactory;
	return $factory;
} );

$container->register( "quick-boi", function() use ( $container ) {
	$service = new \Model\Services\QuickBoi(
		$container->getService( "dao" )
	);
	return $service;
} );

$container->register( "industry-repository", function() use ( $container ) {
	$repo = new \Model\Services\IndustryRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "braintree-client-token-generator", function () use ( $container ) {
	$service =  new \Model\Services\BraintreeAPI\ClientTokenGenerator(
		$container->getService( "braintree-gateway-initializer" )
	);
	return $service;
} );

$container->register( "braintree-gateway-initializer", function () use ( $container ) {
	$service =  new \Model\Services\BraintreeAPI\GatewayInitializer(
		$container->getService( "config" )
	);
	return $service;
} );

$container->register( "braintree-customer-repository", function () use ( $container ) {
	$service =  new \Model\Services\BraintreeAPI\CustomerRepository(
		$container->getService( "braintree-gateway-initializer" )
	);
	return $service;
} );

$container->register( "braintree-payment-method-repository", function () use ( $container ) {
	$service =  new \Model\Services\BraintreeAPI\PaymentMethodRepository(
		$container->getService( "braintree-gateway-initializer" )
	);
	return $service;
} );

$container->register( "braintree-subscription-repository", function () use ( $container ) {
	$service =  new \Model\Services\BraintreeAPI\SubscriptionRepository(
		$container->getService( "braintree-gateway-initializer" )
	);
	return $service;
} );

$container->register( "braintree-api-manager", function () use ( $container ) {
	$service =  new \Model\Services\BraintreeAPIManager(
		$container->getService( "braintree-gateway-initializer" )
	);
	return $service;
} );

$container->register( "account-provisioner", function() use ( $container ) {
	$repo = new \Model\Services\AccountProvisioner(
		$container->getService( "account-repository" ),
		$container->getService( "plan-repository" ),
		$container->getService( "plan-details-repository" )
	);
	return $repo;
} );

$container->register( "account-upgrader", function() use ( $container ) {
	$repo = new \Model\Services\AccountUpgrader(
		$container->getService( "account-repository" ),
		$container->getService( "account-provisioner" ),
		$container->getService( "plan-repository" )
	);
	return $repo;
} );

$container->register( "account-repository", function() use ( $container ) {
	$repo = new \Model\Services\AccountRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "account-user-repository", function() use ( $container ) {
	$repo = new \Model\Services\AccountUserRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "address-repository", function() use ( $container ) {
	$repo = new \Model\Services\AddressRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "cart-destroyer", function() use ( $container ) {
	$repo = new \Model\Services\CartDestroyer(
		$container->getService( "cart-repository" ),
		$container->getService( "product-repository" )
	);
	return $repo;
} );

$container->register( "cart-repository", function() use ( $container ) {
	$repo = new \Model\Services\CartRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "concatenated-sms-repository", function() use ( $container ) {
	$repo = new \Model\Services\ConcatenatedSmsRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "conversation-repository", function() use ( $container ) {
	$repo = new \Model\Services\ConversationRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "conversation-provisioner", function() use ( $container ) {
	$service = new \Model\Services\ConversationProvisioner(
		$container->getService( "conversation-repository" ),
		$container->getService( "twilio-phone-number-repository" )
	);
	return $service;
} );

$container->register( "country-repository", function() use ( $container ) {
	$repo = new \Model\Services\CountryRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "deployment-type-repository", function() use ( $container ) {
	$repo = new \Model\Services\DeploymentTypeRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "facebook-pixel-repository", function() use ( $container ) {
	$repo = new \Model\Services\FacebookPixelRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "file-repository", function() use ( $container ) {
	$repo = new \Model\Services\FileRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "image-repository", function() use ( $container ) {
	$repo = new \Model\Services\ImageRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "inbound-sms-repository", function() use ( $container ) {
	$repo = new \Model\Services\InboundSmsRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "industry-repository", function() use ( $container ) {
	$repo = new \Model\Services\IndustryRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "interview-repository", function() use ( $container ) {
	$repo = new \Model\Services\InterviewRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "interview-builder", function() use ( $container ) {
	$repo = new \Model\Services\InterviewBuilder(
		$container->getService( "account-repository" ),
		$container->getService( "interview-repository" ),
		$container->getService( "question-repository" ),
		$container->getService( "interview-question-repository" ),
		$container->getService( "interviewee-repository" )
	);
	return $repo;
} );

$container->register( "interview-dispatcher", function() use ( $container ) {
	$repo = new \Model\Services\InterviewDispatcher(
		$container->getService( "interview-repository" ),
		$container->getService( "interview-question-repository" ),
		$container->getService( "interviewee-answer-repository" ),
		$container->getService( "interviewee-repository" ),
		$container->getService( "conversation-repository" ),
		$container->getService( "twilio-phone-number-repository" ),
		$container->getService( "phone-repository" ),
		$container->getService( "sms-messager" )
	);
	return $repo;
} );

$container->register( "interviewee-repository", function() use ( $container ) {
	$repo = new \Model\Services\IntervieweeRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "interviewee-answer-repository", function() use ( $container ) {
	$repo = new \Model\Services\IntervieweeAnswerRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "interview-question-repository", function() use ( $container ) {
	$repo = new \Model\Services\InterviewQuestionRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "interview-template-repository", function() use ( $container ) {
	$repo = new \Model\Services\InterviewTemplateRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "organization-repository", function() use ( $container ) {
	$repo = new \Model\Services\OrganizationRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "organization-user-repository", function() use ( $container ) {
	$repo = new \Model\Services\OrganizationUserRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "payment-method-repository", function() use ( $container ) {
	$repo = new \Model\Services\PaymentMethodRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "phone-repository", function() use ( $container ) {
	$repo = new \Model\Services\PhoneRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "plan-details-repository", function() use ( $container ) {
	$repo = new \Model\Services\PlanDetailsRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "plan-repository", function() use ( $container ) {
	$repo = new \Model\Services\PlanRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "position-repository", function() use ( $container ) {
	$repo = new \Model\Services\PositionRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "product-repository", function() use ( $container ) {
	$repo = new \Model\Services\ProductRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "question-repository", function() use ( $container ) {
	$repo = new \Model\Services\QuestionRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "question-choice-repository", function() use ( $container ) {
	$repo = new \Model\Services\QuestionChoiceRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "question-tag-repository", function() use ( $container ) {
	$repo = new \Model\Services\QuestionTagRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "question-type-repository", function() use ( $container ) {
	$repo = new \Model\Services\QuestionTypeRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "inbound-sms-concatenator", function() use ( $container ) {
	$service = new \Model\Services\InboundSmsConcatenator(
		$container->getService( "concatenated-sms-repository" ),
		$container->getService( "inbound-sms-repository" ),
		$container->getService( "logger" )
	);

	return $service;
} );

$container->register( "sendgrid-client-initializer", function() use ( $container ) {
	$service = new \Model\Services\SendGridAPI\ClientInitializer(
		$container->getService( "config" )
	);
	return $service;
} );

$container->register( "sendgrid-mailer", function() use ( $container ) {
	$service = new \Model\Services\SendGridAPI\Mailer(
		$container->getService( "sendgrid-client-initializer" ),
		$container->getService( "unsubscribe-repository" )
	);
	return $service;
} );

$container->register( "mailer", function() use ( $container ) {
	$mailerService = $container->getService( "sendgrid-mailer" );
	return $mailerService;
} );

$container->register( "tag-repository", function() use ( $container ) {
	$repo = new \Model\Services\TagRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "video-repository", function() use ( $container ) {
	$repo = new \Model\Services\VideoRepository(
	    $container->getService( "dao" ),
	    $container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "twilio-phone-number-repository", function() use ( $container ) {
	$repo = new \Model\Services\TwilioPhoneNumberRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "twilio-client-initializer", function() use ( $container ) {
	$obj = new \Model\Services\TwilioAPI\ClientInitializer(
	    $container->getService( "config" )
	);
	return $obj;
} );

$container->register( "twilio-phone-number-buyer", function() use ( $container ) {
	$obj = new \Model\Services\TwilioAPI\PhoneNumberBuyer(
	    $container->getService( "twilio-client-initializer" ),
		$container->getService( "config" )
	);
	return $obj;
} );

$container->register( "twilio-service-dispatcher", function() use ( $container ) {
	$obj = new \Model\Services\TwilioAPI\ServiceDispatcher(
	    $container->getService( "twilio-client-initializer" )
	);
	return $obj;
} );

$container->register( "twilio-sms-messager", function() use ( $container ) {
	$obj = new \Model\Services\TwilioAPI\SMSMessager(
	    $container->getService( "twilio-client-initializer" )
	);
	return $obj;
} );

$container->register( "sms-messager", function() use ( $container ) {
	$smsMessager = $container->getService( "twilio-sms-messager" );
	return $smsMessager;
} );

$container->register( "unsubscribe-repository", function() use ( $container ) {
	$repo = new \Model\Services\UnsubscribeRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "user-authenticator", function() use ( $container ) {
	$repo = new \Model\Services\UserAuthenticator(
		$container->getService( "user-repository" ),
		$container->getService( "session" )
	);
	return $repo;
} );

$container->register( "user-repository", function() use ( $container ) {
	$repo = new \Model\Services\UserRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

// Helpers

$container->register( "access-control", function() {
	$helper = new \Helpers\AccessControl;
	return $helper;
} );

$container->register( "csv-generator", function() use ( $container ) {
	$helper = new \Helpers\CSVGenerator;
	return $helper;
} );

$container->register( "email-builder", function() use ( $container ) {
	$helper = new \Helpers\EmailBuilder(
		$container->getService( "config" )
	);
	return $helper;
} );

$container->register( "html-interview-results-builder", function() use ( $container ) {
	$helper = new \Helpers\HTMLInterviewResultsBuilder;
	return $helper;
} );

$container->register( "time-converter", function() use ( $container ) {
	$helper = new \Helpers\TimeConverter;
	return $helper;
} );

$container->register( "time-manager", function() {
	$timeManager = new \Helpers\TimeManager;
	return $timeManager;
} );

$container->register( "time-zone-helper", function() {
	$helper = new \Helpers\TimeZoneHelper;
	return $helper;
} );

$container->register( "access-control", function() {
	$accessControl = new \Helpers\AccessControl;
	return $accessControl;
} );

$container->register( "image-manager", function() {
	$imageManager = new \Helpers\ImageManager;
	return $imageManager;
} );

$container->register( "video-manager", function() {
	$helper = new \Helpers\VideoManager;
	return $helper;
} );
