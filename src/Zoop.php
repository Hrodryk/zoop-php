<?php

namespace Zoop;

use Zoop\Contracts\Authentication;
use Zoop\Resource\Account;
use Zoop\Resource\Balances;
use Zoop\Resource\BankAccount;
use Zoop\Resource\Buyer;
use Zoop\Resource\Entry;
use Zoop\Resource\CardToken;
use Zoop\Resource\BankAccountToken;
use Zoop\Resource\Keys;
use Zoop\Resource\Multitransactions;
use Zoop\Resource\NotificationPreferences;
use Zoop\Resource\Transactions;
use Zoop\Resource\Payment;
use Zoop\Resource\Boleto;
use Zoop\Resource\Refund;
use Zoop\Resource\Transfers;
use Zoop\Resource\WebhookList;
use Requests_Session;

/**
 * Class Zoop.
 */
class Zoop
{
    /**
     * endpoint of production.
     *
     * @const string
     */
    const ENDPOINT = 'https://api.zoop.ws/';

    /**
     * Client name.
     *
     * @const string
     * */
    const CLIENT = 'ZoopPhpSDK';

    /**
     * Client Version.
     *
     * @const string
     */
    const CLIENT_VERSION = '3.1.0';

    /**
     * Authentication that will be added to the header of request.
     *
     * @var \Zoop\ZoopAuthentication
     */
    private $zoopAuthentication;

    /**
     * Endpoint of request.
     *
     * @var \Zoop\Zoop::ENDPOINT|\Zoop\Zoop::ENDPOINT_SANDBOX
     */
    private $endpoint;

    /**
     * Marketplace id.
     *
     * @var string
     */
    private $marketplaceId;

    /**
     * @var Requests_Session HTTP session configured to use the zoop API.
     */
    private $session;

    /**
     * Create a new aurhentication with the endpoint.
     *
     * @param \Zoop\Auth\ZoopAuthentication $zoopAuthentication
     * @param string                        $endpoint
     */
    public function __construct(Authentication $zoopAuthentication, $marketplaceId)
    {
        $this->zoopAuthentication = $zoopAuthentication;
        $this->marketplaceId = $marketplaceId;
        $this->endpoint = self::ENDPOINT;
        $this->createNewSession();
    }

    /**
     * Creates a new Request_Session with all the default values.
     * A Session is created at construction.
     *
     * @param float $timeout         How long should we wait for a response?(seconds with a millisecond precision, default: 30, example: 0.01).
     * @param float $connect_timeout How long should we wait while trying to connect? (seconds with a millisecond precision, default: 10, example: 0.01)
     */
    public function createNewSession($timeout = 30.0, $connect_timeout = 30.0)
    {
        $user_agent = sprintf('%s/%s (+https://github.com/zoop/zoop-sdk-php/)', self::CLIENT, self::CLIENT_VERSION);
        $sess = new Requests_Session($this->endpoint);
        $sess->options['auth'] = $this->zoopAuthentication;
        $sess->options['timeout'] = $timeout;
        $sess->options['connect_timeout'] = $connect_timeout;
        $sess->options['useragent'] = $user_agent;
        $this->session = $sess;
    }

    /**
     * Returns the http session created.
     *
     * @return Requests_Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Replace the http session by a custom one.
     *
     * @param Requests_Session $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * Create a new Buyer instance.
     *
     * @return \Zoop\Resource\Buyer
     */
    public function buyers()
    {
        return new Buyer($this);
    }

    /**
     * Create a new CardToken instance.
     *
     * @return \Zoop\Resource\CardToken
     */
    public function cardTokens()
    {
        return new CardToken($this);
    }

		/**
     * Create a new BankAccountToken instance.
     *
     * @return \Zoop\Resource\BankAccountToken
     */
    public function bankAccountTokens()
    {
        return new BankAccountToken($this);
    }

    /**
     * Create a new Account instance.
     *
     * @return \Zoop\Resource\Account
     */
    public function accounts()
    {
        return new Account($this);
    }

    /**
     * Create a new Entry instance.
     *
     * @return \Zoop\Resource\Entry
     */
    public function entries()
    {
        return new Entry($this);
    }

    /**
     * Create a new Transactions instance.
     *
     * @return \Zoop\Resource\Transactions
     */
    public function transactions()
    {
        return new Transactions($this);
    }

    /**
     * Create a new Payment instance.
     *
     * @return \Zoop\Resource\Payment
     */
    public function payments()
    {
        return new Payment($this);
    }

		/**
     * Create a new Boleto instance.
     *
     * @return \Zoop\Resource\Boleto
     */
    public function boletos()
    {
        return new Boleto($this);
    }

    /**
     * Create a new Multitransactions instance.
     *
     * @return \Zoop\Resource\Multitransactions
     */
    public function multitransactions()
    {
        return new Multitransactions($this);
    }

    /**
     * Create a new Transfers.
     *
     * @return \Zoop\Resource\Transfers
     */

    /**
     * Create a new Transfers instance.
     *
     * @return Transfers
     */
    public function transfers()
    {
        return new Transfers($this);
    }

    /**
     * Create a new Notification Prefences instance.
     *
     * @return NotificationPreferences
     */
    public function notifications()
    {
        return new NotificationPreferences($this);
    }

    /**
     * Create a new WebhookList instance.
     *
     * @return WebhookList
     */
    public function webhooks()
    {
        return new WebhookList($this);
    }

    /**
     * Create a new Keys instance.
     *
     * @return Keys
     */
    public function keys()
    {
        return new Keys($this);
    }

    /**
     * Create a new Refund instance.
     *
     * @return Refund
     */
    public function refunds()
    {
        return new Refund($this);
    }

    /**
     * Create a new BankAccount instance.
     *
     * @return BankAccount
     */
    public function bankaccount()
    {
        return new BankAccount($this);
    }

    /**
     * Create a new Balances instance.
     *
     * @return Balances
     */
    public function balances()
    {
        return new Balances($this);
    }

    /**
     * Get the endpoint.
     *
     * @return \Zoop\Zoop::ENDPOINT|\Zoop\Zoop::ENDPOINT_SANDBOX
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Get the marketplaceId.
     *
     * @return string
     */
    public function getMarketplaceId()
    {
        return $this->marketplaceId;
    }
}
