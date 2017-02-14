<?php

namespace AppBundle\Controller;

use AMZ\PostBundle\Entity\Post;
use AMZ\PostBundle\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    public function postAction($slug)
    {
        $page = $this->get('amz_db.service.query')->getRepository("AMZPostBundle:Post")
            ->findOneBy(array(
               'slug' => $slug
            ));
        return $this->render('@App/Page/page-detail.html.twig', compact('page'));
    }
    public function indexAction($slug) {
        $category = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Category')
            ->findOneBy(array(
                'isFeature' => 1,
                'slug' => $slug
            ));
        $menu = $this->get('application.service.menu')->getMenu();

        return $this->render('AppBundle:Page:index.html.twig', array(
            'category' => $category,
            'menu' => $menu
        ));
    }

    public function detailAction($slug) {

        $post = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Post')
            ->findOneBy(array(
                'isFeature' => 1,
                'slug' => $slug
            ));

        $menu = $this->get('application.service.menu')->getMenu();

        $categories = $post->getCategories();
        return $this->render('AppBundle:Education:detail.html.twig', array(
            'post' => $post,
            'category' => $categories[0],
            'menu' => $menu
        ));
    }

    public function locationAction($slug) {
        //var_dump($this->getParameter('header'));die();
        $menu = $this->get('application.service.menu')->getMenu();
        $category = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Category')
            ->findOneBy(array(
                'isFeature' => 1,
                'slug' => 'co-so-dao-tao'
            ));

        $posts = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Post')
            ->get(array(
                'status' => Post::STATUS_PUBLISH,
                'type' => Post::TYPE_POST,
                'is_featured' => 1,
                'category_slug' => 'co-so-dao-tao',

            ), array(), 3, 0);
        $events = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Event')
            ->get(array(
                'status' => Event::STATUS_PUBLISH,
                'is_featured' => 1,
            ), array('created','DESC'), 3, 0);
        return $this->render('AppBundle:Page:location.html.twig', array(
            'category' => $category,
            'posts' => $posts,
            'menu' => $menu,
            'events' => $events
        ));
    }

}
