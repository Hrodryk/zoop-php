<?php

namespace Zoop\Resource;

use stdClass;

/**
* Class BankAccount.
*/
class BankAccount extends ZoopResource
{
	/**
	* Path bank accounts API.
	*
	* @const string
	*/
	const PATH = 'marketplaces/%s/bank_accounts';

	/**
	* Path accounts API.
	*
	* @const string
	*/
	const PATH_ACCOUNT = 'accounts';

	/**
	* Bank account type.
	*
	* @const string
	*/
	const CHECKING = 'CHECKING';

	/**
	* Bank account type.
	*
	* @const string
	*/
	const SAVING = 'SAVING';

	/**
	* Initialize a new instance.
	*/
	public function initialize()
	{
		$this->data = new stdClass();
	}

	/**
	* Returns bank account id.
	*
	* @return stdClass
	*/
	public function getId()
	{
		return $this->getIfSet('id');
	}

	/**
	* Set buyer associated with the bank account.
	*
	* @param \Zoop\Resource\Buyer $buyer buyer associated with the request.
	*
	* @return $this
	*/
	public function setBuyer(Buyer $buyer)
	{
		$this->data->customer = $buyer->getId();

		return $this;
	}

	/**
	* Set buyer id associated with the bank account.
	*
	* @param string $id Buyer's id.
	*
	* @return $this
	*/
	public function setBuyerId($id)
	{
		$this->data->customer = $id;

		return $this;
	}

	public function setHolderName($holderName)
	{
		$this->data->holder_name = $holderName;

		return $this;
	}

	public function setTaxpayerId($taxpayerId)
	{
		$this->data->taxpayer_id = $taxpayerId;

		return $this;
	}

	public function setBankCode($bankCode)
	{
		$this->data->bank_code = $bankCode;

		return $this;
	}

	public function setType($type)
	{
		$this->data->type = $type;

		return $this;
	}

	public function setAccountNumber($accountNumber)
	{
		$this->data->account_number = $accountNumber;

		return $this;
	}

	public function setRoutingNumber($routingNumber)
	{
		$this->data->routing_number = $routingNumber;

		return $this;
	}

	public function setIsActive($isActive)
	{
		$this->data->is_active = $isActive;

		return $this;
	}

	/**
	* Set bank account token associated with the transaction.
	*
	* @param \Zoop\Resource\BankAccountToken $bankAccountToken bank account token associated with the request.
	*
	* @return $this
	*/
	public function setBankAccountToken(BankAccountToken $bankAccountToken)
	{
		$this->data->token = $bankAccountToken->getId();

		return $this;
	}

	/**
	* Returns bank account type.
	*
	* @return string
	*/
	public function getType()
	{
		return $this->getIfSet('type');
	}

	/**
	* Returns bank number.
	*
	* @return string
	*/
	public function getBankNumber()
	{
		return $this->getIfSet('bankNumber');
	}

	/**
	* Returns bank account agency number.
	*
	* @return int
	*/
	public function getAgencyNumber()
	{
		return $this->getIfSet('agencyNumber');
	}

	/**
	* Returns bank account agency check number.
	*
	* @return int
	*/
	public function getAgencyCheckNumber()
	{
		return $this->getIfSet('agencyCheckNumber');
	}

	/**
	* Returns bank account number.
	*
	* @return int
	*/
	public function getAccountNumber()
	{
		return $this->getIfSet('accountNumber');
	}

	/**
	* Returns bank account check number.
	*
	* @return int
	*/
	public function getAccountCheckNumber()
	{
		return $this->getIfSet('accountCheckNumber');
	}

	/**
	* Returns holder full name.
	*
	* @return string
	*/
	public function getFullname()
	{
		return $this->getIfSet('fullname', $this->data->holder);
	}

	/**
	* Get tax document type from customer.
	*
	* @return string Type of value: CPF and CNPJ
	*/
	public function getTaxDocumentType()
	{
		return $this->getIfSet('type', $this->data->holder->taxDocument);
	}

	/**
	* Get tax document number from customer.
	*
	* @return string Document Number.
	*/
	public function getTaxDocumentNumber()
	{
		return $this->getIfSet('number', $this->data->holder->taxDocument);
	}

	/**
	* Get a bank account.
	*
	* @param string $bank_account_id Bank account id.
	*
	* @return stdClass
	*/
	public function get($bank_account_id)
	{
		return $this->getByPath(sprintf('/%s/%s/%s', ZoopResource::VERSION, sprintf(self::PATH, $this->zoop->getMarketplaceId()), $bank_account_id));
	}

	/**
	* Create a new BankAccount List instance.
	*
	* @param string Account id.
	*
	* @return \Zoop\Resource\BankAccountList
	*/
	public function getList($account_id)
	{
		$bankAccountList = new BankAccountList($this->zoop);

		return $bankAccountList->get($account_id);
	}

	/**
	* Create a new bank account.
	*
	* @return stdClass
	*/
	public function create()
	{
		return $this->createResource(sprintf('/%s/%s/', ZoopResource::VERSION, sprintf(self::PATH, $this->zoop->getMarketplaceId())));
	}


	/**
	* Update a bank account.
	*
	* @param string|null $bank_account_id Bank account id.
	*
	* @return stdClass
	*/
	public function update($bank_account_id = null)
	{
		$bank_account_id = (!empty($bank_account_id) ? $bank_account_id : $this->getId());

		return $this->updateByPath(sprintf('/%s/%s/%s', ZoopResource::VERSION, sprintf(self::PATH, $this->zoop->getMarketplaceId()), $bank_account_id));
	}

	/**
	* Delete a bank account.
	*
	* @param string $bank_account_id Bank account id.
	*
	* @return mixed
	*/
	public function delete($bank_account_id)
	{
		return $this->deleteByPath(sprintf('/%s/%s/%s', ZoopResource::VERSION, sprintf(self::PATH, $this->zoop->getMarketplaceId()), $bank_account_id));
	}

	/**
	* Mount the bank account structure.
	*
	* @param stdClass $response
	*
	* @return \Zoop\Resource\BankAccount
	*/
	protected function populate(stdClass $response)
	{
		$bank_account = clone $this;
		$bank_account->data->id = $this->getIfSet('id', $response);
		$bank_account->data->agencyNumber = $this->getIfSet('agencyNumber', $response);
		$bank_account->data->accountNumber = $this->getIfSet('accountNumber', $response);
		$bank_account->data->status = $this->getIfSet('status', $response);
		$bank_account->data->accountCheckNumber = $this->getIfSet('accountCheckNumber', $response);
		$bank_account->data->bankName = $this->getIfSet('bankName', $response);
		$bank_account->data->type = $this->getIfSet('type', $response);
		$bank_account->data->agencyCheckNumber = $this->getIfSet('agencyCheckNumber', $response);
		$bank_account->data->bankNumber = $this->getIfSet('bankNumber', $response);

		$bank_account->data->_links = $this->getIfSet('_links', $response);
		$bank_account->data->createdAt = $this->getIfSet('createdAt', $response);

		return $bank_account;
	}
}
