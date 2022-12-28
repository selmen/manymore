<?php

namespace App\Repository;

use App\Entity\Demand;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Demand>
 *
 * @method Demand|null find($id, $lockMode = null, $lockVersion = null)
 * @method Demand|null findOneBy(array $criteria, array $orderBy = null)
 * @method Demand[]    findAll()
 * @method Demand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demand::class);
    }

    public function save(Demand $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Demand $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**     
     *
     * @param User $user
     * @return void
     */
    public function findDemandsByUser(User $user): ?array
    {
        $qb = $this->createQueryBuilder('d');
        $expr = $qb->expr();
        return
            $qb                
                ->where(
                    $expr->eq('d.user', ':currentUser')                    
                )
                ->setParameters([
                    'currentUser' => $user
                ])
                ->orderBy('d.id', 'asc')
                ->groupBy('d.id')                
                ->getQuery()
                ->getResult();
    }
}
