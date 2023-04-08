<?php

namespace Spygar\FruityBundle\Repository;

use Spygar\FruityBundle\Entity\Fruits;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
/**
 * @extends EntityRepository<Fruits>
 *
 * @method Fruits|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fruits|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fruits[]    findAll()
 * @method Fruits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FruitsRepository extends \Doctrine\ORM\EntityRepository
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($this->entityManager, $this->entityManager->getClassMetadata(Fruits::class));
    }

    public function save(Fruits $entity, bool $flush = false): void
    {
        $this->entityManager->persist($entity);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function remove(Fruits $entity, bool $flush = false): void
    {
        $this->entityManager->remove($entity);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function saveFruit(array $fruitData)
    {
        $fruitInstance = $this->findOneByFruitId($fruitData['id']);
        $fruitInstance = empty($fruitInstance) ? new Fruits() : $fruitInstance;

        $fruitInstance->setFruitId($fruitData['id']);
        $fruitInstance->setGenus($fruitData['genus']);
        $fruitInstance->setName($fruitData['name']);
        $fruitInstance->setFamily($fruitData['family']);
        $fruitInstance->setFruitOrder($fruitData['order']);
        $fruitInstance->setNutritions($fruitData['nutritions']);

        $this->save($fruitInstance, true);
    }

   /**
    * @return Fruits[] Returns an array of Fruits objects
    */
   public function fetchFruits($request): array
   {
        $page = !empty($request->get('page')) ? $request->get('page') : 1;
        $limit = !empty($request->get('limit')) ? $request->get('limit') : 10;
        $families = !empty($request->get('families')) ? $request->get('families') : [];
        $search = !empty($request->get('search')) ? $request->get('search') : [];
      
        $pageSize = ($limit * ($page-1)); 
        
        $qb  = $this->createQueryBuilder('f')
            ->select('f.id, f.genus, f.name, f.family, f.isFavorite as is_favorite')
            ->orderBy('f.id', 'ASC')
            ->setFirstResult($pageSize)
            ->setMaxResults($limit);
            
        if (!empty($families)) {
            $qb->andWhere('f.family IN (:families)')
                ->setParameter('families', $families);
        }

        if (!empty($search)) {
            $qb->andWhere('f.name LIKE :name')
                ->setParameter('name', '%'.$search.'%');
        }

        return  $qb->getQuery()->getResult();
   }

    /**
    * Total results
    */
    public function totalFruits($request): int
    {
         $families = !empty($request->get('families')) ? $request->get('families') : [];
         $search = !empty($request->get('search')) ? $request->get('search') : [];
         
         $qb  = $this->createQueryBuilder('f');
             
         if (!empty($families)) {
             $qb->andWhere('f.family IN (:families)')
                 ->setParameter('families', $families);
         }
 
         if (!empty($search)) {
             $qb->andWhere('f.name LIKE :name')
                 ->setParameter('name', '%'.$search.'%');
         }
 
         return  count($qb->getQuery()->getResult());
    }

   /** Add to favorite */
   public function updateFavoriteStatus(array $data, bool $status)
   {
        $fruitInstance = $this->findOneById($data['fruitId']);
        $fruitInstance->setIsFavorite($status);

        $this->save($fruitInstance, true);
   }
   
   /** Fetch favorite fruites */
   public function fetchFavorite()
   {
        return $this->createQueryBuilder('f')
                    ->select('f.id, f.genus, f.name, f.family, f.isFavorite as is_favorite, f.nutritions')
                    ->orderBy('f.id', 'ASC')
                    ->where('f.isFavorite =:isFavorite')
                    ->setParameter('isFavorite', true)
                    ->getQuery()
                    ->getResult();
   }
//    public function findOneBySomeField($value): ?Fruits
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
