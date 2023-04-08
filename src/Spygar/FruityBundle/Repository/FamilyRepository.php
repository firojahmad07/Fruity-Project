<?php

namespace Spygar\FruityBundle\Repository;

use Spygar\FruityBundle\Entity\Family;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends EntityRepository<Family>
 *
 * @method Family|null find($id, $lockMode = null, $lockVersion = null)
 * @method Family|null findOneBy(array $criteria, array $orderBy = null)
 * @method Family[]    findAll()
 * @method Family[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FamilyRepository extends \Doctrine\ORM\EntityRepository
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($this->entityManager, $this->entityManager->getClassMetadata(Family::class));
    }

    public function save(Family $entity, bool $flush = false): void
    {
        $this->entityManager->persist($entity);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function remove(Family $entity, bool $flush = false): void
    {
        $this->entityManager->remove($entity);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function saveFamily(string $familyName)
    {
        $familyInstance = $this->findOneByName($familyName);
        $familyInstance = empty($familyInstance) ? new Family() : $familyInstance;

        $familyInstance->setName($familyName);
        $this->save($familyInstance, true);
    }

    public function fetchFamilies()
    {
        return $this->createQueryBuilder('f')
                    ->select('f.name')
                    ->orderBy('f.id', 'desc')
                    ->getQuery()
                    ->getResult();
    }
}
