![build](https://github.com/crediborg/crediborg-php-client/workflows/build/badge.svg)

# crediborg-php-client #

PHP Client for the CrediBorg Service.

CrediBorg is a CREDIT Alert Processing Technology that listens for CREDIT Transaction Emails (Alerts) on your mailbox, processes them by extracting relevant information from it and running it accross customer generated invoices for a match and equally notifying your servers with relevant details via web hooks.

This is a PHP based Client Library to consume the Service (CrediBorg) APIs.

Go to <a href="http://crediborg.cynobit-app.com/developers/doc" target="_blank"/> for the full REST API documentation.

## Installation ##

The CrediBorg PHP Client SDK is available on Packagist as cynobit/crediborg-client:

```php
$ composer require cynobit/crediborg-client
```

## Usage ##

### Create Invoice ###
```php
$amount = 75000;

$secret = ".....";
$token = "...";

$invoice = new CrediBorg\Invoice($amount);

$invoice->setCode('AHYT645623')
    ->setEmail('example@example.com');
    ->setCustomer([
        'first_name'  => 'John',
        'middle_name' => 'Alfred',
        'last_name'   => 'Doe'
    ])
    ->setMetaData([
        // Possible cart items or anything you want.
        'items' => [
            [
                'name'       => 'Raspberry Pi'
                'qty'        => 2,
                'unit_price' => 25000
            ],
            [
                'name'       => 'ESP32 Module'
                'qty'        => 20,
                'unit_price' => 2000
            ]
        ]
    ]);
$crediborg = new CrediBorg\CrediBorg($secret, $token);

$crediborg->createInvoice($invoice);

echo $invoice->getCode(); // Invoice Code
```

### Handle Event Payload at Configured WebHook ###
```php
$secret = ".....";
$token = "...";

$crediborg = new CrediBorg\CrediBorg($secret, $token);

$event = $crediborg->getEventPayload();

foreach ($event->getMatchedInvoices() as $invoice) {
    echo $invoice->code . PHP_EOL;
    foreach ($invoice->getMatchedTransactions() as $transaction) {
        echo $transaction->narration . PHP_EOL;
        echo $transaction->type . PHP_EOL;
        echo $transaction->amount . PHP_EOL;
    }
}
```