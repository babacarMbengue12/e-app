<?php

namespace App\Repository;

use App\Entity\Cart;
use App\Entity\Family;
use App\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cart[]    findAll()
 * @method Cart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    // /**
    //  * @return Cart[] Returns an array of Cart objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    
    public function findByMember(Member $member)
    {
        $ids = [];
        foreach($member->getFamilies() as $item){
            $id =$item->getId();
            $ids[] = $id;
        }
        if(count($ids) === 0) return [];
        
        $query =  $this->createQueryBuilder('c');
        $query->add('where', $query->expr()->in('c.family', $ids));
        $query->andWhere('c.ordered = :val')->setParameter('val', false);
       return $query->getQuery()
        ->getResult();
    }
    public function findByMemberAndStatus(Member $member,$status)
    {
        $ids = [];
        foreach($member->getFamilies() as $item){
            $id =$item->getId();
            $ids[] = $id;
        }
        if(count($ids) === 0) return [];
        
        $query =  $this->createQueryBuilder('c');
        $query->add('where', $query->expr()->in('c.family', $ids));
        return $query->andWhere('c.ordered = :val')->setParameter('val', $status)
        ->getQuery()
        ->getResult();
    }
    
}
