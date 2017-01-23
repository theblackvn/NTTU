<?php

namespace AppBundle\Controller;

use AMZ\PostBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EducationController extends Controller
{
    public function indexAction()
    {
        $menu = $this->get('application.service.menu')->getMenu();
        $category = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Category')
            ->findOneBy(array(
                'isFeature' => 1,
                'slug' => 'dao-tao'
            ));
        $childCategory = $category->getChildren();



        return $this->render('AppBundle:Education:index.html.twig', array(
            'childCategory' => $childCategory,
            'category' => $category,
            'menu' => $menu
        ));
    }
		
    public function aboutAction(){
        return $this->render('AppBundle:Default:about-us.html.twig');
    }

    public function subPageAction($parentSlug, $slug) {
        $menu = $this->get('application.service.menu')->getMenu();
        $category = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Category')
            ->findOneBy(array(
                'isFeature' => 1,
                'slug' => $slug
            ));
        $posts = array();
        if (!empty($category) && !empty($category)) {
            $posts = $this->get('amz_db.service.query')
                ->getRepository('AMZPostBundle:Post')
                ->get(array(
                    'status' => Post::STATUS_PUBLISH,
                    'type' => Post::TYPE_POST,
                    'is_featured' => 1,
                    'category_slug' => $slug,

                ), array(), 3, 0);

        }

        return $this->render('AppBundle:Education:sub-page.html.twig', array(
            'posts' => $posts,
            'category' => $category,
            'menu' => $menu
        ));
    }



}
