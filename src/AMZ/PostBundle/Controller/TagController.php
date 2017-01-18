<?php

namespace AMZ\PostBundle\Controller;

use AMZ\PostBundle\Entity\Tag;
use AMZ\PostBundle\Form\TagType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TagController extends Controller
{
    public function indexAction(Request $request)
    {
        $parameters = $request->query->all();
        $pagination = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Tag')
            ->paging($parameters, $request->get('page', 1), Tag::ADMIN_NUMBER_ITEM_PER_PAGE);
        return $this->render('AMZPostBundle:Tag:index.html.twig', array(
            'pagination' => $pagination,
            'parameters' => $parameters
        ));
    }

    public function createAction(Request $request)
    {
        $entity = new Tag();
        $form = $this->createForm(TagType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Tag')
                ->insert($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_post_tag_homepage');
        }
        return $this->render('AMZPostBundle:Tag:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($id, Request $request)
    {
        $entity = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Tag')
            ->find($id);
        if (empty($entity)) {
            throw $this->createNotFoundException('Not found entity: ' . $id);
        }
        $form = $this->createForm(TagType::class, $entity, array('current_id' => $id));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Tag')
                ->update($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_post_tag_homepage');
        }

        return $this->render('AMZPostBundle:Tag:edit.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView()
        ));
    }

    public function deleteAction($id)
    {
        $entity = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Tag')
            ->find($id);
        if (empty($entity)) {
            throw $this->createNotFoundException('Not found entity: ' . $id);
        }
        $result = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Tag')
            ->remove($entity);
        if ($result) {
            $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được xóa thành công!'));
        } else {
            $this->addFlash('error', $this->get('translator')->trans('Xóa dữ liệu thất bại! Vui lòng thử lại sau'));
        }
        return $this->redirectToRoute('amz_post_tag_homepage');
    }
}
