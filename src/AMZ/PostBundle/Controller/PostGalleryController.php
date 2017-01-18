<?php

namespace AMZ\PostBundle\Controller;

use AMZ\PostBundle\Entity\Gallery;
use AMZ\PostBundle\Entity\Post;
use AMZ\PostBundle\Form\GalleryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PostGalleryController extends Controller
{
    public function indexAction($post_id, Request $request)
    {
        $post = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Post')
            ->findOneBy(array(
                'id' => $post_id,
                'type' => Post::TYPE_POST
            ));
        if (empty($post)) {
            throw $this->createNotFoundException("Not found post: {$post_id}");
        }
        $parameters = $request->query->all();
        $parameters['post_id'] = $post_id;
        $pagination = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Gallery')
            ->paging($parameters, $request->get('page', 1), Gallery::ADMIN_NUMBER_ITEM_PER_PAGE);
        return $this->render('AMZPostBundle:PostGallery:index.html.twig', array(
            'pagination' => $pagination,
            'parameters' => $parameters,
            'post' => $post
        ));
    }

    public function createAction($post_id, Request $request)
    {
        $post = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Post')
            ->findOneBy(array(
                'id' => $post_id,
                'type' => Post::TYPE_POST
            ));
        if (empty($post)) {
            throw $this->createNotFoundException("Not found post: {$post_id}");
        }
        $entity = new Gallery();
        $entity->setPost($post);
        $form = $this->createForm(GalleryType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Gallery')
                ->insert($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_post_gallery_homepage', array('post_id' => $post_id));
        }
        return $this->render('AMZPostBundle:PostGallery:create.html.twig', array(
            'form' => $form->createView(),
            'post' => $post
        ));
    }

    public function editAction($post_id, $id, Request $request)
    {
        $post = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Post')
            ->findOneBy(array(
                'id' => $post_id,
                'type' => Post::TYPE_POST
            ));
        if (empty($post)) {
            throw $this->createNotFoundException("Not found post: {$post_id}");
        }
        $entity = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Gallery')
            ->findOneBy(array(
                'id' => $id,
                'post' => $post_id
            ));
        if (empty($entity)) {
            throw $this->createNotFoundException('Not found entity: ' . $id);
        }
        $form = $this->createForm(GalleryType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Gallery')
                ->update($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_post_gallery_homepage', array('post_id' => $post_id));
        }

        return $this->render('AMZPostBundle:PostGallery:edit.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'post' => $post
        ));
    }

    public function deleteAction($post_id, $id)
    {
        $entity = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Gallery')
            ->findOneBy(array(
                'id' => $id,
                'post' => $post_id
            ));
        if (empty($entity)) {
            throw $this->createNotFoundException('Not found entity: ' . $id);
        }
        $result = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Gallery')
            ->remove($entity);
        if ($result) {
            $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được xóa thành công!'));
        } else {
            $this->addFlash('error', $this->get('translator')->trans('Xóa dữ liệu thất bại! Vui lòng thử lại sau'));
        }
        return $this->redirectToRoute('amz_post_gallery_homepage', array('post_id' => $post_id));
    }
}
