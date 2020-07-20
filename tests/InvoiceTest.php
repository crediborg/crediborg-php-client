<?php 
declare(strict_types=1);

namespace Tests;

use CrediBorg\Invoice;
use CrediBorg\CrediBorg;
use PHPUnit\Framework\TestCase;


final class InvoiceTest extends TestCase
{
    public function testClientSetFields(): void
    {
        $invoice = new Invoice(5000);

        $this->assertEquals(5000, $invoice->getAmount());

        $crediborg = new CrediBorg('a', 'b');

        $crediborg->createInvoice($invoice);

        $this->assertEquals('STUB_GENERATED', $invoice->getCode());
    }
}
