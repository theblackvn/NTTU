<?php

namespace AppBundle\Controller;

use AMZ\PostBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $headerBanners = $this->get('amz_db.service.query')
            ->getRepository('AMZSliderBundle:Item')
            ->get(array(
                'position-slug' => 'homepage-header-banner'
            ));
        $bannerFooterLeft = $this->get('amz_db.service.query')
            ->getRepository('AMZSliderBundle:Item')
            ->findOneBy(array(
                'position-slug' => 'homepage-footer-left-big-banner'
            ), array('id' => 'DESC'));
        $bannerFooterTopRight = $this->get('amz_db.service.query')
            ->getRepository('AMZSliderBundle:Item')
            ->findOneBy(array(
                'position-slug' => 'homepage-footer-top-right'
            ), array('id' => 'DESC'));
        $bannerFooterBottomRight = $this->get('amz_db.service.query')
            ->getRepository('AMZSliderBundle:Item')
            ->findOneBy(array(
                'position-slug' => 'homepage-footer-bottom-right'
            ), array('id' => 'DESC'));
        $itNews = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Post')
            ->get(array(
                'status' => Post::STATUS_PUBLISH,
                'type' => Post::TYPE_POST,
                'is_featured' => 1,
                'category_slug' => 'khoa-cong-nghe-thong-tin',
            ), array('created','DESC'), 3, 0);

        $tuyenSinhNews = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Post')
            ->get(array(
                'status' => Post::STATUS_PUBLISH,
                'type' => Post::TYPE_POST,
                'is_featured' => 1,
                'category_slug' => 'tuyen-sinh',

            ), array('created', 'DESC'), 3, 0);

        $hotNewsTruyenThong = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Post')
            ->get(array(
                'status' => Post::STATUS_PUBLISH,
                'type' => Post::TYPE_POST,
                'is_featured' => 1,
                'category_slug' => 'truyen-thong-va-tuyen-truyen',

            ), array(), 3, 0);

        $hotNewsVanBan = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Post')
            ->get(array(
                'status' => Post::STATUS_PUBLISH,
                'type' => Post::TYPE_POST,
                'is_featured' => 1,
                'category_slug' => 'he-thong-van-ban',

            ), array(), 3, 0);

        $kyThuatCan = $this->get('amz_db.service.query')->getRepository("AMZPostBundle:Post")
            ->get(array(
                'status' => Post::STATUS_PUBLISH,
                'type' => Post::TYPE_PAGE,
                'slug' => 'ky-thuat-can',
            ));
        $kyThuatDoChieuCao = $this->get('amz_db.service.query')->getRepository("AMZPostBundle:Post")
            ->get(array(
                'status' => Post::STATUS_PUBLISH,
                'type' => Post::TYPE_PAGE,
                'slug' => 'ky-thuat-do-chieu-cao',
            ));

        return $this->render('AppBundle:Default:index.html.twig', array(
            'headerBanners' => $headerBanners,
            'itNews' => $itNews,
            'bannerFooterLeft' => $bannerFooterLeft,
            'bannerFooterTopRight' => $bannerFooterTopRight,
            'bannerFooterBottomRight' => $bannerFooterBottomRight,
            'tuyenSinhNews' => $tuyenSinhNews,
            'hotNewsTruyenThong' => $hotNewsTruyenThong,
            'hotNewsVanBan' => $hotNewsVanBan,
            'kyThuatCan' => $kyThuatCan,
            'kyThuatDoChieuCao' => $kyThuatDoChieuCao
        ));
    }
		
    public function aboutAction(){
        return $this->render('AppBundle:Default:about-us.html.twig');
    }

    public  function  consultantAction() {
        return $this->render('AppBundle:Default:consultant-list.html.twig');
    }

}
