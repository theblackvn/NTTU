<?php

namespace AMZ\PostBundle\Repository;

use AMZ\PostBundle\Entity\Event;
use Doctrine\ORM\QueryBuilder;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventRepository extends \Doctrine\ORM\EntityRepository
{
    private function init($criteria)
    {
        $qb = $this->createQueryBuilder('t');
        if (!empty($criteria['admin_keyword'])) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('t.title', $qb->expr()->literal("%{$criteria['admin_keyword']}%"))
                )
            );
        }
        //var_dump($criteria);die();
        if (!empty($criteria['title'])) {
            $qb->andWhere(
                $qb->expr()->like('t.title', $qb->expr()->literal($criteria['title']))
            );
        }
        if (!empty($criteria['type'])) {
            $qb->andWhere(
                $qb->expr()->like('t.type', $qb->expr()->literal($criteria['type']))
            );
        }
        if (!empty($criteria['slug'])) {
            $qb->andWhere(
                $qb->expr()->like('t.slug', $qb->expr()->literal($criteria['slug']))
            );
        }
        if (isset($criteria['id'])) {
            $qb->andWhere(
                $qb->expr()->eq('t.id', $criteria['id'])
            );
        }
        if (!empty($criteria['category_slug'])) {
            $qb->andWhere(
                $qb->expr()->like('c.slug', $qb->expr()->literal($criteria['category_slug']))
            );
        }
        if (!empty($criteria['category'])) {
            $qb->andWhere(
                $qb->expr()->eq('c.id', $criteria['category'])
            );
        }
        if (isset($criteria['status'])) {
            $qb->andWhere(
                $qb->expr()->eq('t.status', $criteria['status'])
            );
        }
        if (isset($criteria['is_featured'])) {
            $qb->andWhere(
                $qb->expr()->eq('t.isFeatured', $criteria['is_featured'])
            );
        }
        if (isset($criteria['lt_id'])) {
            $qb->andWhere(
                $qb->expr()->lt('t.id', $criteria['lt_id'])
            );
        }

        return $qb;
    }

    public function total(array $criteria)
    {
        $qb = $this->init($criteria);
        $qb->select($qb->expr()->countDistinct('t.id'));
        return $qb->getQuery()->getSingleScalarResult();
    }

    private function order(array $orderBy = null, QueryBuilder &$qb)
    {
        if (!empty($orderBy['id'])) {
            $qb->addOrderBy('t.id', $orderBy['id']);
        }
        if (empty($orderBy)) {
            $qb->orderBy('t.id', 'DESC');
        }
    }

    public function findOneBy(array $criteria, array $orderBy = null)
    {
        $qb = $this->init($criteria);
        $qb->setFirstResult(0)
            ->setMaxResults(1);
        $this->order($orderBy, $qb);
        $qb->groupBy('t.id');
        return $qb->getQuery()->getSingleResult();
    }

    public function get($criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $qb = $this->init($criteria);
        if (isset($limit) && isset($offset)) {
            $qb->setFirstResult($offset)
                ->setMaxResults($limit);
        }
        $this->order($orderBy, $qb);
        $qb->groupBy('t.id');
        return $qb->getQuery()->getResult();
    }

    public function query($criteria, array $orderBy = null)
    {
        $qb = $this->init($criteria);
        $this->order($orderBy, $qb);
        $qb->groupBy('t.id');
        return $qb->getDQL();
    }
}
