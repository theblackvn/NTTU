<?php

namespace AMZ\BackendBundle\Service;

use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use AMZ\BackendBundle\Entity\Log;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;


class DBQueryService
{
    private $em;
    private $repository;
    private $paginator;
    private $tokenStorage;

    public function __construct(EntityManager $entityManager, Paginator $paginator, TokenStorage $tokenStorage)
    {
        $this->em = $entityManager;
        $this->paginator = $paginator;
        $this->tokenStorage = $tokenStorage;
    }

    public function getRepository($entityName)
    {
        $this->repository = $this->em->getRepository($entityName);
        return $this;
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
            //$this->addLog($entity, 'Create');
            return $entity;
        } catch (\Exception $e) {
            var_dump($e->getMessage());die();
            return false;
        }
    }

    public function update($entity)
    {
        try {
            //$this->addLog($entity, 'Update');
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
            //$this->addLog($entity, 'Remove');
            $this->em->flush();
            return $entity;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function get($criteria = array(), $orderBy = array(), $limit = null, $offset = null)
    {
        try {
            $result = $this->repository->get($criteria, $orderBy, $limit, $offset);
            return $result;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function query($criteria, $orderBy = null)
    {
        try {
            $dql = $this->repository->query($criteria, $orderBy);
            return $this->em->createQuery($dql);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function total($criteria) {
        try {
            $result = $this->repository->total($criteria);
            return $result;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function paging($criteria, $page, $limit, array $orderBy = null)
    {
        try {
            return $this->paginator->paginate(
                $this->query($criteria, $orderBy),
                $page,
                $limit
            );
        } catch (\Exception $e) {
            return null;
        }
    }

    private function addLog($entity, $event='Update')
    {
        try{
            $user = $this->tokenStorage->getToken()->getUser();
            $entityName = $this->em->getClassMetadata(get_class($entity))->getName();
            if ($event == 'Update') {
                $uow = $this->em->getUnitOfWork();
                $uow->computeChangeSets();
                $aChangeSet = $uow->getEntityChangeSet($entity);
                //echo "<pre>";var_dump($aChangeSet);die();
                if (!empty($aChangeSet)) {
                    foreach ($aChangeSet as $field => $value) {
                        $temp = new Log();
                        $temp->setAccessIp($_SERVER["REMOTE_ADDR"]);
                        $temp->setAccessTime(new \DateTime('now'));
                        $temp->setEvent($event);
                        $temp->setField($field);
                        $temp->setEntity($entityName);
                        $temp->setUser($user);
                        if (is_a($value[0],'DateTime')) {
                            $temp->setOldValue($value[0]->format('Y-m-d h:i:s'));
                        } else $temp->setOldValue($value[0]);
                        if (is_a($value[1],'DateTime')) {
                            $temp->setNewValue($value[1]->format('Y-m-d h:i:s'));
                        } else $temp->setNewValue($value[1]);
                        $temp->setEntityId($entity->getId());
                        $this->em->persist($temp);
                    }
                }
            } else { // Create or Remove
                $temp = new Log();
                $temp->setAccessIp($_SERVER["REMOTE_ADDR"]);
                $temp->setAccessTime(new \DateTime('now'));
                $temp->setEvent($event);
                $temp->setEntity($entityName);
                $temp->setEntityId($entity->getId());
                $temp->setUser($user);
                $this->em->persist($temp);
                if ($event == 'Create') $this->em->flush($temp);
            }
            return $entity;
        } catch (\Exception $e){
            return NULL;
        }

    }
}