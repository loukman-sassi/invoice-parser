<?php

declare(strict_types=1);

namespace App\Tests;

use App\Repository\InvoiceRepository;
use App\Service\CsvInvoiceParser;
use App\Service\InvoiceParser;
use App\Service\JsonInvoiceParser;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class InvoiceParserTest extends KernelTestCase
{
    private $entityManager;
    private $invoiceRepository;

    public function testParseJson(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->invoiceRepository = $this->createMock(InvoiceRepository::class);
        $this->invoiceRepository
            ->expects($this->atLeastOnce())
            ->method('modifyInvoice');

        $invoiceParser = new JsonInvoiceParser($this->entityManager, $this->invoiceRepository);


        $invoiceParser->parse('data/invoices.json');
    }

    public function testParseCsv(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->invoiceRepository = $this->createMock(InvoiceRepository::class);
        $this->invoiceRepository
            ->expects($this->atLeastOnce())
            ->method('modifyInvoice');

        $invoiceParser = new CsvInvoiceParser($this->entityManager, $this->invoiceRepository);

        $invoiceParser->parse('data/invoices.csv');
    }

}