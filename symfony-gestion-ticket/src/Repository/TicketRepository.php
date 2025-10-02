<?php
// src/Repository/TicketRepository.php
namespace App\Repository;

use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    public function findAllOrderedByDate()
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.openDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}