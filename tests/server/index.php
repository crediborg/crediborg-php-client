<?php

require_once __DIR__ . '/../../vendor/autoload.php';

class CrediBorgServerStub
{
    public function checkInvoice(): void
    {
        if (!$this->authenticate()) {
            Flight::json(null, 401);
            return;
        }

        $body = Flight::request()->data;

        if (!$body) Flight::json(null, 400);

        $pass = $body->amount == 500000;

        if ($pass) {
            if (!($body->code ?? null)) {
                $body->code = "STUB_GENERATED";
            }
            Flight::json(array_merge([
                'code' => 'STUB_GENERATED',
                'id'   => 45
            ], json_decode(json_encode($body), true)), 201);
            return;
        }

        Flight::json(null, 500);
    }

    public function returnRequestBody():void
    {
        if (!$this->authenticate()) {
            Flight::json(null, 401);
            return;
        } 
    }

    /**
     * Authenticate
     *
     * @return boolean
     */
    private function authenticate(): bool
    {
        return $this->get_authorization_header() == 'Secret a:b';
    }

    /**
     * Get Authorization Header.
     *
     * @return string|null
     */
    private function get_authorization_header(): ?string
    {
        if (isset($_SERVER['Authorization'])) {
            return trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            return trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();

            // Avoid Surprises.
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));

            if (isset($requestHeaders['Authorization'])) {
                return trim($requestHeaders['Authorization']);
            }
        }
        return null;
    }
}

$stub = new CrediBorgServerStub();

Flight::route('/', function () {
    echo "Welcome to the CrediBorg Test Server.";
});

Flight::route('POST /invoices', [$stub, 'checkInvoice']);
Flight::route('GET /stub/webhook', [$stub, 'getInvoices']);

/**
 * Configurations
 */
Flight::set('flight.log_errors', true);

/**
 * 3, 2, 1 Lift Off!
 */
Flight::start();
