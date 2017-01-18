<?php

namespace AMZ\ProfileBundle\Controller;

use AMZ\ProfileBundle\Entity\School;
use AMZ\ProfileBundle\Form\SchoolType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SchoolController extends Controller
{
    public function indexAction(Request $request)
    {
        $parameters = $request->query->all();
        $pagination = $this->get('amz_db.service.query')->getRepository('AMZProfileBundle:School')
            ->paging($parameters, $request->get('page', 1), School::ADMIN_NUMBER_ITEM_PER_PAGE);
        return $this->render('AMZProfileBundle:School:index.html.twig', array(
            'pagination' => $pagination,
            'parameters' => $parameters,
            'city' => $this->getParameter('city')
        ));
    }

    public function createAction(Request $request)
    {
        $entity = new School();
        $form = $this->createForm(SchoolType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZProfileBundle:School')
                ->insert($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_profile_school_homepage');
        }
        return $this->render('AMZProfileBundle:School:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($id, Request $request)
    {
        $entity = $this->get('amz_db.service.query')->getRepository('AMZProfileBundle:School')
            ->find($id);
        if (empty($entity)) {
            throw $this->createNotFoundException('Not found entity: ' . $id);
        }
        $form = $this->createForm(SchoolType::class, $entity, array('current_id' => $id));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZProfileBundle:School')
                ->update($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_profile_school_homepage');
        }

        return $this->render('AMZProfileBundle:School:edit.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView()
        ));
    }

    public function deleteAction($id)
    {
        $entity = $this->get('amz_db.service.query')->getRepository('AMZProfileBundle:School')
            ->find($id);
        if (empty($entity)) {
            throw $this->createNotFoundException('Not found entity: ' . $id);
        }
        $result = $this->get('amz_db.service.query')->getRepository('AMZProfileBundle:School')
            ->remove($entity);
        if ($result) {
            $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được xóa thành công!'));
        } else {
            $this->addFlash('error', $this->get('translator')->trans('Xóa dữ liệu thất bại! Vui lòng thử lại sau'));
        }
        return $this->redirectToRoute('amz_profile_school_homepage');
    }
}
