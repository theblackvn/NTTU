<?php

namespace AMZ\UserBundle\Service;

use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use AMZ\UserBundle\Form\User\UserLang1Type;
use AMZ\UserBundle\Form\User\UserLang2Type;
use AMZ\UserBundle\Form\User\UserLang3Type;

class UserService
{
    private $em;
    private $repository;
    private $paginator;

    public function __construct(EntityManager $entityManager, Paginator $paginator)
    {
        $this->em = $entityManager;
        $this->repository = $this->em->getRepository('AMZUserBundle:User');
        $this->paginator = $paginator;
    }

    public function find($id)
    {
        try {
            return $this->repository->find($id);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function findOneBy($criteria, $orderBy = array())
    {
        try {
            return $this->repository->findOneBy($criteria, $orderBy);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function insert($entity)
    {
        try {
            $this->em->persist($entity);
            $this->em->flush();
            return $entity;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function update()
    {
        try {
            $this->em->flush();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function remove($entity)
    {
        try {
            $this->em->remove($entity);
            $this->em->flush();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function get($criteria)
    {
        try {
            $result = $this->repository->get($criteria);
            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function query($criteria)
    {
        try {
            $dql = $this->repository->query($criteria);
            return $this->em->createQuery($dql);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function paging($criteria, $page, $limit)
    {
        try {
            return $this->paginator->paginate(
                $this->query($criteria),
                $page,
                $limit
            );
        } catch (\Exception $e) {
            return null;
        }
    }

    public function initForm($lang)
    {
        if (3 == $lang) {
            return UserLang3Type::class;
        } elseif (2 == $lang) {
            return UserLang2Type::class;
        } else {
            return UserLang1Type::class;
        }
    }
}