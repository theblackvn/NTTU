<?php

namespace AppBundle\Controller;

use AMZ\PostBundle\Entity\Post;
use AMZ\PostBundle\Entity\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SearchController extends Controller
{
    public function indexAction(Request $request)
    {
        $data = $request->query->all();
        $result = $this->get('amz_db.service.query')
            ->getRepository('AMZPostBundle:Post')
            ->get(array(
                'status' => Post::STATUS_PUBLISH,
                'type' => Post::TYPE_POST,
                'is_featured' => 1,
                'keyword' => $data['keyword'],
            ), array(), null, 0);
        //var_dump(count($result));die();
        $menu = $this->get('application.service.menu')->getMenu();
        return $this->render('AppBundle:Search:index.html.twig', array(
            'result' => $result,
            'menu' => $menu
        ));
    }


}
