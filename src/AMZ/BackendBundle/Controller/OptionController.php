<?php

namespace AMZ\BackendBundle\Controller;

use AMZ\BackendBundle\Entity\Option;
use AMZ\BackendBundle\Form\OptionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class OptionController extends Controller
{
    public function indexAction(Request $request)
    {
        $parameters = $request->query->all();
        $pagination = $this->get('amz_db.service.query')->getRepository('AMZBackendBundle:Option')
            ->paging($parameters, $request->get('page', 1), Option::ADMIN_NUMBER_ITEM_PER_PAGE);
        return $this->render('AMZBackendBundle:Option:index.html.twig', array(
            'pagination' => $pagination,
            'parameters' => $parameters
        ));
    }

    public function editAction($id, Request $request)
    {
        $entity = $this->get('amz_db.service.query')->getRepository('AMZBackendBundle:Option')
            ->find($id);
        if (empty($entity)) {
            throw $this->createNotFoundException('Not found entity: ' . $id);
        }
        $form = $this->createForm(OptionType::class, $entity, array('type' => $entity->getType()));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZBackendBundle:Option')
                ->update($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_option_homepage');
        }

        return $this->render('AMZBackendBundle:Option:edit.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView()
        ));
    }
}
