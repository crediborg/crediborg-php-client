# crediborg-php-client #

PHP Client for the CrediBorg Service.

CrediBorg is a CREDIT Alert Processing Technology that listens for CREDIT Transaction Emails (Alerts) on your mailbox, processes them by extracting relevant information from it and running it accross customer generated invoices for a match and equally notifying your servers with relevant details via web hooks.

## Installation ##

The CrediBorg PHP Client SDK is available on Packagist as cynobit/crediborg-php-client:

```php
$ composer require cynobit/crediborg-php-client
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
                'first_name' => 'John',
                'last_name'  => 'Doe'
            ])
            ->setMetaData([
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
```