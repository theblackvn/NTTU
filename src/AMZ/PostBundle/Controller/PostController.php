<?php

namespace AMZ\PostBundle\Controller;

use AMZ\PostBundle\Entity\Post;
use AMZ\PostBundle\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    public function indexAction(Request $request)
    {
        $parameters = $request->query->all();
        $parameters['type'] = Post::TYPE_POST;
        $pagination = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Post')
            ->paging($parameters, $request->get('page', 1), Post::ADMIN_NUMBER_ITEM_PER_PAGE);

        $categories = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Category')
            ->get();
        return $this->render('AMZPostBundle:Post:index.html.twig', array(
            'pagination' => $pagination,
            'parameters' => $parameters,
            'categories' => $categories
        ));
    }

    public function createAction(Request $request)
    {
        $entity = new Post();
        $entity->setType(Post::TYPE_POST);
        $entity->setStatus(Post::STATUS_PUBLISH);
        $form = $this->createForm(PostType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Post')
                ->insert($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_post_homepage');
        }
        return $this->render('AMZPostBundle:Post:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($id, Request $request)
    {
        $entity = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Post')
            ->findOneBy(array(
                'id' => $id,
                'type' => Post::TYPE_POST
            ));
        if (empty($entity)) {
            throw $this->createNotFoundException('Not found entity: ' . $id);
        }
        $form = $this->createForm(PostType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Post')
                ->update($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_post_homepage');
        }

        return $this->render('AMZPostBundle:Post:edit.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView()
        ));
    }

    public function deleteAction($id)
    {
        $entity = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Post')
            ->findOneBy(array(
                'id' => $id,
                'type' => Post::TYPE_POST
            ));
        if (empty($entity)) {
            throw $this->createNotFoundException('Not found entity: ' . $id);
        }
        $result = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Post')
            ->remove($entity);
        if ($result) {
            $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
        } else {
            $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
        }
        return $this->redirectToRoute('amz_post_homepage');
    }

    public function getByCategoryAction($catID, Request $request)
    {
        $parameters = $request->query->all();
        $parameters['type'] = Post::TYPE_POST;
        $parameters['category'] = $catID;
        $pagination = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Post')
            ->paging($parameters, $request->get('page', 1), Post::ADMIN_NUMBER_ITEM_PER_PAGE);

        $categories = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Category')
            ->get();
        return $this->render('AMZPostBundle:Post:index.html.twig', array(
            'pagination' => $pagination,
            'parameters' => $parameters,
            'categories' => $categories,
            'catID' => $catID
        ));
    }

    public function createByCategoryAction($catID, Request $request)
    {
        $entity = new Post();
        $entity->setType(Post::TYPE_POST);
        $entity->setStatus(Post::STATUS_PUBLISH);
        $form = $this->createForm(PostType::class, $entity);
        $form->handleRequest($request);
        $category = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Category')
            ->findOneBy(array(
                'id' => $catID));
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Post')
                ->insert($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_post_homepage');
        }
        return $this->render('AMZPostBundle:Post:createByCategory.html.twig', array(
            'form' => $form->createView(),
            'catID' => $catID,
            'catName' => $category->getTitle()

        ));
    }
}
