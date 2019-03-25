<?php

namespace Zoop\Resource;

use stdClass;

/**
* Class CardToken.
*/
class CardToken extends ZoopResource
{
	/**
	* Path cardToken API.
	*
	* @const string
	*/
	const PATH = 'marketplaces/%s/cards/tokens';

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
	* Add a new address to the cardToken.
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
	* Get cardToken id.
	*
	* @return \stdClass CardToken's id.
	*/
	public function getId()
	{
		return $this->getIfSet('id');
	}


	/**
	* Get cardToken address.
	*
	* @return \stdClass CardToken's address.
	*/
	public function getBillingAddress()
	{
		return $this->getIfSet('billingAddress');
	}

	/**
	* Get cardToken address.
	*
	* @return \stdClass CardToken's address.
	*/
	public function getCardId()
	{
		return $this->getIfSet('id', $this->data->card);
	}

	/**
	* Get cardToken address.
	*
	* @return \stdClass CardToken's address.
	*/
	public function getCardFirst4Digits()
	{
		return $this->getIfSet('first4_digits', $this->data->card);
	}

	/**
	* Get cardToken address.
	*
	* @return \stdClass CardToken's address.
	*/
	public function getCardBrand()
	{
		return $this->getIfSet('card_brand', $this->data->card);
	}

	/**
	* Get holser fullname.
	*
	* @return string CardToken's full name.
	*/
	public function getFullname()
	{
		return $this->getIfSet('fullname');
	}

	/**
	* Get birth date from cardToken.
	*
	* @return \DateTime|null Date of birth of the credit card cardToken.
	*/
	public function getBirthDate()
	{
		return $this->getIfSetDate('birthDate');
	}

	/**
	* Get phone area code from cardToken.
	*
	* @return int DDD telephone.
	*/
	public function getPhoneAreaCode()
	{
		return $this->getIfSet('areaCode', $this->data->phone);
	}

	/**
	* Get phone country code from cardToken.
	*
	* @return int Country code.
	*/
	public function getPhoneCountryCode()
	{
		return $this->getIfSet('countryCode', $this->data->phone);
	}

	/**
	* Get phone number from cardToken.
	*
	* @return int Telephone number.
	*/
	public function getPhoneNumber()
	{
		return $this->getIfSet('number', $this->data->phone);
	}

	/**
	* Get tax document type from cardToken.
	*
	* @return string Type of value: CPF and CNPJ
	*/
	public function getTaxDocumentType()
	{
		return $this->getIfSet('type', $this->data->taxDocument);
	}

	/**
	* Get tax document number from cardToken.
	*
	* @return string Document Number.
	*/
	public function getTaxDocumentNumber()
	{
		return $this->getIfSet('number', $this->data->taxDocument);
	}

	/**
	* Mount the buyer structure from cardToken.
	*
	* @param \stdClass $response
	*
	* @return CardToken information.
	*/
	protected function populate(stdClass $response)
	{
		$cardToken = clone $this;
		$cardToken->data = new stdClass();
		$cardToken->data->id = $this->getIfSet('id', $response);
		$cardToken->data->card = new stdClass();

		$card = $this->getIfSet('card', $response);

		$cardToken->data->card->id = $this->getIfSet('id', $card);
		$cardToken->data->card->card_brand = $this->getIfSet('card_brand', $card);
		$cardToken->data->card->first4_digits = $this->getIfSet('first4_digits', $card);
		$cardToken->data->card->expiration_month = $this->getIfSet('expiration_month', $card);
		$cardToken->data->card->expiration_year = $this->getIfSet('expiration_year', $card);
		$cardToken->data->card->holder_name = $this->getIfSet('holder_name', $card);

		return $cardToken;
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
	public function setExpirationMonth($expirationMonth)
	{
		$this->data->expiration_month = $expirationMonth;

		return $this;
	}

	/**
	* Set expiration year from card.
	*
	* @param string $holderName Card's expiration year.
	*
	* @return $this
	*/
	public function setExpirationYear($expirationYear)
	{
		$this->data->expiration_year = $expirationYear;

		return $this;
	}

	/**
	* Set card number from card.
	*
	* @param string $cardNumber Card's card number.
	*
	* @return $this
	*/
	public function setCardNumber($cardNumber)
	{
		$this->data->card_number = $cardNumber;

		return $this;
	}

	/**
	* Set security code from card.
	*
	* @param string $securityCode Card's security code.
	*
	* @return $this
	*/
	public function setSecurityCode($securityCode)
	{
		$this->data->security_code = $securityCode;

		return $this;
	}

	/**
	* Set birth date from cardToken.
	*
	* @param \DateTime|string $birthDate Date of birth of the credit card cardToken.
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
	* Set tax document from cardToken.
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
	* Set phone from cardToken.
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
	* Create a new cardToken.
	*
	* @return \stdClass
	*/
	public function create()
	{
		return $this->createResource(sprintf('/%s/%s/', ZoopResource::VERSION, sprintf(self::PATH, $this->zoop->getMarketplaceId())));
	}
}
