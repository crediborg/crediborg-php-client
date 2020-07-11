<?php

namespace CrediBorg;

use CrediBorg\Exceptions\MalformedEventPayloadException;

class EventPayload
{
    /**
     * Event Object Payload.
     *
     * @var object
     */
    private $payload;

    /**
     * Class Constructor
     *
     * @param string $json
     */
    public function __construct(string $json)
    {
        $this->payload = json_decode($json);

        if (!$this->payload) throw new MalformedEventPayloadException();
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
     * Get Event Payload Type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->payload->type;
    }

    /**
     * Get Transactions
     *
     * @return array
     */
    public function getTransactions(): array
    {
        $transactions = [];

        foreach ($this->payload->transactions ?? [] as $transaction) {
            $transactions[] = new Transaction($transaction);
        }

        return $transactions;
    }

    /**
     * Get UnMatched Invoices
     *
     * @return array
     */
    public function getMatchedInvoices(): array
    {
        $invoices = $ids = [];

        foreach ($this->payload->transactions as $transaction) {
            foreach ($transaction->invoices as $invoice) {
                if (!in_array($invoice->id, $ids)) {
                    $ids[] = $invoice->id;
                    $invoices = Invoice::fromObject($invoice);
                    foreach ($this->get_associated_transactions($invoice->id) as $txs) {
                        $invoices[count($invoices) - 1]->addMatchedTransaction($txs);
                    }
                }
            }
        }

        return $invoices;
    }

    /**
     * Get Transactions Associated with Invoice by ID.
     *
     * @param  integer $invoiceId
     * 
     * @return array
     */
    private function get_associated_transactions(int $invoiceId): array
    {
        $transactions = [];
        foreach ($this->payload->transactions as $transaction) {
            foreach ($transaction->invoices as $invoice) {
                if ($invoice->id == $invoiceId) {
                    $transactions[] = Transaction::fromObject($transaction);
                }
            }
        }
        return $transactions;
    }
}
