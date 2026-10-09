<?php
/**
 * Test doubles shared across the suite.
 *
 * @package VuloMail
 */

namespace VuloMail\Tests;

use VuloMail\Connections\ProviderRegistry;
use VuloMail\Delivery\Result;
use VuloMail\Email\MailerInterface;
use VuloMail\Email\Message;
use VuloMail\Logging\LogRepository;
use VuloMail\Security\HttpClient;
use VuloMail\Sms\GatewayInterface;

/**
 * Records requests and replays queued responses instead of touching the network.
 */
class FakeHttp extends HttpClient {

	/**
	 * @var array<int, array{url: string, headers: array, body: mixed}>
	 */
	public $requests = array();

	/**
	 * @var array
	 */
	private $queue = array();

	/**
	 * @param int    $code    HTTP status.
	 * @param array  $json    Decoded body.
	 * @param array  $headers Response headers.
	 * @param string $body    Raw body; defaults to the JSON.
	 * @return void
	 */
	public function queue( $code, array $json = array(), array $headers = array(), $body = null ) {
		$this->queue[] = array(
			'code'    => $code,
			'json'    => $json,
			'headers' => $headers,
			'body'    => null === $body ? (string) json_encode( $json ) : $body,
		);
	}

	/**
	 * @param \WP_Error $error Transport error to return.
	 * @return void
	 */
	public function queue_error( \WP_Error $error ) {
		$this->queue[] = $error;
	}

	public function post( $url, array $headers, $body ) {
		$this->requests[] = compact( 'url', 'headers', 'body' );

		return array_shift( $this->queue );
	}
}

/**
 * Keeps log rows in memory.
 */
class FakeLogs extends LogRepository {

	/**
	 * @var array<int, array>
	 */
	public $rows = array();

	public function insert( array $row ) {
		$this->rows[] = $row;

		return count( $this->rows );
	}
}

/**
 * Email adapter whose outcome is scripted per connection through its `outcome` setting.
 */
class ScriptedMailer implements MailerInterface {

	/**
	 * @var array<int, array{config: array, message: Message}>
	 */
	public static $sent = array();

	private $config;

	public function __construct( array $config, $http = null ) {
		$this->config = $config;
	}

	public static function definition() {
		return array(
			'label'  => 'Scripted',
			'desc'   => '',
			'fields' => array(
				array(
					'key'      => 'outcome',
					'label'    => 'Outcome',
					'type'     => 'text',
					'required' => true,
				),
				array(
					'key'    => 'token',
					'label'  => 'Token',
					'type'   => 'password',
					'secret' => true,
				),
			),
		);
	}

	public function send( Message $message ) {
		self::$sent[] = array(
			'config'  => $this->config,
			'message' => $message,
		);

		if ( 'throw' === $this->config['outcome'] ) {
			throw new \RuntimeException( 'boom' );
		}

		return 'ok' === $this->config['outcome'] ? Result::ok( 'msg-1' ) : Result::fail( 'rejected', 'Provider said no' );
	}
}

/**
 * SMS adapter scripted the same way.
 */
class ScriptedGateway implements GatewayInterface {

	/**
	 * @var array<int, array{to: string, body: string}>
	 */
	public static $sent = array();

	private $config;

	public function __construct( array $config, $http = null ) {
		$this->config = $config;
	}

	public static function definition() {
		return ScriptedMailer::definition();
	}

	public function send( $to, $body ) {
		self::$sent[] = compact( 'to', 'body' );

		return 'ok' === $this->config['outcome'] ? Result::ok( 'sms-1' ) : Result::fail( 'rejected', 'Gateway said no' );
	}
}

/**
 * Registry exposing only the scripted adapters.
 */
class ScriptedRegistry extends ProviderRegistry {

	public function classes( $channel ) {
		return 'sms' === $channel
			? array( 'scripted' => ScriptedGateway::class )
			: array( 'scripted' => ScriptedMailer::class );
	}
}

/**
 * Stand-in for PHPMailer that records how it was configured.
 */
class FakePhpMailer {

	// phpcs:disable WordPress.NamingConventions.ValidVariableName -- mirrors PHPMailer's public API.
	public $Host        = 'localhost';
	public $Port        = 25;
	public $SMTPSecure  = '';
	public $SMTPAutoTLS = true;
	public $SMTPAuth    = false;
	public $Username    = '';
	public $Password    = '';
	public $Timeout     = 300;
	public $SMTPDebug   = 0;
	public $Mailer      = 'mail';
	public $CharSet     = '';
	public $Subject     = '';
	public $Body        = '';
	public $ContentType = '';
	// phpcs:enable

	public $calls = array();

	/**
	 * @var \Exception|null Thrown by send() when set.
	 */
	public $fail_with = null;

	public function __call( $name, $args ) {
		$this->calls[] = array( $name, $args );
	}

	public function isSMTP() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
		$this->Mailer = 'smtp'; // phpcs:ignore WordPress.NamingConventions.ValidVariableName
	}

	public function isHTML( $html ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
		$this->ContentType = $html ? 'text/html' : 'text/plain'; // phpcs:ignore WordPress.NamingConventions.ValidVariableName
	}

	public function send() {
		if ( $this->fail_with ) {
			throw $this->fail_with;
		}

		return true;
	}

	public function getLastMessageID() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
		return '<abc@example.com>';
	}

	/**
	 * @param string $method Method name.
	 * @return array<int, array> Arguments of every call to that method.
	 */
	public function calls_to( $method ) {
		$matches = array();

		foreach ( $this->calls as $call ) {
			if ( $call[0] === $method ) {
				$matches[] = $call[1];
			}
		}

		return $matches;
	}
}
