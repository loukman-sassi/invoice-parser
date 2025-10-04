<?php

declare(strict_types=1);


namespace App\Service;

use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;


class JsonInvoiceParser
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
        $fileContent = file_get_contents($fileName);
        $json = json_decode($fileContent, true);

        foreach ($json as $item) {
            $this->invoiceRepository->modifyInvoice([
                'montant' => (float)$item['montant'],
                'currency' => $item['devise'],
                'nom' => $item['nom'],
                'date' => \DateTime::createFromFormat('Y-m-d', $item['date']),
            ]);
        }
        $this->em->flush();
    }
}