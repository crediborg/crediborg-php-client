<?php

namespace CrediBorg;

use stdClass;

class Invoice
{
    /**
     * Invoice Payload. Contains All Invoice Fields.
     *
     * @var object
     */
    private $data;

    /**
     * Invoice ID
     *
     * @var int
     */
    private $id;

    /**
     * Associative Array for Customer Details.
     *
     * @var array
     */
    private $customerData = [];

    /**
     * Matched Transactions.
     *
     * @var array
     */
    private $matchedTransactions = [];

    /**
     * Class Constructor
     *
     * @param int $amount
     */
    public function __construct(int $amount = 0)
    {
        $this->data = new stdClass();

        $this->data->amount = $amount;
    }

    /**
     * Invoice from JSON String.
     *
     * @param  string|null $json
     * @return Invoice
     */
    public function fromJson(?string $json): Invoice
    {
        $this->data = json_decode($json);

        if ($this->data->first_name) $this->customerData['first_name'] = $this->data->first_name;
        if ($this->data->middle_name) $this->customerData['middle_name'] = $this->data->middle_name;
        if ($this->data->last_name) $this->customerData['last_name'] = $this->data->last_name;

        return $this;
    }

    /**
     * Invoice from stdClass
     *
     * @param  object $invoice
     * @return Invoice
     */
    public static function fromObject(object $invoice): Invoice
    {
        $instance = new static();
        $instance->data = $invoice;

        if ($instance->data->first_name) $instance->customerData['first_name'] = $instance->data->first_name;
        if ($instance->data->middle_name) $instance->customerData['middle_name'] = $instance->data->middle_name;
        if ($instance->data->last_name) $instance->customerData['last_name'] = $instance->data->last_name;

        return $instance;
    }

    /**
     * Set Invoice Code
     *
     * @param  string $code Invoice Code.
     * 
     * @return Invoice (Method Chaining)
     */
    public function setCode(string $code): Invoice
    {
        $this->data->code = $code;
        return $this;
    }

    /**
     * Set Customer Details
     *
     * @param  array $customer Associative array of customer details.
     *                         array_merge is used internally to set the details.
     *                         This means you can set customer details with 
     *                         multiple calls to this function.
     * 
     * @return Invoice (Method Chaining)
     */
    public function setCustomer(array $customer): Invoice
    {
        $this->customerData = array_merge($this->customerData, $customer);
        return $this;
    }

    /**
     * Set Customer Email.
     *
     * @param  string $email
     * @return Invoice
     */
    public function setEmail(string $email): Invoice
    {
        $this->data->email;
        return $this;
    }

    /**
     * Get Customer Data Array.
     *
     * @param string $field
     * @return void
     */
    public function getCustomerData(string $field = null)
    {
        if (!$field) return $this->customerData;

        return $this->customerData[$field] ?? null;
    }

    /**
     * Set Invoice MetaData
     *
     * @param  array $metaData Meta Data of Invoice. You will receive the metadata
     *                         as part of the request body for the request that triggers 
     *                         your transactions webhook.
     * 
     * @return Invoice (Method Chaining)
     */
    public function setMetaData(array $metaData): Invoice
    {
        $this->data->metadata = $metaData;
        return $this;
    }

    /**
     * Customer ID
     *
     * @param integer $customerId Customer ID
     * 
     * @return Invoice (Method Chaining)
     */
    public function setCustomerId(int $customerId): Invoice
    {
        $this->data->customer_id = $customerId;
        return $this;
    }

    /**
     * Get Invoice ID.
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->data->id;
    }

    /**
     * Get Invoice Code.
     *
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->data->code ?? null;
    }

    /**
     * Set Invoice ID.
     *
     * @param  integer $id
     * @return Invoice
     */
    public function setId(int $id): Invoice
    {
        $this->data->id = $id;
        return $this;
    }

    /**
     * Get Invoice Amount.
     *
     * @return float Invoice Amount
     */
    public function getAmount(): float
    {
        return $this->data->amount;
    }

    /**
     * Magic Getter Method.
     *
     * @param  string $name
     * @return void
     */
    public function __get($name)
    {
        return $this->data->$name ?? null;
    }

    /**
     * Add Matched Transaction.
     *
     * @param  \CrediBorg\Transaction $transaction
     * 
     * @return void
     */
    public function addMatchedTransaction(Transaction $transaction): void
    {
        $this->matchedTransactions[] = $transaction;
    }

    /**
     * Get Matched Transactions
     *
     * @return array
     */
    public function getMatchedTransactions(): array
    {
        return $this->matchedTransactions;
    }

    /**
     * Invoice Request Body.
     *
     * @return array
     */
    public function getBody(): array
    {
        $body = ['amount' => $this->data->amount * 100];

        if ($this->data->code ?? null)  $body['code'] = $this->data->code;
        if ($this->data->metadata ?? null) $body['metadata'] = json_encode($this->data->metaData);
        if ($this->data->email ?? null) $body['email'] = $this->data->email;
        if ($this->data->customer_id ?? null) $body['customer_id'] = $this->data->customer_id;

        if ($this->customerData['first_name'] ?? null) $body['first_name'] = $this->customerData['first_name'];
        if ($this->customerData['middle_name'] ?? null) $body['middle_name'] = $this->customerData['middle_name'];
        if ($this->customerData['last_name'] ?? null) $body['last_name'] = $this->customerData['last_name'];

        return $body;
    }
}
