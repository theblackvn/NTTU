<?php

namespace AMZ\UserBundle\DataFixtures\ORM;

use AMZ\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setFullName('Nguyễn Anh Kiệt');
        $user->setUsername('admin@amzsolution.com');
        $user->setPassword('likipe');
        $user->setRole('ROLE_ADMIN');
        $user->setLocked(false);
        $user->setDeleted(false);
        $user->setAddress('999 NKKN, P8, Q3, HCM');
        $user->setPhone('0902681117');

        $manager->persist($user);
        $manager->flush();

        $user = new User();
        $user->setFullName('Người dùng');
        $user->setUsername('nguoidung');
        $user->setEmail('nguoidung@test.com');
        $user->setPassword('likipe');
        $user->setRole('ROLE_USER');
        $user->setLocked(false);
        $user->setDeleted(false);
        $user->setAddress('999 NKKN, P8, Q3, HCM');
        $user->setPhone('0902681117');

        $manager->persist($user);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}