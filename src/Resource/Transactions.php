<?php

namespace Zoop\Resource;

use ArrayIterator;
use Zoop\Helper\Filters;
use Zoop\Helper\Pagination;
use stdClass;

/**
* Class Transactions.
*/
class Transactions extends ZoopResource
{
	/**
	* @const string
	*/
	const PATH = 'marketplaces/%s/transactions';

	/**
	* Defines what kind of payee as pripmary.
	*
	* @const string
	*/
	const RECEIVER_TYPE_PRIMARY = 'PRIMARY';

	/**
	* Defines what kind of payee as secundary.
	*
	* @const string
	*/
	const RECEIVER_TYPE_SECONDARY = 'SECONDARY';

	/**
	* Currency used in the application.
	*
	* @const string
	*/
	const AMOUNT_CURRENCY = 'BRL';

	/**
	* @var \Zoop\Resource\Transactions
	**/
	private $transactions;

	/**
	* Adds a new item to transaction.
	*
	* @param string $product  Name of the product.
	* @param int    $quantity Product Quantity.
	* @param string $detail   Additional product description.
	* @param int    $price    Initial value of the item.
	* @param string category  Product category. see: https://dev.zoop.com.br/v2.1/reference#tabela-de-categorias-de-produtos.
	*
	* @return $this
	*/
	public function addItem($product, $quantity, $detail, $price, $category = 'OTHER_CATEGORIES')
	{
		if (!is_int($price)) {
			throw new \UnexpectedValueException('Informe o valor do item como inteiro');
		}

		if (!is_int($quantity) || $quantity < 1) {
			throw new \UnexpectedValueException('A quantidade do item deve ser um valor inteiro maior que 0');
		}

		$item = new stdClass();
		$item->product = $product;
		$item->quantity = $quantity;
		$item->detail = $detail;
		$item->price = $price;
		$item->category = $category;
		$this->data->items[] = $item;

		return $this;
	}

	/**
	*  Adds a new receiver to transaction.
	*
	* @param string $zoopAccount Id Zoop Zoop account that will receive payment values.
	* @param string $type        Define qual o tipo de recebedor do pagamento, valores possíveis: PRIMARY, SECONDARY.
	* @param int    $fixed       Value that the receiver will receive.
	* @param int    $percentual  Percentual value that the receiver will receive. Possible values: 0 - 100
	* @param bool   $feePayor    Flag to know if receiver is the payer of Zoop tax.
	*
	* @return $this
	*/
	public function addReceiver($zoopAccount, $type, $fixed = null, $percentual = null, $feePayor = false)
	{
		$receiver = new stdClass();
		$receiver->zoopAccount = new stdClass();
		$receiver->zoopAccount->id = $zoopAccount;
		if (!empty($fixed)) {
			$receiver->amount = new stdClass();
			$receiver->amount->fixed = $fixed;
		}
		if (!empty($percentual)) {
			$receiver->amount = new stdClass();
			$receiver->amount->percentual = $percentual;
		}
		$receiver->feePayor = $feePayor;
		$receiver->type = $type;

		$this->data->receivers[] = $receiver;

		return $this;
	}

	/**
	* Initialize necessary used in some functions.
	*/
	protected function initialize()
	{
		$this->data = new stdClass();
		$this->data->currency = self::AMOUNT_CURRENCY;
	}

	/**
	* Initialize necessary used in some functions.
	*/
	private function initializeSubtotals()
	{
		if (!isset($this->data->subtotals)) {
			$this->data->subtotals = new stdClass();
		}
	}

	/**
	* Mount the structure of transaction.
	*
	* @param \stdClass $response
	*
	* @return Transactions Response transaction.
	*/
	protected function populate(stdClass $response)
	{
		$this->transactions = clone $this;
		$this->transactions->data->id = $response->id;
		$this->transactions->data->status = $response->status;
		$this->transactions->data->amount = $response->amount;
		$this->transactions->data->original_amount = $response->original_amount;
		$this->transactions->data->currency = $response->currency;
		$this->transactions->data->payment_type = $response->payment_type;
		$this->transactions->data->on_behalf_of = $response->on_behalf_of;
		$this->transactions->data->customer = $response->customer;
		$this->transactions->data->reference_id = $response->reference_id;
		$this->transactions->data->payment_method = $response->payment_method;

		return $this->transactions;
	}

	/**
	* Structure resource.
	*
	* @param stdClass                                                                               $response
	* @param string                                                                                 $resource
	* @param \Zoop\Resource\Payment|\Zoop\Resource\Refund|\Zoop\Resource\Entry|\Zoop\Resource\Event $class
	*
	* @return array
	*/
	private function structure(stdClass $response, $resource, $class)
	{
		$structures = [];

		foreach ($response->$resource as $responseResource) {
			$structure = new $class($this->transactions->zoop);
			$structure->populate($responseResource);

			$structures[] = $structure;
		}

		return $structures;
	}

	/**
	* Create a new transaction in Zoop.
	*
	* @return \Zoop\Resource\Transactions|stdClass
	*/
	public function create()
	{
		return $this->createResource(sprintf('/%s/%s/', ZoopResource::VERSION, sprintf(self::PATH, $this->zoop->getMarketplaceId())));
	}

	/**
	* Get an transaction in Zoop.
	*
	* @param string $id_zoop Id Zoop transaction id
	*
	* @return stdClass
	*/
	public function get($id_zoop)
	{
		return $this->getByPath(sprintf('/%s/%s/%s', ZoopResource::VERSION, sprintf(self::PATH, $this->zoop->getMarketplaceId()), $id_zoop));
	}

	/**
	* Get Zoop transaction id.
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
	* @return string
	*/
	public function getOwnId()
	{
		return $this->getIfSet('ownId');
	}

	/**
	* Get paid value of transaction.
	*
	* @return int|float
	*/
	public function getAmountPaid()
	{
		return $this->getIfSet('paid', $this->data->amount);
	}

	/**
	* Get total value of transaction.
	*
	* @return int|float
	*/
	public function getAmountTotal()
	{
		return $this->getIfSet('total', $this->data->amount);
	}

	/**
	* Get total value of Zoop rate.
	*
	* @return int|float
	*/
	public function getAmountFees()
	{
		return $this->getIfSet('fees', $this->data->amount);
	}

	/**
	* Get total amount of refunds.
	*
	* @return int|float
	*/
	public function getAmountRefunds()
	{
		return $this->getIfSet('refunds', $this->data->amount);
	}

	/**
	* Get net total value.
	*
	* @return int|float
	*/
	public function getAmountLiquid()
	{
		return $this->getIfSet('liquid', $this->data->amount);
	}

	/**
	* Get sum of amounts received by other recipients. Used in Marketplaces.
	*
	* @return int|float
	*/
	public function getAmountOtherReceivers()
	{
		return $this->getIfSet('otherReceivers', $this->data->amount);
	}

	/**
	* Get currency used in the application. Possible values: BRL.
	*
	* @return string
	*/
	public function getCurrenty()
	{
		return $this->getIfSet('currency', $this->data->amount);
	}

	/**
	* Get payment method id used in the application.
	*
	* @return string
	*/
	public function getPaymentMethodId()
	{
		return $this->getIfSet('id', $this->data->payment_method);
	}

	/**
	* Get payment method barcode used in the application.
	*
	* @return string
	*/
	public function getPaymentMethodBarcode()
	{
		return $this->getIfSet('barcode', $this->data->payment_method);
	}

	/**
	* Get greight value of the item will be added to the value of the items.
	*
	* @return int|float
	*/
	public function getSubtotalShipping()
	{
		$this->initializeSubtotals();

		return $this->getIfSet('shipping', $this->data->amount->subtotals);
	}

	/**
	* Get Additional value to the item will be added to the value of the items.
	*
	* @return int|float
	*/
	public function getSubtotalAddition()
	{
		$this->initializeSubtotals();

		return $this->getIfSet('addition', $this->data->amount->subtotals);
	}

	/**
	* Get discounted value of the item will be subtracted from the total value of the items.
	*
	* @return int|float
	*/
	public function getSubtotalDiscount()
	{
		$this->initializeSubtotals();

		return $this->getIfSet('discount', $this->data->amount->subtotals);
	}

	/**
	* Get summing the values of all items.
	*
	* @return int|float
	*/
	public function getSubtotalItems()
	{
		$this->initializeSubtotals();

		return $this->getIfSet('items', $this->data->amount->subtotals);
	}

	/**
	* Ger structure item information request.
	*
	* @return \ArrayIterator
	*/
	public function getItemIterator()
	{
		return new ArrayIterator($this->data->items);
	}

	/**
	* Get Buyer associated with the request.
	*
	* @return \Zoop\Resource\Buyer
	*/
	public function getBuyer()
	{
		return $this->data->buyer;
	}

	/**
	* Get zipCode of shippingAddress.
	*
	* @return string
	*/
	public function getShippingAddressZipCode()
	{
		return $this->getIfSet('zipCode', $this->data->shippingAddress);
	}

	/**
	* Get street of shippingAddress.
	*
	* @return string
	*/
	public function getShippingAddressStreet()
	{
		return $this->getIfSet('street', $this->data->shippingAddress);
	}

	/**
	* Get streetNumber of shippingAddress.
	*
	* @return string
	*/
	public function getShippingAddressStreetNumber()
	{
		return $this->getIfSet('streetNumber', $this->data->shippingAddress);
	}

	/**
	* Get complement of shippingAddress.
	*
	* @return string
	*/
	public function getShippingAddressComplement()
	{
		return $this->getIfSet('complement', $this->data->shippingAddress);
	}

	/**
	* Get city of shippingAddress.
	*
	* @return string
	*/
	public function getShippingAddressCity()
	{
		return $this->getIfSet('city', $this->data->shippingAddress);
	}

	/**
	* Get district of shippingAddress.
	*
	* @return string
	*/
	public function getShippingAddressDistrict()
	{
		return $this->getIfSet('district', $this->data->shippingAddress);
	}

	/**
	* Get state of shippingAddress.
	*
	* @return string
	*/
	public function getShippingAddressState()
	{
		return $this->getIfSet('state', $this->data->shippingAddress);
	}

	/**
	* Get country of shippingAddress.
	*
	* @return string
	*/
	public function getShippingAddressCountry()
	{
		return $this->getIfSet('country', $this->data->shippingAddress);
	}

	/**
	* Get payments associated with the request.
	*
	* @return ArrayIterator
	*/
	public function getPaymentIterator()
	{
		return new ArrayIterator($this->data->payments);
	}

	/**
	* Get escrows associated with the request.
	*
	* @return ArrayIterator
	*/
	public function getEscrowIterator()
	{
		return new ArrayIterator($this->data->escrows);
	}

	/**
	* Get refunds associated with the request.
	*
	* @return ArrayIterator
	*/
	public function getRefundIterator()
	{
		return new ArrayIterator($this->data->refunds);
	}

	/**
	* Get entries associated with the request.
	*
	* @return ArrayIterator
	*/
	public function getEntryIterator()
	{
		return new ArrayIterator($this->data->entries);
	}

	/**
	* Get releases associated with the request.
	*
	* @return ArrayIterator
	*/
	public function getEventIterator()
	{
		return new ArrayIterator($this->data->events);
	}

	/**
	* Get recipient structure of payments.
	*
	* @return ArrayIterator
	*/
	public function getReceiverIterator()
	{
		return new ArrayIterator($this->data->receivers);
	}

	/**
	* Get transaction status.
	* Possible values: CREATED, WAITING, PAID, NOT_PAID, REVERTED.
	*
	* @return string
	*/
	public function getStatus()
	{
		return $this->getIfSet('status');
	}

	/**
	* Get date of resource creation.
	*
	* @return \DateTime
	*/
	public function getCreatedAt()
	{
		return $this->getIfSetDateTime('createdAt');
	}

	/**
	* Get updated resource.
	*
	* @return \DateTime
	*/
	public function getUpdatedAt()
	{
		return $this->getIfSetDateTime('updatedAt');
	}

	/**
	* Get checkout preferences of the transaction.
	*
	* @return string
	*/
	public function getCheckoutPreferences()
	{
		return $this->getIfSet('checkoutPreferences');
	}

	/**
	* Create a new Transactions list instance.
	*
	* @return \Zoop\Resource\TransactionsList
	*/
	public function getList(Pagination $pagination = null, Filters $filters = null, $qParam = '')
	{
		$transactionList = new TransactionsList($this->zoop);

		return $transactionList->get($pagination, $filters, $qParam);
	}

	/**
	* Structure of payment.
	*
	* @return \Zoop\Resource\Payment
	*/
	public function payments()
	{
		$payment = new Payment($this->zoop);
		$payment->setTransaction($this);

		return $payment;
	}

	/**
	* Structure of refund.
	*
	* @return \Zoop\Resource\Refund
	*/
	public function refunds()
	{
		$refund = new Refund($this->zoop);
		$refund->setTransaction($this);

		return $refund;
	}

	/**
	* Set additional value to the item will be added to the value of the items.
	*
	* @param int|float $value additional value to the item.
	*
	* @return $this
	*/
	public function setAddition($value)
	{
		if (!isset($this->data->amount->subtotals)) {
			$this->data->amount->subtotals = new stdClass();
		}
		$this->data->amount->subtotals->addition = (float) $value;

		return $this;
	}

	/**
	* Set value to the transaction.
	*
	* @param int|float $amount value to the transaction.
	*
	* @return $this
	*/
	public function setAmount($amount)
	{
		$this->data->amount = (float) $amount;

		return $this;
	}

	/**
	* Set payment type of the transaction.
	*
	* @param string $paymentType payment type of the transaction.
	*
	* @return $this
	*/
	public function setPaymentType($paymentType)
	{
		$this->data->payment_type = $paymentType;

		return $this;
	}

	/**
	* Set seller id associated with the transaction.
	*
	* @param string $id Seller's id.
	*
	* @return $this
	*/
	public function setSellerId($id)
	{
		$this->data->on_behalf_of = $id;

		return $this;
	}

	/**
	* Set boleto expiration date associated with the transaction.
	*
	* @param string $expirationDate Seller's id.
	*
	* @return $this
	*/
	public function setBoletoExpirationDate($expirationDate)
	{
		$this->data->payment_method = new stdClass();
		$this->data->payment_method->expiration_date = $expirationDate;

		return $this;
	}

	/**
	* Set buyer associated with the transaction.
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
	* Set buyer id associated with the transaction.
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

	/**
	* Set card token associated with the transaction.
	*
	* @param \Zoop\Resource\CardToken $cardToken card token associated with the request.
	*
	* @return $this
	*/
	public function setCardToken(CardToken $cardToken)
	{
		$this->data->token = $cardToken->getId();

		return $this;
	}

	public function setCardTokenId($cardTokenId)
	{
		$this->data->token = $cardTokenId;

		return $this;
	}

	/**
	* Set discounted value of the item will be subtracted from the total value of the items.
	*
	* @param int|float $value discounted value.
	*
	* @return $this
	*/
	public function setDiscount($value)
	{
		$this->data->amount->subtotals->discount = (float) $value;

		return $this;
	}

	/**
	* Set discounted value of the item will be subtracted from the total value of the items.
	*
	* @deprecated
	*
	* @param int|float $value discounted value.
	*
	* @return $this
	*/
	public function setDiscont($value)
	{
		$this->setDiscount($value);

		return $this;
	}

	/**
	* Set own request id. external reference.
	*
	* @param string $ownId external reference.
	*
	* @return $this
	*/
	public function setReferenceId($referenceId)
	{
		$this->data->reference_id = $referenceId;

		return $this;
	}

	/**
	* Set shipping Amount.
	*
	* @param float $value shipping Amount.
	*
	* @return $this
	*/
	public function setShippingAmount($value)
	{
		$this->data->amount->subtotals->shipping = (float) $value;

		return $this;
	}

	/**
	* Set URL for redirection in case of success.
	*
	* @param string $urlSuccess UrlSuccess.
	*
	* @return $this
	*/
	public function setUrlSuccess($urlSuccess = '')
	{
		$this->data->checkoutPreferences->redirectUrls->urlSuccess = $urlSuccess;

		return $this;
	}

	/**
	* Set URL for redirection in case of failure.
	*
	* @param string $urlFailure UrlFailure.
	*
	* @return $this
	*/
	public function setUrlFailure($urlFailure = '')
	{
		$this->data->checkoutPreferences->redirectUrls->urlFailure = $urlFailure;

		return $this;
	}

	/**
	* Set installment settings for checkout preferences.
	*
	* @param array $quantity
	* @param int   $discountValue
	* @param int   $additionalValue
	*
	* @return $this
	*/
	public function addInstallmentCheckoutPreferences($quantity, $discountValue = 0, $additionalValue = 0)
	{
		$installmentPreferences = new stdClass();
		$installmentPreferences->quantity = $quantity;
		$installmentPreferences->discount = $discountValue;
		$installmentPreferences->addition = $additionalValue;

		$this->data->checkoutPreferences->installments[] = $installmentPreferences;

		return $this;
	}
}
