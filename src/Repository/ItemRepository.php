<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }


    public function findByItems():array
    {
        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();
        $query = $qb->select('i', 'c')
            ->from('App\Entity\Item', 'i')
            ->join('i.collection', 'c')
//            ->where('c.id = i.collection')
//            ->join('c.user', 'u')
//            ->where('u.username = :val')
//            ->setParameter('val', $username)
            ->getQuery()
            ->getResult();

        return $query;
    }
     /**
      * @return Item[] Returns an array of Item objects
     */

    public function findItemAndCollection($id): Item
    {
        return $this->createQueryBuilder('i')
            ->select('i', 'c')
            ->join('i.collection', 'c')
            ->where('i.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getSingleResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Item
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
