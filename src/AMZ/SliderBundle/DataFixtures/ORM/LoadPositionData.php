<?php

namespace AMZ\SliderBundle\DataFixtures\ORM;

use AMZ\SliderBundle\Entity\Item;
use AMZ\SliderBundle\Entity\Position;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPositionData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $position = new Position();
        $position->setName('Trang chủ - Header banner');
        $position->setSlug('homepage-header-banner');
        $manager->persist($position);
        $manager->flush();

        for ($i = 1; $i <= 3; $i++) {
            $item = new Item();
            $item->setPosition($position);
            $item->setFullSizeThumbnail("http://nangtamvocviet.loc/web/upload/images/{$position->getSlug()}-{$i}.jpg");
            $manager->persist($item);
        }
        $manager->flush();

        // Banner for Index footer left
        $position = new Position();
        $position->setName('Trang chủ - Footer - Hình trái lớn');
        $position->setSlug('homepage-footer-left-big-banner');
        $manager->persist($position);
        $manager->flush();

        $item = new Item();
        $item->setPosition($position);
        $item->setLink('http://google.com');
        $item->setFullSizeThumbnail("http://nangtamvocviet.loc/web/upload/images/{$position->getSlug()}.jpg");
        $manager->persist($item);
        $manager->flush();

        // Banner for Index footer top-right
        $position = new Position();
        $position->setName('Trang chủ - Footer - Hình phải trên');
        $position->setSlug('homepage-footer-top-right');
        $manager->persist($position);
        $manager->flush();

        $item = new Item();
        $item->setPosition($position);
        $item->setLink('http://google.com');
        $item->setFullSizeThumbnail("http://nangtamvocviet.loc/web/upload/images/{$position->getSlug()}.jpg");
        $manager->persist($item);
        $manager->flush();

        // Banner for Index footer bottom-right
        $position = new Position();
        $position->setName('Trang chủ - Footer - Hình phải dưới');
        $position->setSlug('homepage-footer-bottom-right');
        $manager->persist($position);
        $manager->flush();

        $item = new Item();
        $item->setPosition($position);
        $item->setLink('http://google.com');
        $item->setFullSizeThumbnail("http://nangtamvocviet.loc/web/upload/images/{$position->getSlug()}.jpg");
        $manager->persist($item);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}