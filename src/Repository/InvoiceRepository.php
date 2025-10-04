<?php

declare(strict_types=1);


namespace App\Repository;

use App\Entity\Invoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class InvoiceRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Invoice::class);
        $this->em = $em;
    }

    public function modifyInvoice(array $data): void
    {
        $invoice =  $this->findOneBy([
            'amount' => $data['montant'],
            'date' => $data['date'],
            'name' => $data['nom'],
            'currency' => $data['currency'],
        ]);
        if (!$invoice instanceof Invoice) {
            $invoice = new Invoice();
            $invoice->setAmount($data['montant']);
            $invoice->setName($data['nom']);
            $invoice->setCurrency($data['currency']);
            $invoice->setDate($data['date']);

            $this->em->persist($invoice);

        }
    }
}
