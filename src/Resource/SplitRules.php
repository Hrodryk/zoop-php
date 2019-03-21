<?php

namespace Zoop\Resource;

use Zoop\Helper\Filters;
use Zoop\Helper\Pagination;
use Requests;
use stdClass;

/**
* Class SplitRules.
*/
class SplitRules extends ZoopResource
{
	/**
	* Path bank accounts API.
	*
	* @const string
	*/
	const PATH = 'split_rules';

	/**
	* Path bank accounts API.
	*
	* @const string
	*/
	const PATH_TRANSACTION = 'marketplaces/%s/transactions';

	/**
	* @const string
	*/
	const METHOD = 'BANK_ACCOUNT';

	/**
	* @const string
	*/
	const TYPE = 'CHECKING';

	/**
	* @const string
	*/
	const TYPE_HOLD = 'CPF';

	/**
	* Initializes new instances.
	*/
	protected function initialize()
	{
		$this->data = new stdClass();
		$this->data->splitRuleInstrument = new stdClass();
		$this->data->splitRuleInstrument->bankAccount = new stdClass();
		$this->data->splitRuleInstrument->bankAccount->holder = new stdClass();
		$this->data->splitRuleInstrument->bankAccount->holder->taxDocument = new stdClass();
	}

	/**
	* @param stdClass $response
	*
	* @return SplitRules
	*/
	protected function populate(stdClass $response)
	{
		$splitRules = clone $this;

		$splitRules->data->id = $this->getIfSet('id', $response);
		$splitRules->data->ownId = $this->getIfSet('ownId', $response);
		$splitRules->data->amount = $this->getIfSet('amount', $response);

		$splitRule_instrument = $this->getIfSet('splitRuleInstrument', $response);
		$splitRules->data->splitRuleInstrument = new stdClass();
		$splitRules->data->splitRuleInstrument->method = $this->getIfSet('method', $splitRule_instrument);

		$bank_account = $this->getIfSet('bankAccount', $splitRule_instrument);
		$splitRules->data->splitRuleInstrument->bankAccount = new stdClass();
		$splitRules->data->splitRuleInstrument->bankAccount->id = $this->getIfSet('id', $bank_account);
		$splitRules->data->splitRuleInstrument->bankAccount->type = $this->getIfSet('type', $bank_account);
		$splitRules->data->splitRuleInstrument->bankAccount->bankNumber = $this->getIfSet('bankNumber', $bank_account);
		$splitRules->data->splitRuleInstrument->bankAccount->agencyNumber = $this->getIfSet('agencyNumber', $bank_account);
		$splitRules->data->splitRuleInstrument->bankAccount->agencyCheckNumber = $this->getIfSet('agencyCheckNumber', $bank_account);
		$splitRules->data->splitRuleInstrument->bankAccount->accountNumber = $this->getIfSet('accountNumber', $bank_account);
		$splitRules->data->splitRuleInstrument->bankAccount->accountCheckNumber = $this->getIfSet('accountCheckNumber', $bank_account);

		$holder = $this->getIfSet('holder', $bank_account);
		$splitRules->data->splitRuleInstrument->bankAccount->holder = new stdClass();
		$splitRules->data->splitRuleInstrument->bankAccount->holder->fullname = $this->getIfSet('fullname', $holder);

		$tax_document = $this->getIfSet('taxDocument', $holder);
		$this->data->splitRuleInstrument->bankAccount->holder->taxDocument = new stdClass();
		$this->data->splitRuleInstrument->bankAccount->holder->taxDocument->type = $this->getIfSet('type', $tax_document);
		$this->data->splitRuleInstrument->bankAccount->holder->taxDocument->number = $this->getIfSet('number', $tax_document);

		return $splitRules;
	}

	/**
	* Set info of splitRules.
	*
	* @param int    $amount
	* @param string $bankNumber         Bank number. possible values: 001, 237, 341, 041.
	* @param int    $agencyNumber
	* @param int    $agencyCheckNumber
	* @param int    $accountNumber
	* @param int    $accountCheckNumber
	*
	* @return $this
	*/
	public function setSplitRules(
		$amount,
		$bankNumber,
		$agencyNumber,
		$agencyCheckNumber,
		$accountNumber,
		$accountCheckNumber
	) {
		$this->data->amount = $amount;
		$this->data->splitRuleInstrument->method = self::METHOD;
		$this->data->splitRuleInstrument->bankAccount->type = self::TYPE;
		$this->data->splitRuleInstrument->bankAccount->bankNumber = $bankNumber;
		$this->data->splitRuleInstrument->bankAccount->agencyNumber = $agencyNumber;
		$this->data->splitRuleInstrument->bankAccount->agencyCheckNumber = $agencyCheckNumber;
		$this->data->splitRuleInstrument->bankAccount->accountNumber = $accountNumber;
		$this->data->splitRuleInstrument->bankAccount->accountCheckNumber = $accountCheckNumber;

		return $this;
	}

	/**
	* Set info of splitRules to a saved bank account.
	*
	* @param int    $amount        Amount
	* @param string $bankAccountId Saved bank account id.
	*
	* @return $this
	*/
	public function setSplitRulesToBankAccount($amount, $bankAccountId)
	{
		$this->data->amount = $amount;
		$this->data->splitRuleInstrument->method = self::METHOD;
		$this->data->splitRuleInstrument->bankAccount->id = $bankAccountId;

		return $this;
	}

	/**
	* Set value to the splitRule.
	*
	* @param int|float $amount value to the splitRule.
	*
	* @return $this
	*/
	public function setAmount($amount)
	{
		$this->data->amount = (float) $amount;

		return $this;
	}

	/**
	* Set value to the splitRule.
	*
	* @param int|float $amount value to the splitRule.
	*
	* @return $this
	*/
	public function setRecipient($recipient)
	{
		$this->data->recipient = $recipient;

		return $this;
	}

	/**
	* Set value to the splitRule.
	*
	* @param int|float $amount value to the splitRule.
	*
	* @return $this
	*/
	public function setLiable($liable)
	{
		$this->data->liable = $liable;

		return $this;
	}

	/**
	* Set value to the splitRule.
	*
	* @param int|float $amount value to the splitRule.
	*
	* @return $this
	*/
	public function setChargeProcessingFee($chargeProcessingFee)
	{
		$this->data->charge_processing_fee = $chargeProcessingFee;

		return $this;
	}

	/**
	* Returns splitRule.
	*
	* @return stdClass
	*/
	public function getSplitRules()
	{
		return $this->data;
	}

	/**
	* Get own request id. external reference.
	*
	* @param mixed $ownId id
	*
	* @return $this
	*/
	public function setOwnId($ownId)
	{
		$this->data->ownId = $ownId;

		return $this;
	}

	/**
	* Set info of holder.
	*
	* @param string $fullname
	* @param int    $taxDocument
	*
	* @return $this
	*/
	public function setHolder($fullname, $taxDocument)
	{
		$this->data->splitRuleInstrument->bankAccount->holder->fullname = $fullname;
		$this->data->splitRuleInstrument->bankAccount->holder->taxDocument->type = self::TYPE_HOLD;
		$this->data->splitRuleInstrument->bankAccount->holder->taxDocument->number = $taxDocument;

		return $this;
	}

	/**
	* Returns splitRule holder.
	*
	* @return stdClass
	*/
	public function getHolder()
	{
		return $this->data->splitRuleInstrument->bankAccount->holder;
	}

	/**
	* Create a new splitRule.
	*
	* @param string bank account id.
	*
	* @return stdClass
	*/
	public function create($transaction)
	{
		if($transaction instanceof Transactions) {
			$transaction = $transaction->getId();
		}
		return $this->createResource(sprintf('/%s/%s/%s/%s', ZoopResource::VERSION, sprintf(self::PATH_TRANSACTION, $this->zoop->getMarketplaceId()), $transaction, self::PATH));
	}

	/**
	* Revert Tranfers.
	*
	* @param string $id SplitRule id.
	*
	* @return SplitRules
	*/
	public function revert($id)
	{
		$path = sprintf('/%s/%s/%s/%s', ZoopResource::VERSION, self::PATH, $id, 'reverse');

		$response = $this->httpRequest($path, Requests::POST, $this);

		return $this->populate($response);
	}

	/**
	* Get a SplitRule.
	*
	* @param string $id SplitRule id.
	*
	* @return stdClass
	*/
	public function get($id)
	{
		return $this->getByPath(sprintf('/%s/%s/%s', ZoopResource::VERSION, self::PATH, $id));
	}

	/**
	* Create a new SplitRules list instance.
	*
	* @return \Zoop\Resource\SplitRulesList
	*/
	public function getList(Pagination $pagination = null, Filters $filters = null, $qParam = '')
	{
		$splitRulesList = new SplitRulesList($this->zoop);

		return $splitRulesList->get($pagination, $filters, $qParam);
	}

	/**
	* Get MoIP SplitRules id.
	*
	* @return string
	*/
	public function getId()
	{
		return $this->getIfSet('id');
	}

	/**
	* Get own request id. external reference.
	*
	* @return mixed
	*/
	public function getOwnId()
	{
		return $this->getIfSet('ownId');
	}
}
