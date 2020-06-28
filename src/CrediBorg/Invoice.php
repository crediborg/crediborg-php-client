<?php

namespace CynoBit\CrediBorg;

class Invoice
{
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
    private $customer = [];

    /**
     * Invoice Meta Data.
     *
     * @var array
     */
    private $metaData;

    /**
     * Class Constructor
     *
     * @param float $amount
     */
    public function __construct(float $amount)
    {
        $this->amount = $amount;
    }

    /**
     * Set Invoice Code
     *
     * @param  string $code Invoice Code.
     * 
     * @return Invoice (Method Chaining)
     */
    public function setCode(string $code):Invoice
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
    public function setCustomer(array $customer):Invoice
    {
        $this->customer = array_merge($this->customer, $customer);
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
    public function setMetaData(array $metaData):Invoice
    {
        $this->metaData = $metaData;
        return $this;
    }
}
