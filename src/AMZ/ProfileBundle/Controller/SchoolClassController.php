<?php

namespace AMZ\ProfileBundle\Controller;

use AMZ\ProfileBundle\Entity\SchoolClass;
use AMZ\ProfileBundle\Form\SchoolClassType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SchoolClassController extends Controller
{
    public function indexAction($school, Request $request)
    {
        $school = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:School')
            ->find($school);
        if (empty($school)) {
            throw $this->createNotFoundException();
        }
        $parameters = $request->query->all();
        $parameters['school'] = $school->getId();
        $pagination = $this->get('amz_db.service.query')->getRepository('AMZProfileBundle:SchoolClass')
            ->paging($parameters, $request->get('page', 1), SchoolClass::ADMIN_NUMBER_ITEM_PER_PAGE);
        return $this->render('AMZProfileBundle:SchoolClass:index.html.twig', array(
            'pagination' => $pagination,
            'parameters' => $parameters,
            'school' => $school
        ));
    }

    public function createAction($school, Request $request)
    {
        $school = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:School')
            ->find($school);
        if (empty($school)) {
            throw $this->createNotFoundException();
        }
        $entity = new SchoolClass();
        $entity->setSchool($school);
        $form = $this->createForm(SchoolClassType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZProfileBundle:SchoolClass')
                ->insert($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_profile_class_homepage', array('school' => $school->getId()));
        }
        return $this->render('AMZProfileBundle:SchoolClass:create.html.twig', array(
            'form' => $form->createView(),
            'school' => $school
        ));
    }

    public function editAction($school, $id, Request $request)
    {
        $school = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:School')
            ->find($school);
        if (empty($school)) {
            throw $this->createNotFoundException();
        }
        $entity = $this->get('amz_db.service.query')->getRepository('AMZProfileBundle:SchoolClass')
            ->find($id);
        if (empty($entity)) {
            throw $this->createNotFoundException('Not found entity: ' . $id);
        }
        $form = $this->createForm(SchoolClassType::class, $entity, array('current_id' => $id));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZProfileBundle:SchoolClass')
                ->update($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_profile_class_homepage', array('school' => $school->getId()));
        }

        return $this->render('AMZProfileBundle:SchoolClass:edit.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'school' => $school
        ));
    }

    public function deleteAction($school, $id)
    {
        $school = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:School')
            ->find($school);
        if (empty($school)) {
            throw $this->createNotFoundException();
        }
        $entity = $this->get('amz_db.service.query')->getRepository('AMZProfileBundle:SchoolClass')
            ->find($id);
        if (empty($entity)) {
            throw $this->createNotFoundException('Not found entity: ' . $id);
        }
        $result = $this->get('amz_db.service.query')->getRepository('AMZProfileBundle:SchoolClass')
            ->remove($entity);
        if ($result) {
            $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được xóa thành công!'));
        } else {
            $this->addFlash('error', $this->get('translator')->trans('Xóa dữ liệu thất bại! Vui lòng thử lại sau'));
        }
        return $this->redirectToRoute('amz_profile_class_homepage', array('school' => $school->getId()));
    }
}
