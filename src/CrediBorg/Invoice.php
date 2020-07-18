<?php

namespace CrediBorg;

class Invoice
{
    /**
     * Invoice Payload (Used when Invoice object is initialized from a JSON object)
     *
     * @var object
     */
    private $payload;

    /**
     * Invoice ID
     *
     * @var int
     */
    private $id;
    /**
     * Amount Payable for this Invoice.
     *
     * @var float
     */
    private $amount;

    /**
     * Invoice Code.
     *
     * @var string
     */
    private $code;

    /**
     * Associative Array for Customer Details.
     *
     * @var array
     */
    private $customerData = [];

    /**
     * Customer's Email
     *
     * @var string
     */
    private $email;

    /**
     * Invoice Meta Data.
     *
     * @var array
     */
    private $metaData;

    /**
     * Customer ID
     *
     * @var int
     */
    private $customerId;

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
        $this->amount = $amount;
    }

    /**
     * Invoice from JSON String.
     *
     * @param  string|null $json
     * @return Invoice
     */
    public function fromJson(?string $json): Invoice
    {
        $this->payload = json_decode($json);
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
        $instance = new Invoice();
        $instance->payload = $invoice;
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
        $this->code = $code;
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
        $this->metaData = $metaData;
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
        $this->customerId = $customerId;
        return $this;
    }

    /**
     * Get Invoice ID.
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get Invoice Code.
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Set Invoice ID.
     *
     * @param  integer $id
     * @return Invoice
     */
    public function setId(int $id): Invoice
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get Invoice Amount.
     *
     * @return float Invoice Amount
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Magic Getter Method.
     *
     * @param  string $name
     * @return void
     */
    public function __get($name)
    {
        return $this->payload->$name ?? null;
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
        $body = ['amount' => $this->amount * 100];

        if ($this->code)  $body['code'] = $this->code;
        if ($this->metaData) $body['metadata'] = json_encode($this->metaData);
        if ($this->email) $body['email'] = $this->email;
        if ($this->customerId) $body['customer_id'] = $this->customerId;

        if ($this->customerData['first_name'] ?? null) $body['first_name'] = $this->customerData['first_name'];
        if ($this->customerData['middle_name'] ?? null) $body['middle_name'] = $this->customerData['middle_name'];
        if ($this->customerData['last_name'] ?? null) $body['last_name'] = $this->customerData['last_name'];

        return $body;
    }
}
