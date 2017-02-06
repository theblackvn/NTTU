<?php

namespace AppBundle\Controller;

use AMZ\PostBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EventController extends Controller
{
    public function indexAction()
    {
        $menu = $this->get('application.service.menu')->getMenu();
        $category = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Category')
            ->findOneBy(array(
                'isFeature' => 1,
                'slug' => 'su-kien'
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


    public function detailAction($slug) {

        $post = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Event')
            ->findOneBy(array(
                'isFeature' => 1,
                'slug' => $slug
            ));

        $menu = $this->get('application.service.menu')->getMenu();

        return $this->render('AppBundle:Event:detail.html.twig', array(
            'post' => $post,
            'menu' => $menu
        ));
    }



}
