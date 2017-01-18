<?php

namespace AMZ\UserBundle\Security;

use AMZ\BackendBundle\Service\DBQueryService;
use AMZ\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class WebserviceUserProvider implements UserProviderInterface
{
    private $em;
    private $userSrv;

    public function __construct(EntityManager $manager, DBQueryService $service)
    {
        $this->em = $manager;
        $this->userSrv = $service->getRepository('AMZUserBundle:User');
    }

    public function loadUserByUsername($username)
    {
        $user = $this->userSrv
            ->findOneBy(array('username' => $username));
        if (!empty($user)) {
            $deleted = $user->getDeleted();
            if (1 == $deleted) {
                throw new BadCredentialsException('Tài khoản đã bị xóa');
            }
            $locked = $user->getLocked();
            if (1 == $locked) {
                throw new BadCredentialsException('Tài khoản đang bị khóa');
            }
            return $user;
        }
        throw new BadCredentialsException('Tài khoản không tồn tại');
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException('core_application.security.user_instances_not_support');
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'AMZ\UserBundle\Entity\User';
    }
}