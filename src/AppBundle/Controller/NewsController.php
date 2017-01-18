<?php

namespace AppBundle\Controller;

use AMZ\PostBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class NewsController extends Controller
{
    public function postAction($category_slug, $slug)
    {
        $post = $this->get('amz_db.service.query')->getRepository("AMZPostBundle:Post")
            ->findOneBy(array(
               'slug' => $slug
            ));

        $relatives = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Post')
            ->get(array(
                'category_slug' => $category_slug,
                'lt_id' => $post->getId()

            ), null, 3, 0);
        return $this->render('@App/News/post-detail.html.twig', compact('post','relatives','category_slug'));
    }
    public function postsAction(Request $request, $category_slug)
    {
        $parameters['category_slug']=$category_slug;
        $parameters['is_featured']=0;

        $pagination = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Post')
            ->paging($parameters, $request->get('page', 1), 5);
        $top = $pagination[0];

        $features = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Post')
            ->get(array(
                'category_slug' => $category_slug,
                'is_featured' => 1
            ),array(), 3,0);

        return $this->render('@App/News/posts.html.twig', compact('top','pagination','features','category_slug'));
    }


}
