<?php

namespace AMZ\PostBundle\Controller;

use AMZ\PostBundle\Entity\Post;
use AMZ\PostBundle\Form\StaticBlockType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class StaticBlockController extends Controller
{
    public function indexAction(Request $request)
    {
        $parameters = $request->query->all();
        $parameters['type'] = Post::TYPE_STATIC_BLOCK;
        $pagination = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Post')
            ->paging($parameters, $request->get('page', 1), Post::ADMIN_NUMBER_ITEM_PER_PAGE);
        return $this->render('AMZPostBundle:StaticBlock:index.html.twig', array(
            'pagination' => $pagination,
            'parameters' => $parameters
        ));
    }

    public function createAction(Request $request)
    {
        $entity = new Post();
        $entity->setType(Post::TYPE_STATIC_BLOCK);
        $entity->setStatus(Post::STATUS_PUBLISH);
        $form = $this->createForm(StaticBlockType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Post')
                ->insert($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_static_block_homepage');
        }
        return $this->render('AMZPostBundle:StaticBlock:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($id, Request $request)
    {
        $entity = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Post')
            ->findOneBy(array(
                'id' => $id,
                'type' => Post::TYPE_STATIC_BLOCK
            ));
        if (empty($entity)) {
            throw $this->createNotFoundException('Not found entity: ' . $id);
        }
        $form = $this->createForm(StaticBlockType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Post')
                ->update($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_static_block_homepage');
        }

        return $this->render('AMZPostBundle:StaticBlock:edit.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView()
        ));
    }
}
