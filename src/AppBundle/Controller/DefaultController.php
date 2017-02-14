<?php

namespace AppBundle\Controller;

use AMZ\PostBundle\Entity\Post;
use AMZ\PostBundle\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
//        $headerBanners = $this->get('amz_db.service.query')
//            ->getRepository('AMZSliderBundle:Item')
//            ->get(array(
//                'position-slug' => 'homepage-header-banner'
//            ));
//        $bannerFooterLeft = $this->get('amz_db.service.query')
//            ->getRepository('AMZSliderBundle:Item')
//            ->findOneBy(array(
//                'position-slug' => 'homepage-footer-left-big-banner'
//            ), array('id' => 'DESC'));
//        $bannerFooterTopRight = $this->get('amz_db.service.query')
//            ->getRepository('AMZSliderBundle:Item')
//            ->findOneBy(array(
//                'position-slug' => 'homepage-footer-top-right'
//            ), array('id' => 'DESC'));
//        $bannerFooterBottomRight = $this->get('amz_db.service.query')
//            ->getRepository('AMZSliderBundle:Item')
//            ->findOneBy(array(
//                'position-slug' => 'homepage-footer-bottom-right'
//            ), array('id' => 'DESC'));
        $menu = $this->get('application.service.menu')->getMenu();
        $events = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Event')
            ->get(array(
                'status' => Event::STATUS_PUBLISH,
                'is_featured' => 1,
            ), array('created','DESC'), 3, 0);
        //var_dump($events);die();
        $itNews = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Post')
            ->get(array(
                'status' => Post::STATUS_PUBLISH,
                'type' => Post::TYPE_POST,
                'is_featured' => 1,
                'category_slug' => 'tin-tuc',
            ), array('created','DESC'), 3, 0);

        $tuyenSinhNews = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Post')
            ->get(array(
                'status' => Post::STATUS_PUBLISH,
                'type' => Post::TYPE_POST,
                'is_featured' => 1,
                'category_slug' => 'tin-tuc',

            ), array('created', 'DESC'), 3, 0);

        $hotHopTacDN = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Post')
            ->findOneBy(array(
                'status' => Post::STATUS_PUBLISH,
                'type' => Post::TYPE_POST,
                'is_featured' => 1,
                'category_slug' => 'hop-tac-doanh-nghiep',

            ), array(), 1, 0);

        $hotMoiTruongHT = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Post')
            ->findOneBy(array(
                'status' => Post::STATUS_PUBLISH,
                'type' => Post::TYPE_POST,
                'is_featured' => 1,
                'category_slug' => 'moi-truong-hoc-tap',

            ), array(), 1, 0);

        $fiveReason = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Post')
            ->get(array(
                'status' => Post::STATUS_PUBLISH,
                'type' => Post::TYPE_POST,
                'is_featured' => 1,
                'category_slug' => '5-ly-do',

            ), array(), 5, 0);
        $sliders = $this->get('amz_db.service.query')
            ->getRepository('AMZSliderBundle:Item')
            ->get(array(),array('sort-order' => 'ASC'));
        //echo count($sliders);die();

        $media = $this->get('amz_db.service.query')
            ->getRepository('AMZSliderBundle:Media')
            ->findOneBy(array(),array('sort-order' => 'ASC'));

        return $this->render('AppBundle:Default:index.html.twig', array(
            'itNews' => $itNews,
            'tuyenSinhNews' => $tuyenSinhNews,
            'hotHopTacDN' => $hotHopTacDN,
            'hotMoiTruongHT' => $hotMoiTruongHT,
            'fiveReason' => $fiveReason,
            'menu' => $menu,
            'events' => $events,
            'sliders' => $sliders,
            'media' => $media
        ));
    }
		
    public function aboutAction(){
        return $this->render('AppBundle:Default:about-us.html.twig');
    }

    public  function  consultantAction() {
        return $this->render('AppBundle:Default:consultant-list.html.twig');
    }

}
