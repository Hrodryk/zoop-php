<?php

namespace Zoop\Resource;

use stdClass;
use UnexpectedValueException;

/**
 * Class Buyer.
 */
class Buyer extends ZoopResource
{
    /**
     * Path buyers API.
     *
     * @const string
     */
    const PATH = 'marketplaces/%s/buyers';

    /**
     * Address Type.
     *
     * @const string
     */
    const ADDRESS_BILLING = 'BILLING';

    /**
     * Address Type.
     *
     * @const string
     */
    const ADDRESS_SHIPPING = 'SHIPPING';

    /**
     * Standard country .
     *
     * @const string
     */
    const ADDRESS_COUNTRY = 'BR';

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
     * @return \Zoop\Resource\BuyerCreditCard
     */
    public function creditCard()
    {
        return new BuyerCreditCard($this->zoop);
    }

    /**
     * Add a new address to the buyer.
     *
     * @param string $line1          Address.
     * @param string $line2          Address.
     * @param string $line3          Address.
     * @param string $neighborhood   Neighborhood address.
     * @param string $city           City address.
     * @param string $state          State address.
     * @param string $postalCode     The zip code billing address.
     * @param string $complement     Address complement.
     * @param string $country        Country ISO-alpha2 format, BR example.
     *
     * @return $this
     */
    public function addAddress($line1, $line2 = null, $line3 = null, $neighborhood, $city, $state, $postalCode, $countryCode = self::ADDRESS_COUNTRY)
    {
        $address = new stdClass();
        $address->line1 = $line1;
        $address->line2 = $line2;
        $address->line3 = $line3;
        $address->neighborhood = $neighborhood;
        $address->city = $city;
        $address->state = $state;
        $address->postal_code = $postalCode;
        $address->country_code = $countryCode;

        $this->data->address = $address;

        return $this;
    }

    /**
     * Create a new buyer.
     *
     * @return \stdClass
     */
    public function create()
    {
        return $this->createResource(sprintf('/%s/%s/', ZoopResource::VERSION, sprintf(self::PATH, $this->zoop->getMarketplaceId())));
    }

    /**
     * Find a buyer.
     *
     * @param string $zoop_id
     *
     * @return \Zoop\Resource\Buyer|stdClass
     */
    public function get($zoop_id)
    {
        return $this->getByPath(sprintf('/%s/%s/%s', ZoopResource::VERSION, sprintf(self::PATH, $this->zoop->getMarketplaceId()), $zoop_id));
    }

    /**
     * Get buyer id.
     *
     * @return string The buyer id.
     */
    public function getId()
    {
        return $this->getIfSet('id');
    }

    /**
     * Get buyer address.
     *
     * @return \stdClass Buyer's address.
     */
    public function getBillingAddress()
    {
        return $this->getIfSet('billingAddress');
    }

    /**
     * Get buyer address.
     *
     * @return \stdClass Buyer's address.
     */
    public function getShippingAddress()
    {
        return $this->getIfSet('shippingAddress');
    }

    /**
     * Get buyer fullname.
     *
     * @return string Buyer's full name.
     */
    public function getFullname()
    {
        return $this->getIfSet('fullname');
    }

    /**
     * Get funding instrument from buyer.
     *
     * @return \stdClass Structure that is the means of payment.
     */
    public function getFundingInstrument()
    {
        return $this->getIfSet('fundingInstrument');
    }

    /**
     * Get birth date from buyer.
     *
     * @return \DateTime|null Date of birth of the credit card holder.
     */
    public function getBirthDate()
    {
        return $this->getIfSetDate('birthDate');
    }

    /**
     * Get phone area code from buyer.
     *
     * @return int DDD telephone.
     */
    public function getPhoneAreaCode()
    {
        return $this->getIfSet('areaCode', $this->data->phone);
    }

    /**
     * Get phone country code from buyer.
     *
     * @return int Country code.
     */
    public function getPhoneCountryCode()
    {
        return $this->getIfSet('countryCode', $this->data->phone);
    }

    /**
     * Get phone number from buyer.
     *
     * @return int Telephone number.
     */
    public function getPhoneNumber()
    {
        return $this->getIfSet('number', $this->data->phone);
    }

    /**
     * Get tax document type from buyer.
     *
     * @return string Type of value: CPF and CNPJ
     */
    public function getTaxDocumentType()
    {
        return $this->getIfSet('type', $this->data->taxDocument);
    }

    /**
     * Get tax document number from buyer.
     *
     * @return string Document Number.
     */
    public function getTaxDocumentNumber()
    {
        return $this->getIfSet('number', $this->data->taxDocument);
    }

    /**
     * Mount the buyer structure from buyer.
     *
     * @param \stdClass $response
     *
     * @return Buyer Buyer information.
     */
    protected function populate(stdClass $response)
    {
        $buyer = clone $this;
        $buyer->data = new stdClass();
        $buyer->data->id = $this->getIfSet('id', $response);
        $buyer->data->status = $this->getIfSet('status', $response);
        $buyer->data->resource = $this->getIfSet('resource', $response);
        $buyer->data->account_balance = $this->getIfSet('account_balance', $response);
				$buyer->data->current_balance = $this->getIfSet('current_balance', $response);
				$buyer->data->first_name = $this->getIfSet('first_name', $response);
				$buyer->data->last_name = $this->getIfSet('last_name', $response);
				$buyer->data->taxpayer_id = $this->getIfSet('taxpayer_id', $response);
				$buyer->data->description = $this->getIfSet('description', $response);
				$buyer->data->email = $this->getIfSet('email', $response);
				$buyer->data->phone_number = $this->getIfSet('phone_number', $response);
				$buyer->data->facebook = $this->getIfSet('facebook', $response);
				$buyer->data->twitter = $this->getIfSet('twitter', $response);
        $buyer->data->address = new stdClass();

        $address = $this->getIfSet('address', $response);

				$buyer->data->address->line1 = $this->getIfSet('line1', $address);
				$buyer->data->address->line2 = $this->getIfSet('line2', $address);
				$buyer->data->address->line3 = $this->getIfSet('line3', $address);
				$buyer->data->address->neighborhood = $this->getIfSet('neighborhood', $address);
				$buyer->data->address->city = $this->getIfSet('city', $address);
				$buyer->data->address->state = $this->getIfSet('state', $address);
				$buyer->data->address->postal_code = $this->getIfSet('postal_code', $address);
				$buyer->data->address->country_code = $this->getIfSet('country_code', $address);

				$buyer->data->delinquent = $this->getIfSet('delinquent', $response);
				$buyer->data->payment_methods = $this->getIfSet('payment_methods', $response);
				$buyer->data->default_debit = $this->getIfSet('default_debit', $response);
				$buyer->data->default_credit = $this->getIfSet('default_credit', $response);
				$buyer->data->default_receipt_delivery_method = $this->getIfSet('default_receipt_delivery_method', $response);
				$buyer->data->uri = $this->getIfSet('uri', $response);
				$buyer->data->metadata = $this->getIfSet('metadata', $response);
				$buyer->data->created_at = $this->getIfSet('created_at', $response);
				$buyer->data->updated_at = $this->getIfSet('updated_at', $response);

        return $buyer;
    }

    /**
     * Set Own id from buyer.
     *
     * @param string $ownId Buyer's own id. external reference.
     *
     * @return $this
     */
    public function setOwnId($ownId)
    {
        $this->data->ownId = $ownId;

        return $this;
    }

    /**
     * Set first_name from buyer.
     *
     * @param string $first_name Buyer's first name.
     *
     * @return $this
     */
    public function setFirstName($first_name)
    {
        $this->data->first_name = $first_name;

        return $this;
    }

    /**
     * Set last_name from buyer.
     *
     * @param string $last_name Buyer's last name.
     *
     * @return $this
     */
    public function setLastName($last_name)
    {
        $this->data->last_name = $last_name;

        return $this;
    }

    /**
     * Set e-mail from buyer.
     *
     * @param string $email Email buyer.
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->data->email = $email;

        return $this;
    }

    /**
     * Set credit card from buyer.
     *
     * @param int                          $expirationMonth Card expiration month.
     * @param int                          $expirationYear  Year card expiration.
     * @param int                          $number          Card number.
     * @param int                          $cvc             Card Security Code.
     * @param \Zoop\Resource\Buyer|null $holder          Cardholder.
     *
     * @return $this
     */
    public function setCreditCard($expirationMonth, $expirationYear, $number, $cvc, Holder $holder = null)
    {
        if ($holder === null) {
            $holder = $this;
        }
        $birthdate = $holder->getBirthDate();
        if ($birthdate instanceof \DateTime) {
            $birthdate = $birthdate->format('Y-m-d');
        }

        $this->data->fundingInstrument = new stdClass();
        $this->data->fundingInstrument->method = Payment::METHOD_CREDIT_CARD;
        $this->data->fundingInstrument->creditCard = new stdClass();
        $this->data->fundingInstrument->creditCard->expirationMonth = $expirationMonth;
        $this->data->fundingInstrument->creditCard->expirationYear = $expirationYear;
        $this->data->fundingInstrument->creditCard->number = $number;
        $this->data->fundingInstrument->creditCard->cvc = $cvc;
        $this->data->fundingInstrument->creditCard->holder = new stdClass();
        $this->data->fundingInstrument->creditCard->holder->fullname = $holder->getFullname();
        $this->data->fundingInstrument->creditCard->holder->birthdate = $birthdate;
        $this->data->fundingInstrument->creditCard->holder->taxDocument = new stdClass();
        $this->data->fundingInstrument->creditCard->holder->taxDocument->type = $holder->getTaxDocumentType();
        $this->data->fundingInstrument->creditCard->holder->taxDocument->number = $holder->getTaxDocumentNumber();
        $this->data->fundingInstrument->creditCard->holder->phone = new stdClass();
        $this->data->fundingInstrument->creditCard->holder->phone->countryCode = $holder->getPhoneCountryCode();
        $this->data->fundingInstrument->creditCard->holder->phone->areaCode = $holder->getPhoneAreaCode();
        $this->data->fundingInstrument->creditCard->holder->phone->number = $holder->getPhoneNumber();

        return $this;
    }

    /**
     * Set birth date from buyer.
     *
     * @param \DateTime|string $birthDate Date of birth of the credit card holder.
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
     * Set taxpayer id from buyer.
     *
     * @param string $taxpayer_id Taxpayer id.
     *
     * @return $this
     */
    public function setTaxpayerId($taxpayer_id)
    {
        $this->data->taxpayer_id = $taxpayer_id;

        return $this;
    }

    /**
     * Set phone from buyer.
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
}
