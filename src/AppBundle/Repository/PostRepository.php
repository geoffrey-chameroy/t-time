<?php

namespace AppBundle\Repository;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAll()
    {
        return $this->createQueryBuilder('p')
            ->where('p.deletedAt is null')
            ->addOrderBy('p.id', 'DESC')
            ->getQuery()->getResult();
    }

    public function findPublished($page = 1, $maxResult = 10, $category = null)
    {
        $first = ($page - 1) * ($maxResult - 1);
        
        $now = new \DateTime();
        $qb = $this->createQueryBuilder('p')
            ->where('p.publishedAt <= :now')
                ->setParameter('now', $now->format('Y-m-d H:i:s'))
            ->andWhere('p.deletedAt is null');
        if (null !== $category) {
            $qb->andWhere('p.category = :category')
                ->setParameter('category', $category);
        }
        return $qb->orderBy('p.publishedAt', 'DESC')
            ->addOrderBy('p.id', 'DESC')
            ->setFirstResult($first)
            ->setMaxResults($maxResult)
            ->getQuery()->getResult();
    }
    
    public function findOnePublished($id)
    {
        $now = new \DateTime();
        return $this->createQueryBuilder('p')
            ->where('p.publishedAt <= :now')
                ->setParameter('now', $now->format('Y-m-d H:i:s'))
            ->andWhere('p.id = :id')
                ->setParameter('id', $id)
            ->andWhere('p.deletedAt is null')
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }

    public function findOnePublishedBySlug($slug)
    {
        $now = new \DateTime();
        return $this->createQueryBuilder('p')
            ->where('p.publishedAt <= :now')
                ->setParameter('now', $now->format('Y-m-d H:i:s'))
            ->andWhere('p.slug = :slug')
                ->setParameter('slug', $slug)
            ->andWhere('p.deletedAt is null')
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }
}