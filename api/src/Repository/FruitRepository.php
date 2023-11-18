<?php

namespace App\Repository;

use App\Entity\Fruit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use App\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Fruit>
 *
 * @method \App\Pagination\Paginator all()
 * @method Fruit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fruit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fruit[]    findAll()
 * @method Fruit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FruitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fruit::class);
    }

    /**
     * @return \App\Paginator\Paginator Returns a paginated results of Fruits[]
     */
    public function all(
        ?int $page = 1,
        ?int $size = 10,
        ?string $orderBy = 'name',
        ?string $direction = 'ASC',
        ?string $search = '',
    ): Paginator {
        $qb = $this->createQueryBuilder('f')
            ->orWhere('f.name LIKE :search')
            ->orWhere('f.family LIKE :search')
            ->orWhere('f.genus LIKE :search')
            ->orWhere('f.fruitOrder LIKE :search')
            ->orWhere('f.source LIKE :search')
            ->orderBy("f.$orderBy", $direction)
            ->setParameter('search', "%$search%");

        return (new Paginator($qb))
            ->setPageSize($size)
            ->paginate($page);
    }

    /**
     * @param string $field The name of the field that you would like to refer to
     * @param string $value The value of the field that you would like to refer to
     *
     * @return \App\Entity\Fruit Returns an instance of Fruit
     */
    public function findByField(string $field, string $value): null|Fruit
    {
        return $this->createQueryBuilder('f')
            ->andWhere("f.{$field} = :val")
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
