<?php

declare(strict_types=1);


namespace App\Service;

use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;


class CsvInvoiceParser
{
    private EntityManagerInterface $em;
    private InvoiceRepository $invoiceRepository;

    public function __construct(EntityManagerInterface $em, InvoiceRepository $invoiceRepository)
    {
        $this->em = $em;
        $this->invoiceRepository = $invoiceRepository;
    }

    public function parse(string $fileName): void
    {
        $fileContent = array_map(function($file) {return str_getcsv($file, "\t");}, file($fileName));

        foreach ($fileContent as $item) {
            $this->invoiceRepository->modifyInvoice([
                'montant' => (float)$item[0],
                'currency' => $item[1],
                'nom' => $item[2],
                'date' => \DateTime::createFromFormat('Y-m-d', $item[3]),
            ]);
        }
        $this->em->flush();
    }
}