<?php

namespace AMZ\ProfileBundle\Controller;

use AMZ\ProfileBundle\Entity\SchoolClassUnit;
use AMZ\ProfileBundle\Form\SchoolClassUnitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SchoolClassUnitController extends Controller
{
    public function indexAction(Request $request)
    {
        $parameters = $request->query->all();
        $pagination = $this->get('amz_db.service.query')->getRepository('AMZProfileBundle:SchoolClassUnit')
            ->paging($parameters, $request->get('page', 1), SchoolClassUnit::ADMIN_NUMBER_ITEM_PER_PAGE);
        return $this->render('AMZProfileBundle:SchoolClassUnit:index.html.twig', array(
            'pagination' => $pagination,
            'parameters' => $parameters
        ));
    }

    public function createAction(Request $request)
    {
        $entity = new SchoolClassUnit();
        $form = $this->createForm(SchoolClassUnitType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZProfileBundle:SchoolClassUnit')
                ->insert($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_profile_class_unit_homepage');
        }
        return $this->render('AMZProfileBundle:SchoolClassUnit:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($id, Request $request)
    {
        $entity = $this->get('amz_db.service.query')->getRepository('AMZProfileBundle:SchoolClassUnit')
            ->find($id);
        if (empty($entity)) {
            throw $this->createNotFoundException('Not found entity: ' . $id);
        }
        $form = $this->createForm(SchoolClassUnitType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZProfileBundle:SchoolClassUnit')
                ->update($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_profile_class_unit_homepage');
        }

        return $this->render('AMZProfileBundle:SchoolClassUnit:edit.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView()
        ));
    }

    public function deleteAction($id)
    {
        $entity = $this->get('amz_db.service.query')->getRepository('AMZProfileBundle:SchoolClassUnit')
            ->find($id);
        if (empty($entity)) {
            throw $this->createNotFoundException('Not found entity: ' . $id);
        }
        $result = $this->get('amz_db.service.query')->getRepository('AMZProfileBundle:SchoolClassUnit')
            ->remove($entity);
        if ($result) {
            $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được xóa thành công!'));
        } else {
            $this->addFlash('error', $this->get('translator')->trans('Xóa dữ liệu thất bại! Vui lòng thử lại sau'));
        }
        return $this->redirectToRoute('amz_profile_class_unit_homepage');
    }
}
