<?php

namespace AMZ\ProfileBundle\Controller;

use AMZ\ProfileBundle\Entity\BmiResult;
use AMZ\ProfileBundle\Form\BmiResultType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BMIResultController extends Controller
{
    public function indexAction(Request $request)
    {
//        get list
        $parameters = $request->query->all();
        $pagination = $this->get('amz_db.service.query')->getRepository('AMZProfileBundle:BmiResult')
            ->paging($parameters, $request->get('page', 1), BmiResult::ADMIN_NUMBER_ITEM_PER_PAGE);
        return $this->render('AMZProfileBundle:BmiResult:index.html.twig', array(
            'pagination' => $pagination,
            'parameters' => $parameters
        ));
    }
    public function editAction(Request $request, $id){
        $entity = $this->get('amz_db.service.query')->getRepository("AMZProfileBundle:BmiResult")
            ->findOneBy(array('id' => $id));

        $opts = array();
        $type = $this->createForm(BmiResultType::class, $entity, $opts);

        $type->handleRequest($request);
        if($type->isSubmitted() && $type->isValid()){
            $saved = $this->get('amz_db.service.query')->getRepository('AMZProfileBundle:BmiResult')
                ->update($entity);
            if($saved){
                $this->addFlash('amz_bmi_result_notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            }
        }

        $form = $type->createView();

        return $this->render('AMZProfileBundle:BmiResult:edit.html.twig',compact('form'));

    }
}