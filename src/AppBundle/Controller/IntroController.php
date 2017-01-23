<?php

namespace AppBundle\Controller;

use AMZ\PostBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IntroController extends Controller
{
    public function indexAction()
    {
        $menu = $this->get('application.service.menu')->getMenu();
        //var_dump($menu);die();
        $category = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Category')
            ->findOneBy(array(
                'isFeature' => 1,
                'slug' => 'gioi-thieu'
            ));
        $childCategory = $category->getChildren();


        $posts = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Post')
            ->get(array(
                'status' => Post::STATUS_PUBLISH,
                'type' => Post::TYPE_POST,
                'is_featured' => 1,
                'category_slug' => 'gioi-thieu',

            ), array(), 3, 0);
        return $this->render('AppBundle:Intro:index.html.twig', array(
            'childCategory' => $childCategory,
            'category' => $category,
            'posts' => $posts,
            'menu' => $menu
        ));
    }





}
