<?php

namespace AppBundle\Controller;

use AMZ\PostBundle\Entity\Post;
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

}
