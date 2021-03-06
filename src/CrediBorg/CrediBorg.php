<?php

namespace CrediBorg;

use Exception;
use Unirest\Request;
use Unirest\Request\Body;
use CrediBorg\Resources\Invoice;
use CrediBorg\Resources\EventPayload;
use CrediBorg\Exceptions\ValidationException;

class CrediBorg
{
    /**
     * API Secret.
     *
     * @var string
     */
    private $secret;

    /**
     * Access Token
     *
     * @var string
     */
    private $token;

    /**
     * Class Constructor
     */
    public function __construct(string $secret, string $token)
    {
        $this->secret = $secret;
        $this->token = $token;
    }

    /**
     * Create Invoice on the CrediBorg Service.
     *
     * @param  Invoice $invoice Invoice object to create.
     * 
     * @return boolean True if successful, False if  not.
     */
    public function createInvoice(Invoice &$invoice): bool
    {
        $response = Request::post(
            $this->endpoint('invoices'),
            $this->get_headers(),
            Body::form($invoice->getBody())
        );

        switch ($response->code) {
            case 201:
                $invoice->setCode($response->body->code);
                $invoice->setId($response->body->id);
                return true;
            case 400:
                throw new ValidationException($response->body->errors);
            default:
                throw new Exception("Request Failed at Destinantion Server with HTTP Code $response->code");
        }
    }

    /**
     * Get Event Payload: Use to process payload sent by CrediBorg servers 
     * as a Web Hook request.
     * 
     * @return EventPayload
     */
    public function getEventPayload(): EventPayload
    {
        return new EventPayload(file_get_contents('php://input'));
    }

    /**
     * Get Global Request Header(s).
     *
     * @return array Headers Key Value Pair.
     */
    private function get_headers(): array
    {
        return [
            'Authorization' => "Secret $this->secret:$this->token",
            'Accept'        => 'application/json'
        ];
    }

    /**
     * Transform End Pont to Full URL.
     *
     * @param  string $endpoint API End Point
     * 
     * @return string Full URL.
     */
    private function endpoint(string $endpoint): string
    {
        return (getenv('LIB_ENV') == 'testing' ?
            'http://localhost:9540/' : 'https://api.cynobit.com/crediborg/v1/')
            . $endpoint;
    }
}
