<?php

namespace Zoop\Resource;

use stdClass;

/**
* Class BankAccountToken.
*/
class BankAccountToken extends ZoopResource
{
	/**
	 * Path bankAccountToken API.
	 *
	 * @const string
	 */
	const PATH = 'marketplaces/%s/bank_accounts/tokens';

	/**
	* Address Type.
	*
	* @const string
	*/
	const ADDRESS_BILLING = 'BILLING';

	/**
	* Standard country .
	*
	* @const string
	*/
	const ADDRESS_COUNTRY = 'BRA';

	/**
	* Standard document type.
	*
	* @const string
	*/
	const TAX_DOCUMENT = 'CPF';

	/**
	* Initialize a new instance.
	*/
	public function initialize()
	{
		$this->data = new stdClass();
	}

	/**
	* Add a new address to the bankAccountToken.
	*
	* @param string $type       Address type: BILLING.
	* @param string $street     Street address.
	* @param string $number     Number address.
	* @param string $district   Neighborhood address.
	* @param string $city       City address.
	* @param string $state      State address.
	* @param string $zip        The zip code billing address.
	* @param string $complement Address complement.
	* @param string $country    Country ISO-alpha3 format, BRA example.
	*
	* @return $this
	*/
	public function setAddress($type, $street, $number, $district, $city, $state, $zip, $complement = null, $country = self::ADDRESS_COUNTRY)
	{
		$address = new stdClass();
		$address->street = $street;
		$address->streetNumber = $number;
		$address->complement = $complement;
		$address->district = $district;
		$address->city = $city;
		$address->state = $state;
		$address->country = $country;
		$address->zipCode = $zip;

		$this->data->billingAddress = $address;

		return $this;
	}

	/**
	* Get bankAccountToken id.
	*
	* @return \stdClass BankAccountToken's id.
	*/
	public function getId()
	{
		return $this->getIfSet('id');
	}


	/**
	* Get bankAccountToken address.
	*
	* @return \stdClass BankAccountToken's address.
	*/
	public function getBillingAddress()
	{
		return $this->getIfSet('billingAddress');
	}

	/**
	* Get holser fullname.
	*
	* @return string BankAccountToken's full name.
	*/
	public function getFullname()
	{
		return $this->getIfSet('fullname');
	}

	/**
	* Get birth date from bankAccountToken.
	*
	* @return \DateTime|null Date of birth of the credit card bankAccountToken.
	*/
	public function getBirthDate()
	{
		return $this->getIfSetDate('birthDate');
	}

	/**
	* Get phone area code from bankAccountToken.
	*
	* @return int DDD telephone.
	*/
	public function getPhoneAreaCode()
	{
		return $this->getIfSet('areaCode', $this->data->phone);
	}

	/**
	* Get phone country code from bankAccountToken.
	*
	* @return int Country code.
	*/
	public function getPhoneCountryCode()
	{
		return $this->getIfSet('countryCode', $this->data->phone);
	}

	/**
	* Get phone number from bankAccountToken.
	*
	* @return int Telephone number.
	*/
	public function getPhoneNumber()
	{
		return $this->getIfSet('number', $this->data->phone);
	}

	/**
	* Get tax document type from bankAccountToken.
	*
	* @return string Type of value: CPF and CNPJ
	*/
	public function getTaxDocumentType()
	{
		return $this->getIfSet('type', $this->data->taxDocument);
	}

	/**
	* Get tax document number from bankAccountToken.
	*
	* @return string Document Number.
	*/
	public function getTaxDocumentNumber()
	{
		return $this->getIfSet('number', $this->data->taxDocument);
	}

	/**
	* Mount the buyer structure from bankAccountToken.
	*
	* @param \stdClass $response
	*
	* @return BankAccountToken information.
	*/
	protected function populate(stdClass $response)
	{
		$bankAccountToken = clone $this;
		$bankAccountToken->data = new stdClass();
		$bankAccountToken->data->id = $this->getIfSet('id', $response);
		$bankAccountToken->data->card = new stdClass();

		$card = $this->getIfSet('card', $response);

		$bankAccountToken->data->card->id = $this->getIfSet('id', $card);
		$bankAccountToken->data->card->card_brand = $this->getIfSet('card_brand', $card);
		$bankAccountToken->data->card->first4_digits = $this->getIfSet('first4_digits', $card);
		$bankAccountToken->data->card->expiration_month = $this->getIfSet('expiration_month', $card);
		$bankAccountToken->data->card->expiration_year = $this->getIfSet('expiration_year', $card);
		$bankAccountToken->data->card->holder_name = $this->getIfSet('holder_name', $card);

		return $bankAccountToken;
	}

	/**
	* Set holder name from card.
	*
	* @param string $holderName Card's holder name.
	*
	* @return $this
	*/
	public function setHolderName($holderName)
	{
		$this->data->holder_name = $holderName;

		return $this;
	}

	/**
	* Set expiration month from card.
	*
	* @param string $expirationMonth Card's expiration month.
	*
	* @return $this
	*/
	public function setBankCode($bankCode)
	{
		$this->data->bank_code = $bankCode;

		return $this;
	}

	/**
	* Set expiration year from card.
	*
	* @param string $holderName Card's expiration year.
	*
	* @return $this
	*/
	public function setRoutingNumber($routingNumber)
	{
		$this->data->routing_number = $routingNumber;

		return $this;
	}

	/**
	* Set card number from card.
	*
	* @param string $cardNumber Card's card number.
	*
	* @return $this
	*/
	public function setAccountNumber($accountNumber)
	{
		$this->data->account_number = $accountNumber;

		return $this;
	}

	/**
	* Set security code from card.
	*
	* @param string $securityCode Card's security code.
	*
	* @return $this
	*/
	public function setTaxpayerId($taxpayerId)
	{
		$this->data->taxpayer_id = $taxpayerId;

		return $this;
	}

	/**
	* Set security code from card.
	*
	* @param string $securityCode Card's security code.
	*
	* @return $this
	*/
	public function setType($type)
	{
		$this->data->type = $type;

		return $this;
	}

	/**
	* Set birth date from bankAccountToken.
	*
	* @param \DateTime|string $birthDate Date of birth of the credit card bankAccountToken.
	*
	* @return $this
	*/
	public function setBirthDate($birthDate)
	{
		if ($birthDate instanceof \DateTime) {
			$birthDate = $birthDate->format('Y-m-d');
		}

		$this->data->birthDate = $birthDate;

		return $this;
	}

	/**
	* Set tax document from bankAccountToken.
	*
	* @param string $number Document number.
	* @param string $type   Document type.
	*
	* @return $this
	*/
	public function setTaxDocument($number, $type = self::TAX_DOCUMENT)
	{
		$this->data->taxDocument = new stdClass();
		$this->data->taxDocument->type = $type;
		$this->data->taxDocument->number = $number;

		return $this;
	}

	/**
	* Set phone from bankAccountToken.
	*
	* @param int $areaCode    DDD telephone.
	* @param int $number      Telephone number.
	* @param int $countryCode Country code.
	*
	* @return $this
	*/
	public function setPhone($areaCode, $number, $countryCode = 55)
	{
		$this->data->phone = new stdClass();
		$this->data->phone->countryCode = $countryCode;
		$this->data->phone->areaCode = $areaCode;
		$this->data->phone->number = $number;

		return $this;
	}

	/**
	* Create a new bankAccountToken.
	*
	* @return \stdClass
	*/
	public function create()
	{
		return $this->createResource(sprintf('/%s/%s/', ZoopResource::VERSION, sprintf(self::PATH, $this->zoop->getMarketplaceId())));
	}
}
