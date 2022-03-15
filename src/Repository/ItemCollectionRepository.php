<?php

namespace App\Repository;

use App\Entity\ItemCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method ItemCollection|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemCollection|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemCollection[]    findAll()
 * @method ItemCollection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemCollectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemCollection::class);
    }

    public function findAll1(){

        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT item_collection, user
                FROM APP\Entity\ItemCollection item_collection
                JOIN item_collection.user user');
        return $query->getResult();

    }

    public function findByItem()
    {

        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT c, i
                FROM APP\Entity\ItemCollection c
                JOIN c.items i');
        return $query->getResult();

    }

    public function findCountCollection()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT count(c)
                FROM APP\Entity\ItemCollection c');
        return $query->getSingleResult();
    }



    public function findByItems()
    {
        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();
        $query = $qb->select('c', 'i')
            ->from('App\Entity\ItemCollection', 'c')
            ->join('c.items', 'i')

            ->getQuery()
            ->getResult();

        return $query;
    }
    public function findByTest($id)
    {
        return $this->createQueryBuilder('c')
            ->select('c', 'i')
            ->from('APP\Entity\Item', 'i')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
            ;
    }


     /**
      * @return ItemCollection[] Returns an array of ItemCollection objects
      */

    public function findByUsername($username):array
    {
        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();
        $query = $qb->select('i', 'u')
            ->from('App\Entity\ItemCollection', 'i')
            ->join('i.user', 'u')
            ->where('u.username = :val')
            ->setParameter('val', $username)
            ->getQuery()
            ->getResult();

        return $query;
    }




    // /**
    //  * @return ItemCollection[] Returns an array of ItemCollection objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ItemCollection
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
