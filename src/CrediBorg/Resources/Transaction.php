<?php

namespace CrediBorg\Resources;

class Transaction
{
    /**
     * Transaction Payload (Used when Transaction object is initialized from a JSON object)
     *
     * @var object
     */
    private $payload;

    /**
     * Class Constructor
     *
     * @param string $json
     */
    public function __construct(?string $json)
    {
        $this->payload = json_decode($json);
    }

    /**
     * Transaction from stdClass.
     *
     * @param  object $transaction
     * 
     * @return \CrediBorg\Transaction
     */
    public static function fromObject(object $transaction): Transaction
    {
        $instance = new Transaction(null);
        $instance->payload = $transaction;
        return $instance;
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
}
