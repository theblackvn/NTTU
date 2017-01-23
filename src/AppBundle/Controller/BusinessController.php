<?php

namespace AppBundle\Controller;

use AMZ\PostBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BusinessController extends Controller
{
    public function indexAction()
    {
        $menu = $this->get('application.service.menu')->getMenu();
        $category = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Category')
            ->findOneBy(array(
                'isFeature' => 1,
                'slug' => 'hop-tac-doanh-nghiep'
            ));
        $childCategory = $category->getChildren();


        $posts = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Post')
            ->get(array(
                'status' => Post::STATUS_PUBLISH,
                'type' => Post::TYPE_POST,
                'is_featured' => 1,
                'category_slug' => 'hop-tac-doanh-nghiep',

            ), array(), 3, 0);
        return $this->render('AppBundle:Business:index.html.twig', array(
            'childCategory' => $childCategory,
            'category' => $category,
            'posts' => $posts,
            'menu' => $menu
        ));
    }
		
    public function aboutAction(){
        return $this->render('AppBundle:Default:about-us.html.twig');
    }

    public function pageAction($slug) {
        $category = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Category')
            ->findOneBy(array(
                'isFeature' => 1,
                'slug' => $slug
            ));
        $posts = array();
        if (!empty($category)) {
            $posts = $this->get('amz_db.service.query')
                ->getRepository('AMZPostBundle:Research')
                ->get(array(
                    'status' => Post::STATUS_PUBLISH,
                    'type' => Post::TYPE_POST,
                    'is_featured' => 1,
                    'category_slug' => $slug,

                ), array(), 3, 0);

        }
        $menuDaoTao = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Category')
            ->findOneBy(array(
                'isFeature' => 1,
                'slug' => 'dao-tao'
            ));
        $menuTuyenSinh = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Category')
            ->findOneBy(array(
                'isFeature' => 1,
                'slug' => 'tuyen-sinh'
            ));
        $menuNghienCuu = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Category')
            ->findOneBy(array(
                'isFeature' => 1,
                'slug' => 'nghien-cuu'
            ));
        return $this->render('AppBundle:Admission:page.html.twig', array(
            'posts' => $posts,
            'category' => $category,
            'menuDaoTao' => $menuDaoTao,
            'menuTuyenSinh' => $menuTuyenSinh,
            'menuNghienCuu' => $menuNghienCuu
        ));
    }



}
