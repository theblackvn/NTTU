<?php

namespace AMZ\PostBundle\DataFixtures\ORM;

use AMZ\PostBundle\Entity\Category;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCategoryData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
//        $category = new Category();
//        $category->setTitle('Tình trạng dinh dưỡng');
//        $category->setSlug('tinh-trang-dinh-duong');
//        $category->setIsFeatured(false);
//        $manager->persist($category);
//        $manager->flush();
//
//        $category = new Category();
//        $category->setTitle('Kỹ thuật cân đo');
//        $category->setSlug('ky-thuat-can-do');
//        $category->setIsFeatured(false);
//        $manager->persist($category);
//        $manager->flush();

        $category = new Category();
        $category->setTitle('Bản tin');
        $category->setSlug('ban-tin');
        $category->setIsFeatured(false);
        $manager->persist($category);
        $manager->flush();

        $category = new Category();
        $category->setTitle('Cẩm nang dinh dưỡng');
        $category->setSlug('cam-nang-dinh-duong');
        $category->setIsFeatured(false);
        $manager->persist($category);
        $manager->flush();

        $category = new Category();
        $category->setTitle('Đề án 641');
        $category->setSlug('de-an-641');
        $category->setIsFeatured(true);
        $manager->persist($category);
        $manager->flush();

        $category = new Category();
        $category->setTitle('Truyền thông và tuyên truyền');
        $category->setSlug('truyen-thong-va-tuyen-truyen');
        $category->setIsFeatured(true);
        $manager->persist($category);
        $manager->flush();

        $category = new Category();
        $category->setTitle('Hệ thống văn bản');
        $category->setSlug('he-thong-van-ban');
        $category->setIsFeatured(true);
        $manager->persist($category);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}