<?php

namespace AppBundle\Controller;

use AMZ\PostBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StatisticsController extends Controller
{
    private function calculateRangeDate($month, $year)
    {
        if ($month >= 9) {
            $date = date_create_from_format('m/Y', $month . '/' . $year);
            $lastDay = date_format($date, 't');
            $startDate = date_create_from_format('d/m/Y H:i:s', '1/' . $month . '/' . $year . '00:00:00');
            $endDate = date_create_from_format('d/m/Y H:i:s', $lastDay . '/' . $month . '/' . $year . '23:59:59');
        } else {
            $year = $year + 1;
            $date = date_create_from_format('m/Y', $month . '/' . $year);
            $lastDay = date_format($date, 't');
            $startDate = date_create_from_format('d/m/Y H:i:s', '1/' . $month . '/' . $year . '00:00:00');
            $endDate = date_create_from_format('d/m/Y H:i:s', $lastDay . '/' . $month . '/' . $year . '23:59:59');
        }
        return array(
            'start_date' => $startDate,
            'end_date' => $endDate
        );
    }

    public function reportByClassAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $parameters = $request->request->all();
            $res = new Response();
            $school = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:School')
                ->find($parameters['school']);
            $class = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:SchoolClass')
                ->find($parameters['class']);
            $schoolYear = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:SchoolYear')
                ->find($parameters['year']);
            $startEnd = $this->calculateRangeDate($parameters['month'], $schoolYear->getYear());
            $bmiResults = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:ProfileBmiResult')
                ->get(array(
                    'class' => $class->getId(),
                    'month_weight_range' => $startEnd
                ), array('profile_name' => 'ASC'));
            $html = $this->renderView('@App/Statistics/Result/class.html.twig', array(
                'parameters' => $parameters,
                'school' => $school,
                'class' => $class,
                'year' => $schoolYear,
                'bmiResults' => $bmiResults,
                'startEnd' => $startEnd
            ));
            $res->setStatusCode(200);
            $res->setContent($html);
            return $res;
        }
        $cities = $this->getParameter('city');
        return $this->render('AppBundle:Statistics:class.html.twig', array(
            'cities' => $cities,
        ));
    }

    public function reportBySchoolAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $parameters = $request->request->all();
            $res = new Response();
            $school = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:School')
                ->find($parameters['school']);

            $schoolYear = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:SchoolYear')
                ->find($parameters['year']);
            $startEnd = $this->calculateRangeDate($parameters['month'], $schoolYear->getYear());
            $classCondition = '';
            if (!empty($school)) {
                $classes = $school->getClasses();
                //echo "<pre>";var_dump($classes);die();
                if (!empty($classes)) {
                    foreach ($classes as $i => $class) {
                        $classCondition .= $class->getId();
                        if ($i != count($classes) - 1) $classCondition .= ', ';
                    }
                }
            }
            if ($classCondition != '') {
                $bmiResults = $this->get('amz_db.service.query')
                    ->getRepository('AMZProfileBundle:ProfileBmiResult')
                    ->get(array(
                        'classIn' => $classCondition,
                        'month_weight_range' => $startEnd,
                        'notCategory' => 3,
                    ), array('profile_name' => 'ASC'));
            } else $bmiResults = array();
            //var_dump(count($bmiResults));die();
            $finalResult = array();
            $finalResult = $this->getFinalResult($bmiResults, $finalResult);
            $html = $this->renderView('@App/Statistics/Result/school.html.twig', array(
                'parameters' => $parameters,
                'school' => $school,
                'year' => $schoolYear,
                'bmiResults' => $bmiResults,
                'startEnd' => $startEnd,
                'finalResult' => $finalResult,
                'totalResult' => count($bmiResults) ? count($bmiResults) : 1
            ));
            $res->setStatusCode(200);
            $res->setContent($html);
            return $res;
        }
        $cities = $this->getParameter('city');
        return $this->render('AppBundle:Statistics:school.html.twig', array(
            'cities' => $cities,
        ));
    }

    public function reportBySchoolUnitAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $parameters = $request->request->all();
            $res = new Response();
            $school = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:School')
                ->find($parameters['school']);

            $schoolYear = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:SchoolYear')
                ->find($parameters['year']);
            $startEnd = $this->calculateRangeDate($parameters['month'], $schoolYear->getYear());
            $classCondition = '';
            if (!empty($school)) {
                $classes = $school->getClasses();
                //echo "<pre>";var_dump($classes);die();
                if (!empty($classes)) {
                    foreach ($classes as $i => $class) {
                        $classCondition .= $class->getId();
                        if ($i != count($classes) - 1) $classCondition .= ', ';
                    }
                }
            }
            if ($classCondition != '') {
                $bmiResults = $this->get('amz_db.service.query')
                    ->getRepository('AMZProfileBundle:ProfileBmiResult')
                    ->get(array(
                        'classIn' => $classCondition,
                        'month_weight_range' => $startEnd,
                        'notCategory' => 3,
                        'schoolUnit' => $parameters['school_unit']
                    ), array('profile_name' => 'ASC'));
            } else $bmiResults = array();
            //var_dump(count($bmiResults));die();
            $finalResult = array();
            $finalResult = $this->getFinalResult($bmiResults, $finalResult);
            $html = $this->renderView('@App/Statistics/Result/schoolUnit.html.twig', array(
                'parameters' => $parameters,
                'school' => $school,
                'year' => $schoolYear,
                'bmiResults' => $bmiResults,
                'startEnd' => $startEnd,
                'finalResult' => $finalResult,
                'totalResult' => count($bmiResults) ? count($bmiResults) : 1
            ));
            $res->setStatusCode(200);
            $res->setContent($html);
            return $res;
        }
        $cities = $this->getParameter('city');
        $schoolUnit = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:SchoolClassUnit')
            ->get(array());
        return $this->render('AppBundle:Statistics:schoolUnit.html.twig', array(
            'cities' => $cities,
            'schoolUnit' => $schoolUnit
        ));
    }

    public function reportByDistrictAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $parameters = $request->request->all();
            $res = new Response();
            $schools = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:School')
                ->get(array('district' => $parameters['district']));

            $schoolYear = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:SchoolYear')
                ->find($parameters['year']);
            $startEnd = $this->calculateRangeDate($parameters['month'], $schoolYear->getYear());
            $classCondition = '';
            if (!empty($schools)) {
                foreach ($schools as $si => $school) {
                    if (!empty($school)) {
                        $classes = $school->getClasses();
                        if (!empty($classes)) {
                            foreach ($classes as $i => $class) {
                                $classCondition .= $class->getId();
                                if ($i != count($classes) - 1) $classCondition .= ', ';
                            }
                        }
                    }
                    if ($si != count($schools) - 1) $classCondition .= ', ';
                }

            }
            //echo $classCondition;die();
            $bmiResults = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:ProfileBmiResult')
                ->get(array(
                    'classIn' => $classCondition,
                    'month_weight_range' => $startEnd,
                    'notCategory' => 3
                ), array('profile_name' => 'ASC'));
            //var_dump(count($bmiResults));die();
            $finalResult = array();
            $finalResult = $this->getFinalResult($bmiResults, $finalResult);
            $html = $this->renderView('@App/Statistics/Result/district.html.twig', array(
                'parameters' => $parameters,
                'year' => $schoolYear,
                'bmiResults' => $bmiResults,
                'startEnd' => $startEnd,
                'finalResult' => $finalResult,
                'totalResult' => count($bmiResults)
            ));
            $res->setStatusCode(200);
            $res->setContent($html);
            return $res;
        }
        $cities = $this->getParameter('city');
        return $this->render('AppBundle:Statistics:district.html.twig', array(
            'cities' => $cities
        ));
    }

    public function reportByCityAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $parameters = $request->request->all();
            $res = new Response();
            $schools = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:School')
                ->get(array('city' => $parameters['city']));

            $schoolYear = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:SchoolYear')
                ->find($parameters['year']);
            $startEnd = $this->calculateRangeDate($parameters['month'], $schoolYear->getYear());
            $classCondition = '';
            if (!empty($schools)) {
                foreach ($schools as $si => $school) {
                    if (!empty($school)) {
                        $classes = $school->getClasses();
                        if (!empty($classes)) {
                            foreach ($classes as $i => $class) {
                                $classCondition .= $class->getId();
                                if ($i != count($classes) - 1) $classCondition .= ', ';
                            }
                        }
                    }
                    if ($si != count($schools) - 1) $classCondition .= ', ';
                }

            }
            //echo $classCondition;die();
            $bmiResults = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:ProfileBmiResult')
                ->get(array(
                    'classIn' => $classCondition,
                    'month_weight_range' => $startEnd,
                    'notCategory' => 3
                ), array('profile_name' => 'ASC'));
            //var_dump(count($bmiResults));die();
            $finalResult = array();
            $finalResult = $this->getFinalResult($bmiResults, $finalResult);
            $html = $this->renderView('@App/Statistics/Result/city.html.twig', array(
                'parameters' => $parameters,
                'year' => $schoolYear,
                'bmiResults' => $bmiResults,
                'startEnd' => $startEnd,
                'finalResult' => $finalResult,
                'totalResult' => count($bmiResults)
            ));
            $res->setStatusCode(200);
            $res->setContent($html);
            return $res;
        }
        $cities = $this->getParameter('city');
        return $this->render('AppBundle:Statistics:city.html.twig', array(
            'cities' => $cities
        ));
    }

    public function reportByAllAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $parameters = $request->request->all();
            $res = new Response();
            $schools = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:School')
                ->get(array());

            $schoolYear = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:SchoolYear')
                ->find($parameters['year']);
            $startEnd = $this->calculateRangeDate($parameters['month'], $schoolYear->getYear());

            //echo $classCondition;die();
            $bmiResults = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:ProfileBmiResult')
                ->get(array(
                    //'classIn' => $classCondition,
                    'month_weight_range' => $startEnd,
                    'notCategory' => 3
                ), array('profile_name' => 'ASC'));
            //var_dump(count($bmiResults));die();
            $finalResult = array();
            $finalResult = $this->getFinalResult($bmiResults, $finalResult);
            $html = $this->renderView('@App/Statistics/Result/all.html.twig', array(
                'parameters' => $parameters,
                'year' => $schoolYear,
                'bmiResults' => $bmiResults,
                'startEnd' => $startEnd,
                'finalResult' => $finalResult,
                'totalResult' => count($bmiResults)
            ));
            $res->setStatusCode(200);
            $res->setContent($html);
            return $res;
        }
        $cities = $this->getParameter('city');
        return $this->render('AppBundle:Statistics:all.html.twig', array(
            'cities' => $cities
        ));
    }

    public function ajaxChangeCityAction(Request $request)
    {
        $res = new Response();
        $city = $request->get('city');
        $cities = $this->getParameter('city');
        if (!isset($cities[$city])) {
            $res->setStatusCode(404);
            return $res;
        }
        $html = $this->renderView('@App/Statistics/Ajax/load-district.html.twig', array(
            'districts' => $cities[$city]
        ));
        $res->setContent($html);
        $res->setStatusCode(200);
        return $res;
    }

    public function ajaxChangeDistrictAction(Request $request)
    {
        $res = new Response();
        $city = $request->get('city');
        $district = $request->get('district');
        $schools = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:School')
            ->get(array(
                'city' => $city,
                'district' => $district
            ), array('name' => 'ASC'));
        $html = $this->renderView('@App/Statistics/Ajax/load-school.html.twig', array('data' => $schools));
        $res->setContent($html);
        $res->setStatusCode(200);
        return $res;
    }

    public function ajaxChangeYearAction(Request $request)
    {
        $res = new Response();
        $year = $request->get('year');
        $school = $request->get('school');
        $schoolUnit = $request->get('schoolUnit');
        $classes = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:SchoolClass')
            ->get(array(
                'school' => $school,
                'school_year' => $year,
                'khoi_lop' => $schoolUnit
            ), array('name' => 'ASC'));

        $html = $this->renderView('@App/Statistics/Ajax/load-class.html.twig', array('data' => $classes));
        $res->setContent($html);
        $res->setStatusCode(200);
        return $res;
    }

    public function exportForClassAction(Request $request)
    {
        $parameters = $request->query->all();
        $school = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:School')
            ->find($parameters['school']);

        $schoolYear = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:SchoolYear')
            ->find($parameters['year']);
        $startEnd = $this->calculateRangeDate($parameters['month'], $schoolYear->getYear());
        $class = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:SchoolClass')
            ->find($parameters['class']);
        $excelAdapter = $this->get('application.excel.export');
        $excelAdapter->setActiveSheet(0);
        $sheet = $excelAdapter->getActiveSheet();
        $excelAdapter->setCellValueByColumnAndRow(0, 1, $school->getName());
        $sheet->mergeCells('A1:C1');
        $sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excelAdapter->setCellValueByColumnAndRow(5, 1, 'DANH SÁCH HỌC SINH THEO NHÓM LỚP');
        $sheet->mergeCells('F1:I1');
        $sheet->getStyle('F1:I1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excelAdapter->setCellValueByColumnAndRow(5, 2, 'Năm học '.$schoolYear->getName());
        $sheet->mergeCells('F2:I2');
        $sheet->getStyle('F2:I2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excelAdapter->setCellValueByColumnAndRow(5, 3, 'Tên lớp: '.$class->getName());
        $sheet->mergeCells('F3:I3');
        $sheet->getStyle('F3:I3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $teachers = $class->getTeachers();
        $teacher = '';
        if (!empty($teachers)) {
            foreach ($teachers as $i=>$t) {
                $teacher.=$t->getFullName();
                if ($i != (count($teachers)-1)) $teacher.=' - ';
            }
        }
        $excelAdapter->setCellValueByColumnAndRow(5, 4, 'Tên giáo viên phụ trách: '.$teacher);
        $sheet->mergeCells('F4:I4');
        $sheet->getStyle('F4:I4')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        $excelAdapter->setCellValueByColumnAndRow(0, 5, 'Mã học sinh');
        $excelAdapter->setCellValueByColumnAndRow(1, 5, 'Họ và tên');
        $excelAdapter->setCellValueByColumnAndRow(2, 5, "Ngày sinh\n (dd/mm/yyyy)");
        $excelAdapter->setCellValueByColumnAndRow(3, 5, 'Giới tính');
        $excelAdapter->setCellValueByColumnAndRow(4, 5, 'Địa chỉ');
        $excelAdapter->setCellValueByColumnAndRow(5, 5, "Chiều cao\n (cm)");
        $excelAdapter->setCellValueByColumnAndRow(6, 5, "Cân nặng\n (kg)");
        $excelAdapter->setCellValueByColumnAndRow(7, 5, 'Ngày cân đo');
        $excelAdapter->setCellValueByColumnAndRow(8, 5, 'Chiều cao trung bình');
        $excelAdapter->setCellValueByColumnAndRow(9, 5, 'Cân nặng trung bình');
        $excelAdapter->setCellValueByColumnAndRow(10, 5, 'Kết quả');
        $excelAdapter->SetCellTextFormat('A5:K5', true, 'ffffff', '12', 'Arial');
        $excelAdapter->SetCellColor('A5:K5', '009acd');//#009acd
        //$excelAdapter->getActiveSheet()->getStyle('A5:K5')->getAlignment()->setWrapText(true);

        $bmiResults = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:ProfileBmiResult')
            ->get(array(
                'class' => $class->getId(),
                'month_weight_range' => $startEnd
            ), array('profile_name' => 'ASC'));

        if (!empty($bmiResults)) {
            //get number of status
            $aStatus = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:ProfileBmiResult')
                ->get(array(
                    'class' => $class->getId(),
                    'month_weight_range' => $startEnd,
                    'countResultType'=>1
                ));
            //var_dump($aStatus);die();
            $i = 6;
            foreach ($bmiResults as $item) {
                $excelAdapter->setCellValueByColumnAndRow(0, $i, $item->getProfile()->getId());
                $excelAdapter->setCellValueByColumnAndRow(1, $i, $item->getProfile()->getName());
                $excelAdapter->setCellValueByColumnAndRow(2, $i, $item->getProfile()->getDateofBirth()->format('d/m/Y'));
                $excelAdapter->setCellValueByColumnAndRow(3, $i, ($item->getProfile()->getGender()==1)?'Nam':'Nữ');
                $excelAdapter->setCellValueByColumnAndRow(4, $i, $item->getProfile()->getAddress());
                $excelAdapter->setCellValueByColumnAndRow(5, $i, $item->getHeight());
                $excelAdapter->setCellValueByColumnAndRow(6, $i, $item->getWeight());
                $excelAdapter->setCellValueByColumnAndRow(7, $i, ($item->getDayWeight())?$item->getDayWeight()->format('d/m/Y'):'');
                $excelAdapter->setCellValueByColumnAndRow(8, $i, $item->getStandardHeight());
                $excelAdapter->setCellValueByColumnAndRow(9, $i, $item->getStandardWeight());
                $excelAdapter->setCellValueByColumnAndRow(10, $i, $item->getResult());
                $i++;
            }
        }
        $i += 2;
        $sheet->mergeCells("D".($i+1).":I".($i+1));
        $excelAdapter->SetCellTextFormat("D".($i+1).":K".($i+1), true, 'ffffff', '12', 'Arial');
        $excelAdapter->SetCellColor("D".($i+1).":K".($i+1), '009acd');//#009acd
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 1, 'TỔNG KẾT');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 1, 'Tình trạng');
        $excelAdapter->setCellValueByColumnAndRow(9, $i + 1, 'Số trẻ (' . count($bmiResults) . ')');
        $excelAdapter->setCellValueByColumnAndRow(10, $i + 1, 'Giải thích');
        $sheet->mergeCells("D".($i+2).":I".($i+2));
        $i+=2;
        if (!empty($aStatus)) {
            foreach ($aStatus as $status){
                $excelAdapter->setCellValueByColumnAndRow(3, $i, $status['result']);
                $excelAdapter->setCellValueByColumnAndRow(9, $i, $status['countResultType']);
                $i+=1;
            }
        }
        /*
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 3, 'Đe dọa suy dinh dưỡng thể gầy còm');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 4, 'Đe dọa vừa suy dinh dưỡng thể  gầy còm vừa suy dinh dưỡng thể thấp còi');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 5, 'Đe dọa suy dinh dưỡng thể thấp còi');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 6, 'Suy dinh dưỡng thể thấp còi mức độ vừa, đe dọa Suy dinh dưỡng thể gầy còm');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 7, 'Suy dinh dưỡng  thể gầy còm mức độ vừa, đe dọa suy dinh dưỡng thể thấp còi');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 8, 'Suy dinh dưỡng  thể gầy còm mức độ vừa và Suy dinh dưỡng thể thấp còi mức độ vừa');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 9, 'Suy dinh dưỡng thể gầy còm mức độ nặng, đe dọa suy dinh dưỡng thể thấp còi');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 10, 'Suy dinh dưỡng thể gầy còm mức độ vừa');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 11, 'Suy dinh dưỡng thể thấp còi mức độ vừa');
        */
        /*
        $sheet->mergeCells("D".($i+3).":I".($i+3));
        $sheet->mergeCells("D".($i+4).":I".($i+4));
        $sheet->mergeCells("D".($i+5).":I".($i+5));
        $sheet->mergeCells("D".($i+6).":I".($i+6));
        $sheet->mergeCells("D".($i+7).":I".($i+7));
        $sheet->mergeCells("D".($i+8).":I".($i+8));
        $sheet->mergeCells("D".($i+9).":I".($i+9));
        $sheet->mergeCells("D".($i+10).":I".($i+10));
        $sheet->mergeCells("D".($i+11).":I".($i+11));
        */


        /*
        $excelAdapter->setCellValueByColumnAndRow(10, $i + 2, 'Trẻ có cân nặng và chiều cao trong giới hạn bình thường');
        $excelAdapter->setCellValueByColumnAndRow(10, $i + 3, 'Trẻ có cân nặng và chiều cao trong giới hạn bình thường nhưng cân nặng thấp nhiều so với chuẩn');
        $excelAdapter->setCellValueByColumnAndRow(10, $i + 4, 'Trẻ có cân nặng và chiều cao trong giới hạn bình thường nhưng cân nặng và chiều cao thấp nhiều so với chuẩn');
        $excelAdapter->setCellValueByColumnAndRow(10, $i + 5, 'Trẻ có cân nặng và chiều cao trong giới hạn bình thường nhưng chiều cao thấp nhiều so với chuẩn');
        $excelAdapter->setCellValueByColumnAndRow(10, $i + 6, 'Trẻ suy dinh dưỡng chiều cao, cân nặng thấp nhiều so với chuẩn');
        $excelAdapter->setCellValueByColumnAndRow(10, $i + 7, 'Trẻ suy dinh dưỡng cân nặng, chiều cao thấp nhiều so với chuẩn');
        $excelAdapter->setCellValueByColumnAndRow(10, $i + 8, 'Trẻ SDD cân nặng và chiều cao');
        $excelAdapter->setCellValueByColumnAndRow(10, $i + 9, 'Trẻ suy dinh dưỡng cân nặng, chiều cao thấp nhiều so với chuẩn');
        $excelAdapter->setCellValueByColumnAndRow(10, $i + 10, 'Trẻ SDD cân nặng');
        $excelAdapter->setCellValueByColumnAndRow(10, $i + 11, 'SDD chiều cao');
        */


        $i+=2;

        $excelAdapter->setCellValueByColumnAndRow(1, $i, 'TỔNG HỢP');
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 1, 'TÌNH TRẠNG');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 1, 'SỐ HS');
        $excelAdapter->setCellValueByColumnAndRow(4, $i + 1, 'TỶ LỆ');

        $excelAdapter->setCellValueByColumnAndRow(1, $i + 2, 'Bình thường');
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 3, 'Suy dinh dưỡng thấp còi');
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 4, 'Suy dinh dưỡng nhẹ cân');
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 5, 'Tổng');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 5, count($bmiResults));
        $excelAdapter->setCellValueByColumnAndRow(4, $i + 5, "100%");

        foreach(range('A','Z') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        return $excelAdapter->export('danh-sach-can-do-lop' . $class->getCode() . '.xls');
        // }
    }

    public function exportForSchoolAction(Request $request)
    {
        $parameters = $request->query->all();
        $school = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:School')
            ->find($parameters['school']);

        $schoolYear = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:SchoolYear')
            ->find($parameters['year']);
        $startEnd = $this->calculateRangeDate($parameters['month'], $schoolYear->getYear());
        $classCondition = '';
        if (!empty($school)) {
            $classes = $school->getClasses();
            //echo "<pre>";var_dump($classes);die();
            if (!empty($classes)) {
                foreach ($classes as $i => $class) {
                    $classCondition .= $class->getId();
                    if ($i != count($classes) - 1) $classCondition .= ', ';
                }
            }
        }
        if ($classCondition != '') {
            $bmiResults = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:ProfileBmiResult')
                ->get(array(
                    'classIn' => $classCondition,
                    'month_weight_range' => $startEnd,
                    'notCategory' => 3,
                ), array('profile_name' => 'ASC'));
        } else $bmiResults = array();

        $excelAdapter = $this->get('application.excel.export');
        $excelAdapter->setActiveSheet(0);
        $sheet = $excelAdapter->getActiveSheet();
        $excelAdapter->setCellValueByColumnAndRow(0, 1, $school->getName());
        $sheet->mergeCells('A1:C1');
        $sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excelAdapter->setCellValueByColumnAndRow(5, 1, 'DANH SÁCH HỌC SINH THEO TRƯỜNG');
        $sheet->mergeCells('F1:I1');
        $sheet->getStyle('F1:I1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excelAdapter->setCellValueByColumnAndRow(5, 2, 'Năm học '.$schoolYear->getName());
        $sheet->mergeCells('F2:I2');
        $sheet->getStyle('F2:I2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excelAdapter->setCellValueByColumnAndRow(5, 3, 'Tên trường: '.$school->getName());
        $sheet->mergeCells('F3:I3');
        $sheet->getStyle('F3:I3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //$excelAdapter->setCellValueByColumnAndRow(5, 4, 'Tên giáo viên phụ trách: '.$teacher);
        $sheet->mergeCells('F4:I4');
        $sheet->getStyle('F4:I4')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //column
        $j=0;
        $excelAdapter->setCellValueByColumnAndRow($j, 5, 'Lớp');
        $excelAdapter->setCellValueByColumnAndRow($j+1, 5, 'Mã học sinh');
        $excelAdapter->setCellValueByColumnAndRow($j+2, 5, 'Họ và tên');
        $excelAdapter->setCellValueByColumnAndRow($j+3, 5, "Ngày sinh\n (dd/mm/yyyy)");
        $excelAdapter->setCellValueByColumnAndRow($j+4, 5, 'Giới tính');
        $excelAdapter->setCellValueByColumnAndRow($j+5, 5, 'Địa chỉ');
        $excelAdapter->setCellValueByColumnAndRow($j+6, 5, "Chiều cao\n (cm)");
        $excelAdapter->setCellValueByColumnAndRow($j+7, 5, "Cân nặng\n (kg)");
        $excelAdapter->setCellValueByColumnAndRow($j+8, 5, 'Ngày cân đo');
        $excelAdapter->setCellValueByColumnAndRow($j+9, 5, 'Chiều cao trung bình');
        $excelAdapter->setCellValueByColumnAndRow($j+10, 5, 'Cân nặng trung bình');
        $excelAdapter->setCellValueByColumnAndRow($j+11, 5, 'Kết quả');
        $excelAdapter->SetCellTextFormat('A5:L5', true, 'ffffff', '12', 'Arial');
        $excelAdapter->SetCellColor('A5:L5', '009acd');//#009acd
        //$excelAdapter->getActiveSheet()->getStyle('A5:K5')->getAlignment()->setWrapText(true);


        if (!empty($bmiResults)) {
            //get number of status
            $aStatus = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:ProfileBmiResult')
                ->get(array(
                    'classIn' => $classCondition,
                    'month_weight_range' => $startEnd,
                    'countResultType'=>1
                ));
            //var_dump($aStatus);die();
            $i = 6;
            foreach ($bmiResults as $item) {
                $excelAdapter->setCellValueByColumnAndRow(0, $i, $item->getSchoolClass()->getName());
                $excelAdapter->setCellValueByColumnAndRow(1, $i, $item->getProfile()->getId());
                $excelAdapter->setCellValueByColumnAndRow(2, $i, $item->getProfile()->getName());
                $excelAdapter->setCellValueByColumnAndRow(3, $i, $item->getProfile()->getDateofBirth()->format('d/m/Y'));
                $excelAdapter->setCellValueByColumnAndRow(4, $i, ($item->getProfile()->getGender()==1)?'Nam':'Nữ');
                $excelAdapter->setCellValueByColumnAndRow(5, $i, $item->getProfile()->getAddress());
                $excelAdapter->setCellValueByColumnAndRow(6, $i, $item->getHeight());
                $excelAdapter->setCellValueByColumnAndRow(7, $i, $item->getWeight());
                $excelAdapter->setCellValueByColumnAndRow(8, $i, ($item->getDayWeight())?$item->getDayWeight()->format('d/m/Y'):'');
                $excelAdapter->setCellValueByColumnAndRow(9, $i, $item->getStandardHeight());
                $excelAdapter->setCellValueByColumnAndRow(10, $i, $item->getStandardWeight());
                $excelAdapter->setCellValueByColumnAndRow(11, $i, $item->getResult());
                $i++;
            }
        }
        $i += 2;
        $sheet->mergeCells("E".($i+1).":J".($i+1));
        $excelAdapter->SetCellTextFormat("D".($i+1).":K".($i+1), true, 'ffffff', '12', 'Arial');
        $excelAdapter->SetCellColor("D".($i+1).":K".($i+1), '009acd');//#009acd
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 1, 'TỔNG KẾT');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 1, 'Tình trạng');
        $excelAdapter->setCellValueByColumnAndRow(9, $i + 1, 'Số trẻ (' . count($bmiResults) . ')');
        $excelAdapter->setCellValueByColumnAndRow(10, $i + 1, 'Giải thích');
        $sheet->mergeCells("D".($i+2).":I".($i+2));
        $i+=2;
        if (!empty($aStatus)) {
            foreach ($aStatus as $status){
                $excelAdapter->setCellValueByColumnAndRow(3, $i, $status['result']);
                $excelAdapter->setCellValueByColumnAndRow(9, $i, $status['countResultType']);
                $i+=1;
            }
        }
        $i+=2;

        $excelAdapter->setCellValueByColumnAndRow(1, $i, 'TỔNG HỢP');
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 1, 'TÌNH TRẠNG');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 1, 'SỐ HS');
        $excelAdapter->setCellValueByColumnAndRow(4, $i + 1, 'TỶ LỆ');

        $excelAdapter->setCellValueByColumnAndRow(1, $i + 2, 'Bình thường');
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 3, 'Suy dinh dưỡng thấp còi');
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 4, 'Suy dinh dưỡng nhẹ cân');
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 5, 'Tổng');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 5, count($bmiResults));
        $excelAdapter->setCellValueByColumnAndRow(4, $i + 5, "100%");

        foreach(range('A','Z') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        return $excelAdapter->export('danh-sach-can-do-truong-' . $school->getCode() . '.xls');
    }

    public function exportForSchoolUnitAction(Request $request)
    {
        $parameters = $request->query->all();
        $school = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:School')
            ->find($parameters['school']);

        $schoolYear = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:SchoolYear')
            ->find($parameters['year']);
        $startEnd = $this->calculateRangeDate($parameters['month'], $schoolYear->getYear());
        $classCondition = '';
        if (!empty($school)) {
            $classes = $school->getClasses();
            //echo "<pre>";var_dump($classes);die();
            if (!empty($classes)) {
                foreach ($classes as $i => $class) {
                    $classCondition .= $class->getId();
                    if ($i != count($classes) - 1) $classCondition .= ', ';
                }
            }
        }
        if ($classCondition != '') {
            $bmiResults = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:ProfileBmiResult')
                ->get(array(
                    'classIn' => $classCondition,
                    'month_weight_range' => $startEnd,
                    'notCategory' => 3,
                    'schoolUnit' => $parameters['school_unit']
                ), array('profile_name' => 'ASC'));
        } else $bmiResults = array();

        $excelAdapter = $this->get('application.excel.export');
        $excelAdapter->setActiveSheet(0);
        $sheet = $excelAdapter->getActiveSheet();
        $excelAdapter->setCellValueByColumnAndRow(0, 1, $school->getName());
        $sheet->mergeCells('A1:C1');
        $sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excelAdapter->setCellValueByColumnAndRow(5, 1, 'DANH SÁCH HỌC SINH THEO KHỐI LỚP');
        $sheet->mergeCells('F1:I1');
        $sheet->getStyle('F1:I1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excelAdapter->setCellValueByColumnAndRow(5, 2, 'Năm học '.$schoolYear->getName());
        $sheet->mergeCells('F2:I2');
        $sheet->getStyle('F2:I2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excelAdapter->setCellValueByColumnAndRow(5, 3, 'Tên trường: '.$school->getName());
        $sheet->mergeCells('F3:I3');
        $sheet->getStyle('F3:I3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //$excelAdapter->setCellValueByColumnAndRow(5, 4, 'Tên giáo viên phụ trách: '.$teacher);
        $sheet->mergeCells('F4:I4');
        $sheet->getStyle('F4:I4')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //column
        $j=0;
        $excelAdapter->setCellValueByColumnAndRow($j, 5, 'Lớp');
        $excelAdapter->setCellValueByColumnAndRow($j+1, 5, 'Mã học sinh');
        $excelAdapter->setCellValueByColumnAndRow($j+2, 5, 'Họ và tên');
        $excelAdapter->setCellValueByColumnAndRow($j+3, 5, "Ngày sinh\n (dd/mm/yyyy)");
        $excelAdapter->setCellValueByColumnAndRow($j+4, 5, 'Giới tính');
        $excelAdapter->setCellValueByColumnAndRow($j+5, 5, 'Địa chỉ');
        $excelAdapter->setCellValueByColumnAndRow($j+6, 5, "Chiều cao\n (cm)");
        $excelAdapter->setCellValueByColumnAndRow($j+7, 5, "Cân nặng\n (kg)");
        $excelAdapter->setCellValueByColumnAndRow($j+8, 5, 'Ngày cân đo');
        $excelAdapter->setCellValueByColumnAndRow($j+9, 5, 'Chiều cao trung bình');
        $excelAdapter->setCellValueByColumnAndRow($j+10, 5, 'Cân nặng trung bình');
        $excelAdapter->setCellValueByColumnAndRow($j+11, 5, 'Kết quả');
        $excelAdapter->SetCellTextFormat('A5:L5', true, 'ffffff', '12', 'Arial');
        $excelAdapter->SetCellColor('A5:L5', '009acd');//#009acd
        //$excelAdapter->getActiveSheet()->getStyle('A5:K5')->getAlignment()->setWrapText(true);


        if (!empty($bmiResults)) {
            //get number of status
            $aStatus = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:ProfileBmiResult')
                ->get(array(
                    'classIn' => $classCondition,
                    'month_weight_range' => $startEnd,
                    'countResultType'=>1
                ));
            //var_dump($aStatus);die();
            $i = 6;
            foreach ($bmiResults as $item) {
                $excelAdapter->setCellValueByColumnAndRow(0, $i, $item->getSchoolClass()->getName());
                $excelAdapter->setCellValueByColumnAndRow(1, $i, $item->getProfile()->getId());
                $excelAdapter->setCellValueByColumnAndRow(2, $i, $item->getProfile()->getName());
                $excelAdapter->setCellValueByColumnAndRow(3, $i, $item->getProfile()->getDateofBirth()->format('d/m/Y'));
                $excelAdapter->setCellValueByColumnAndRow(4, $i, ($item->getProfile()->getGender()==1)?'Nam':'Nữ');
                $excelAdapter->setCellValueByColumnAndRow(5, $i, $item->getProfile()->getAddress());
                $excelAdapter->setCellValueByColumnAndRow(6, $i, $item->getHeight());
                $excelAdapter->setCellValueByColumnAndRow(7, $i, $item->getWeight());
                $excelAdapter->setCellValueByColumnAndRow(8, $i, ($item->getDayWeight())?$item->getDayWeight()->format('d/m/Y'):'');
                $excelAdapter->setCellValueByColumnAndRow(9, $i, $item->getStandardHeight());
                $excelAdapter->setCellValueByColumnAndRow(10, $i, $item->getStandardWeight());
                $excelAdapter->setCellValueByColumnAndRow(11, $i, $item->getResult());
                $i++;
            }
        }
        $i += 2;
        $sheet->mergeCells("E".($i+1).":J".($i+1));
        $excelAdapter->SetCellTextFormat("D".($i+1).":K".($i+1), true, 'ffffff', '12', 'Arial');
        $excelAdapter->SetCellColor("D".($i+1).":K".($i+1), '009acd');//#009acd
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 1, 'TỔNG KẾT');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 1, 'Tình trạng');
        $excelAdapter->setCellValueByColumnAndRow(9, $i + 1, 'Số trẻ (' . count($bmiResults) . ')');
        $excelAdapter->setCellValueByColumnAndRow(10, $i + 1, 'Giải thích');
        $sheet->mergeCells("D".($i+2).":I".($i+2));
        $i+=2;
        if (!empty($aStatus)) {
            foreach ($aStatus as $status){
                $excelAdapter->setCellValueByColumnAndRow(3, $i, $status['result']);
                $excelAdapter->setCellValueByColumnAndRow(9, $i, $status['countResultType']);
                $i+=1;
            }
        }
        $i+=2;

        $excelAdapter->setCellValueByColumnAndRow(1, $i, 'TỔNG HỢP');
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 1, 'TÌNH TRẠNG');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 1, 'SỐ HS');
        $excelAdapter->setCellValueByColumnAndRow(4, $i + 1, 'TỶ LỆ');

        $excelAdapter->setCellValueByColumnAndRow(1, $i + 2, 'Bình thường');
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 3, 'Suy dinh dưỡng thấp còi');
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 4, 'Suy dinh dưỡng nhẹ cân');
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 5, 'Tổng');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 5, count($bmiResults));
        $excelAdapter->setCellValueByColumnAndRow(4, $i + 5, "100%");

        foreach(range('A','Z') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        return $excelAdapter->export('danh-sach-can-do-khoi-lop.xls');
        // }
    }

    public function exportForDistrictAction(Request $request)
    {
        $parameters = $request->query->all();
        $schools = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:School')
            ->get(array('district' => $parameters['district']));

        $schoolYear = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:SchoolYear')
            ->find($parameters['year']);
        $startEnd = $this->calculateRangeDate($parameters['month'], $schoolYear->getYear());
        $classCondition = '';
        if (!empty($schools)) {
            foreach ($schools as $si => $school) {
                if (!empty($school)) {
                    $classes = $school->getClasses();
                    if (!empty($classes)) {
                        foreach ($classes as $i => $class) {
                            $classCondition .= $class->getId();
                            if ($i != count($classes) - 1) $classCondition .= ', ';
                        }
                    }
                }
                if ($si != count($schools) - 1) $classCondition .= ', ';
            }

        }
        if ($classCondition != '') {
            $bmiResults = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:ProfileBmiResult')
                ->get(array(
                    'classIn' => $classCondition,
                    'month_weight_range' => $startEnd,
                    'notCategory' => 3,
                ), array('profile_name' => 'ASC'));
        } else $bmiResults = array();

        $excelAdapter = $this->get('application.excel.export');
        $excelAdapter->setActiveSheet(0);
        $sheet = $excelAdapter->getActiveSheet();
        $excelAdapter->setCellValueByColumnAndRow(0, 1, $parameters['district']);
        $sheet->mergeCells('A1:C1');
        $sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excelAdapter->setCellValueByColumnAndRow(5, 1, 'DANH SÁCH HỌC SINH THEO QUẬN');
        $sheet->mergeCells('F1:I1');
        $sheet->getStyle('F1:I1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excelAdapter->setCellValueByColumnAndRow(5, 2, 'Năm học '.$schoolYear->getName());
        $sheet->mergeCells('F2:I2');
        $sheet->getStyle('F2:I2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excelAdapter->setCellValueByColumnAndRow(5, 3, 'Tên Quận: '.$parameters['district']);
        $sheet->mergeCells('F3:I3');
        $sheet->getStyle('F3:I3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //$excelAdapter->setCellValueByColumnAndRow(5, 4, 'Tên giáo viên phụ trách: '.$teacher);
        $sheet->mergeCells('F4:I4');
        $sheet->getStyle('F4:I4')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //column
        $j=0;
        $excelAdapter->setCellValueByColumnAndRow($j, 5, 'Trường');
        $excelAdapter->setCellValueByColumnAndRow($j+1, 5, 'Lớp');
        $excelAdapter->setCellValueByColumnAndRow($j+2, 5, 'Mã học sinh');
        $excelAdapter->setCellValueByColumnAndRow($j+3, 5, 'Họ và tên');
        $excelAdapter->setCellValueByColumnAndRow($j+4, 5, "Ngày sinh\n (dd/mm/yyyy)");
        $excelAdapter->setCellValueByColumnAndRow($j+5, 5, 'Giới tính');
        $excelAdapter->setCellValueByColumnAndRow($j+6, 5, 'Địa chỉ');
        $excelAdapter->setCellValueByColumnAndRow($j+7, 5, "Chiều cao\n (cm)");
        $excelAdapter->setCellValueByColumnAndRow($j+8, 5, "Cân nặng\n (kg)");
        $excelAdapter->setCellValueByColumnAndRow($j+9, 5, 'Ngày cân đo');
        $excelAdapter->setCellValueByColumnAndRow($j+10, 5, 'Chiều cao trung bình');
        $excelAdapter->setCellValueByColumnAndRow($j+11, 5, 'Cân nặng trung bình');
        $excelAdapter->setCellValueByColumnAndRow($j+12, 5, 'Kết quả');
        $excelAdapter->SetCellTextFormat('A5:M5', true, 'ffffff', '12', 'Arial');
        $excelAdapter->SetCellColor('A5:M5', '009acd');//#009acd
        //$excelAdapter->getActiveSheet()->getStyle('A5:K5')->getAlignment()->setWrapText(true);


        if (!empty($bmiResults)) {
            //get number of status
            $aStatus = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:ProfileBmiResult')
                ->get(array(
                    'classIn' => $classCondition,
                    'month_weight_range' => $startEnd,
                    'countResultType'=>1
                ));
            //var_dump($aStatus);die();
            $i = 6;
            foreach ($bmiResults as $item) {
                $excelAdapter->setCellValueByColumnAndRow(0, $i, $item->getSchoolClass()->getSchool()->getName());
                $excelAdapter->setCellValueByColumnAndRow(1, $i, $item->getSchoolClass()->getName());
                $excelAdapter->setCellValueByColumnAndRow(2, $i, $item->getProfile()->getId());
                $excelAdapter->setCellValueByColumnAndRow(3, $i, $item->getProfile()->getName());
                $excelAdapter->setCellValueByColumnAndRow(4, $i, $item->getProfile()->getDateofBirth()->format('d/m/Y'));
                $excelAdapter->setCellValueByColumnAndRow(5, $i, ($item->getProfile()->getGender()==1)?'Nam':'Nữ');
                $excelAdapter->setCellValueByColumnAndRow(6, $i, $item->getProfile()->getAddress());
                $excelAdapter->setCellValueByColumnAndRow(7, $i, $item->getHeight());
                $excelAdapter->setCellValueByColumnAndRow(8, $i, $item->getWeight());
                $excelAdapter->setCellValueByColumnAndRow(9, $i, ($item->getDayWeight())?$item->getDayWeight()->format('d/m/Y'):'');
                $excelAdapter->setCellValueByColumnAndRow(10, $i, $item->getStandardHeight());
                $excelAdapter->setCellValueByColumnAndRow(11, $i, $item->getStandardWeight());
                $excelAdapter->setCellValueByColumnAndRow(12, $i, $item->getResult());
                $i++;
            }
        }
        $i += 2;
        $sheet->mergeCells("E".($i+1).":J".($i+1));
        $excelAdapter->SetCellTextFormat("D".($i+1).":K".($i+1), true, 'ffffff', '12', 'Arial');
        $excelAdapter->SetCellColor("D".($i+1).":K".($i+1), '009acd');//#009acd
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 1, 'TỔNG KẾT');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 1, 'Tình trạng');
        $excelAdapter->setCellValueByColumnAndRow(9, $i + 1, 'Số trẻ (' . count($bmiResults) . ')');
        $excelAdapter->setCellValueByColumnAndRow(10, $i + 1, 'Giải thích');
        $sheet->mergeCells("D".($i+2).":I".($i+2));
        $i+=2;
        if (!empty($aStatus)) {
            foreach ($aStatus as $status){
                $excelAdapter->setCellValueByColumnAndRow(3, $i, $status['result']);
                $excelAdapter->setCellValueByColumnAndRow(9, $i, $status['countResultType']);
                $i+=1;
            }
        }
        $i+=2;

        $excelAdapter->setCellValueByColumnAndRow(1, $i, 'TỔNG HỢP');
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 1, 'TÌNH TRẠNG');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 1, 'SỐ HS');
        $excelAdapter->setCellValueByColumnAndRow(4, $i + 1, 'TỶ LỆ');

        $excelAdapter->setCellValueByColumnAndRow(1, $i + 2, 'Bình thường');
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 3, 'Suy dinh dưỡng thấp còi');
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 4, 'Suy dinh dưỡng nhẹ cân');
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 5, 'Tổng');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 5, count($bmiResults));
        $excelAdapter->setCellValueByColumnAndRow(4, $i + 5, "100%");

        foreach(range('A','Z') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        return $excelAdapter->export('danh-sach-can-do-quan.xls');
    }

    public function exportForCityAction(Request $request)
    {
        $parameters = $request->query->all();
        $schools = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:School')
            ->get(array('city' => $parameters['city']));

        $schoolYear = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:SchoolYear')
            ->find($parameters['year']);
        $startEnd = $this->calculateRangeDate($parameters['month'], $schoolYear->getYear());
        $classCondition = '';
        if (!empty($schools)) {
            foreach ($schools as $si => $school) {
                if (!empty($school)) {
                    $classes = $school->getClasses();
                    if (!empty($classes)) {
                        foreach ($classes as $i => $class) {
                            $classCondition .= $class->getId();
                            if ($i != count($classes) - 1) $classCondition .= ', ';
                        }
                    }
                }
                if ($si != count($schools) - 1) $classCondition .= ', ';
            }

        }
        if ($classCondition != '') {
            $bmiResults = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:ProfileBmiResult')
                ->get(array(
                    'classIn' => $classCondition,
                    'month_weight_range' => $startEnd,
                    'notCategory' => 3,
                ), array('profile_name' => 'ASC'));
        } else $bmiResults = array();

        $excelAdapter = $this->get('application.excel.export');
        $excelAdapter->setActiveSheet(0);
        $sheet = $excelAdapter->getActiveSheet();
        $excelAdapter->setCellValueByColumnAndRow(0, 1, $parameters['city']);
        $sheet->mergeCells('A1:C1');
        $sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excelAdapter->setCellValueByColumnAndRow(5, 1, 'DANH SÁCH HỌC SINH THEO TÌNH THÀNH');
        $sheet->mergeCells('F1:I1');
        $sheet->getStyle('F1:I1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excelAdapter->setCellValueByColumnAndRow(5, 2, 'Năm học '.$schoolYear->getName());
        $sheet->mergeCells('F2:I2');
        $sheet->getStyle('F2:I2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excelAdapter->setCellValueByColumnAndRow(5, 3, 'Tên tỉnh thành: '.$parameters['city']);
        $sheet->mergeCells('F3:I3');
        $sheet->getStyle('F3:I3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //$excelAdapter->setCellValueByColumnAndRow(5, 4, 'Tên giáo viên phụ trách: '.$teacher);
        $sheet->mergeCells('F4:I4');
        $sheet->getStyle('F4:I4')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //column
        $j=0;
        $excelAdapter->setCellValueByColumnAndRow($j, 5, 'Quận');
        $excelAdapter->setCellValueByColumnAndRow($j+1, 5, 'Trường');
        $excelAdapter->setCellValueByColumnAndRow($j+2, 5, 'Lớp');
        $excelAdapter->setCellValueByColumnAndRow($j+3, 5, 'Mã học sinh');
        $excelAdapter->setCellValueByColumnAndRow($j+4, 5, 'Họ và tên');
        $excelAdapter->setCellValueByColumnAndRow($j+5, 5, "Ngày sinh\n (dd/mm/yyyy)");
        $excelAdapter->setCellValueByColumnAndRow($j+6, 5, 'Giới tính');
        $excelAdapter->setCellValueByColumnAndRow($j+7, 5, 'Địa chỉ');
        $excelAdapter->setCellValueByColumnAndRow($j+8, 5, "Chiều cao\n (cm)");
        $excelAdapter->setCellValueByColumnAndRow($j+9, 5, "Cân nặng\n (kg)");
        $excelAdapter->setCellValueByColumnAndRow($j+10, 5, 'Ngày cân đo');
        $excelAdapter->setCellValueByColumnAndRow($j+11, 5, 'Chiều cao trung bình');
        $excelAdapter->setCellValueByColumnAndRow($j+12, 5, 'Cân nặng trung bình');
        $excelAdapter->setCellValueByColumnAndRow($j+13, 5, 'Kết quả');
        $excelAdapter->SetCellTextFormat('A5:N5', true, 'ffffff', '12', 'Arial');
        $excelAdapter->SetCellColor('A5:N5', '009acd');//#009acd
        //$excelAdapter->getActiveSheet()->getStyle('A5:K5')->getAlignment()->setWrapText(true);


        if (!empty($bmiResults)) {
            //get number of status
            $aStatus = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:ProfileBmiResult')
                ->get(array(
                    'classIn' => $classCondition,
                    'month_weight_range' => $startEnd,
                    'countResultType'=>1
                ));
            //var_dump($aStatus);die();
            $i = 6;
            foreach ($bmiResults as $item) {
                $excelAdapter->setCellValueByColumnAndRow(0, $i, $item->getSchoolClass()->getSchool()->getDistrict());
                $excelAdapter->setCellValueByColumnAndRow(1, $i, $item->getSchoolClass()->getSchool()->getName());
                $excelAdapter->setCellValueByColumnAndRow(2, $i, $item->getSchoolClass()->getName());
                $excelAdapter->setCellValueByColumnAndRow(3, $i, $item->getProfile()->getId());
                $excelAdapter->setCellValueByColumnAndRow(4, $i, $item->getProfile()->getName());
                $excelAdapter->setCellValueByColumnAndRow(5, $i, $item->getProfile()->getDateofBirth()->format('d/m/Y'));
                $excelAdapter->setCellValueByColumnAndRow(6, $i, ($item->getProfile()->getGender()==1)?'Nam':'Nữ');
                $excelAdapter->setCellValueByColumnAndRow(7, $i, $item->getProfile()->getAddress());
                $excelAdapter->setCellValueByColumnAndRow(8, $i, $item->getHeight());
                $excelAdapter->setCellValueByColumnAndRow(9, $i, $item->getWeight());
                $excelAdapter->setCellValueByColumnAndRow(10, $i, ($item->getDayWeight())?$item->getDayWeight()->format('d/m/Y'):'');
                $excelAdapter->setCellValueByColumnAndRow(11, $i, $item->getStandardHeight());
                $excelAdapter->setCellValueByColumnAndRow(12, $i, $item->getStandardWeight());
                $excelAdapter->setCellValueByColumnAndRow(13, $i, $item->getResult());
                $i++;
            }
        }
        $i += 2;
        $sheet->mergeCells("E".($i+1).":J".($i+1));
        $excelAdapter->SetCellTextFormat("D".($i+1).":K".($i+1), true, 'ffffff', '12', 'Arial');
        $excelAdapter->SetCellColor("D".($i+1).":K".($i+1), '009acd');//#009acd
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 1, 'TỔNG KẾT');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 1, 'Tình trạng');
        $excelAdapter->setCellValueByColumnAndRow(9, $i + 1, 'Số trẻ (' . count($bmiResults) . ')');
        $excelAdapter->setCellValueByColumnAndRow(10, $i + 1, 'Giải thích');
        $sheet->mergeCells("D".($i+2).":I".($i+2));
        $i+=2;
        if (!empty($aStatus)) {
            foreach ($aStatus as $status){
                $excelAdapter->setCellValueByColumnAndRow(3, $i, $status['result']);
                $excelAdapter->setCellValueByColumnAndRow(9, $i, $status['countResultType']);
                $i+=1;
            }
        }
        $i+=2;

        $excelAdapter->setCellValueByColumnAndRow(1, $i, 'TỔNG HỢP');
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 1, 'TÌNH TRẠNG');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 1, 'SỐ HS');
        $excelAdapter->setCellValueByColumnAndRow(4, $i + 1, 'TỶ LỆ');

        $excelAdapter->setCellValueByColumnAndRow(1, $i + 2, 'Bình thường');
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 3, 'Suy dinh dưỡng thấp còi');
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 4, 'Suy dinh dưỡng nhẹ cân');
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 5, 'Tổng');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 5, count($bmiResults));
        $excelAdapter->setCellValueByColumnAndRow(4, $i + 5, "100%");

        foreach(range('A','Z') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        return $excelAdapter->export('danh-sach-can-do-thanh-pho.xls');
    }
    public function exportForAllAction(Request $request)
    {
        $parameters = $request->query->all();
        $schools = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:School')
            ->get(array());

        $schoolYear = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:SchoolYear')
            ->find($parameters['year']);
        $startEnd = $this->calculateRangeDate($parameters['month'], $schoolYear->getYear());
        /*
        $classCondition = '';
        if (!empty($schools)) {
            foreach ($schools as $si => $school) {
                if (!empty($school)) {
                    $classes = $school->getClasses();
                    if (!empty($classes)) {
                        foreach ($classes as $i => $class) {
                            $classCondition .= $class->getId();
                            if ($i != count($classes) - 1) $classCondition .= ', ';
                        }
                    }
                }
                if ($si != count($schools) - 1) $classCondition .= ', ';
            }

        }
        */
        //if ($classCondition != '') {
            $bmiResults = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:ProfileBmiResult')
                ->get(array(
                    //'classIn' => $classCondition,
                    'month_weight_range' => $startEnd,
                    'notCategory' => 3,
                ), array('profile_name' => 'ASC'));
        //} else $bmiResults = array();

        $excelAdapter = $this->get('application.excel.export');
        $excelAdapter->setActiveSheet(0);
        $sheet = $excelAdapter->getActiveSheet();
        $excelAdapter->setCellValueByColumnAndRow(0, 1, 'TOÀN QUỐC');
        $sheet->mergeCells('A1:C1');
        $sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excelAdapter->setCellValueByColumnAndRow(5, 1, 'DANH SÁCH HỌC SINH TOÀN QUỐC');
        $sheet->mergeCells('F1:I1');
        $sheet->getStyle('F1:I1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excelAdapter->setCellValueByColumnAndRow(5, 2, 'Năm học '.$schoolYear->getName());
        $sheet->mergeCells('F2:I2');
        $sheet->getStyle('F2:I2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //$excelAdapter->setCellValueByColumnAndRow(5, 3, 'Tên tỉnh thành: '.$parameters['city']);
        //$sheet->mergeCells('F3:I3');
        //$sheet->getStyle('F3:I3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //$excelAdapter->setCellValueByColumnAndRow(5, 4, 'Tên giáo viên phụ trách: '.$teacher);
        $sheet->mergeCells('F4:I4');
        $sheet->getStyle('F4:I4')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //column
        $j=0;
        $excelAdapter->setCellValueByColumnAndRow($j, 5, 'Tỉnh thành');
        $excelAdapter->setCellValueByColumnAndRow($j+1, 5, 'Quận');
        $excelAdapter->setCellValueByColumnAndRow($j+2, 5, 'Trường');
        $excelAdapter->setCellValueByColumnAndRow($j+3, 5, 'Lớp');
        $excelAdapter->setCellValueByColumnAndRow($j+4, 5, 'Mã học sinh');
        $excelAdapter->setCellValueByColumnAndRow($j+5, 5, 'Họ và tên');
        $excelAdapter->setCellValueByColumnAndRow($j+6, 5, "Ngày sinh\n (dd/mm/yyyy)");
        $excelAdapter->setCellValueByColumnAndRow($j+7, 5, 'Giới tính');
        $excelAdapter->setCellValueByColumnAndRow($j+8, 5, 'Địa chỉ');
        $excelAdapter->setCellValueByColumnAndRow($j+9, 5, "Chiều cao\n (cm)");
        $excelAdapter->setCellValueByColumnAndRow($j+10, 5, "Cân nặng\n (kg)");
        $excelAdapter->setCellValueByColumnAndRow($j+11, 5, 'Ngày cân đo');
        $excelAdapter->setCellValueByColumnAndRow($j+12, 5, 'Chiều cao trung bình');
        $excelAdapter->setCellValueByColumnAndRow($j+13, 5, 'Cân nặng trung bình');
        $excelAdapter->setCellValueByColumnAndRow($j+14, 5, 'Kết quả');
        $excelAdapter->SetCellTextFormat('A5:O5', true, 'ffffff', '12', 'Arial');
        $excelAdapter->SetCellColor('A5:O5', '009acd');//#009acd
        //$excelAdapter->getActiveSheet()->getStyle('A5:K5')->getAlignment()->setWrapText(true);


        if (!empty($bmiResults)) {
            //get number of status
            $aStatus = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:ProfileBmiResult')
                ->get(array(
                    //'classIn' => $classCondition,
                    'month_weight_range' => $startEnd,
                    'countResultType'=>1
                ));
            //var_dump($aStatus);die();
            $i = 6;
            foreach ($bmiResults as $item) {
                $excelAdapter->setCellValueByColumnAndRow(0, $i, $item->getSchoolClass()->getSchool()->getCity());
                $excelAdapter->setCellValueByColumnAndRow(1, $i, $item->getSchoolClass()->getSchool()->getDistrict());
                $excelAdapter->setCellValueByColumnAndRow(2, $i, $item->getSchoolClass()->getSchool()->getName());
                $excelAdapter->setCellValueByColumnAndRow(3, $i, $item->getSchoolClass()->getName());
                $excelAdapter->setCellValueByColumnAndRow(4, $i, $item->getProfile()->getId());
                $excelAdapter->setCellValueByColumnAndRow(5, $i, $item->getProfile()->getName());
                $excelAdapter->setCellValueByColumnAndRow(6, $i, $item->getProfile()->getDateofBirth()->format('d/m/Y'));
                $excelAdapter->setCellValueByColumnAndRow(7, $i, ($item->getProfile()->getGender()==1)?'Nam':'Nữ');
                $excelAdapter->setCellValueByColumnAndRow(8, $i, $item->getProfile()->getAddress());
                $excelAdapter->setCellValueByColumnAndRow(9, $i, $item->getHeight());
                $excelAdapter->setCellValueByColumnAndRow(10, $i, $item->getWeight());
                $excelAdapter->setCellValueByColumnAndRow(11, $i, ($item->getDayWeight())?$item->getDayWeight()->format('d/m/Y'):'');
                $excelAdapter->setCellValueByColumnAndRow(12, $i, $item->getStandardHeight());
                $excelAdapter->setCellValueByColumnAndRow(13, $i, $item->getStandardWeight());
                $excelAdapter->setCellValueByColumnAndRow(14, $i, $item->getResult());
                $i++;
            }
        }
        $i += 2;
        $sheet->mergeCells("E".($i+1).":J".($i+1));
        $excelAdapter->SetCellTextFormat("D".($i+1).":K".($i+1), true, 'ffffff', '12', 'Arial');
        $excelAdapter->SetCellColor("D".($i+1).":K".($i+1), '009acd');//#009acd
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 1, 'TỔNG KẾT');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 1, 'Tình trạng');
        $excelAdapter->setCellValueByColumnAndRow(9, $i + 1, 'Số trẻ (' . count($bmiResults) . ')');
        $excelAdapter->setCellValueByColumnAndRow(10, $i + 1, 'Giải thích');
        $sheet->mergeCells("D".($i+2).":I".($i+2));
        $i+=2;
        if (!empty($aStatus)) {
            foreach ($aStatus as $status){
                $excelAdapter->setCellValueByColumnAndRow(3, $i, $status['result']);
                $excelAdapter->setCellValueByColumnAndRow(9, $i, $status['countResultType']);
                $i+=1;
            }
        }
        $i+=2;

        $excelAdapter->setCellValueByColumnAndRow(1, $i, 'TỔNG HỢP');
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 1, 'TÌNH TRẠNG');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 1, 'SỐ HS');
        $excelAdapter->setCellValueByColumnAndRow(4, $i + 1, 'TỶ LỆ');

        $excelAdapter->setCellValueByColumnAndRow(1, $i + 2, 'Bình thường');
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 3, 'Suy dinh dưỡng thấp còi');
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 4, 'Suy dinh dưỡng nhẹ cân');
        $excelAdapter->setCellValueByColumnAndRow(1, $i + 5, 'Tổng');
        $excelAdapter->setCellValueByColumnAndRow(3, $i + 5, count($bmiResults));
        $excelAdapter->setCellValueByColumnAndRow(4, $i + 5, "100%");

        foreach(range('A','Z') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        return $excelAdapter->export('danh-sach-can-do-toan-quoc.xls');
    }

    private function getFinalResult($bmiResults, $finalResult)
    {
        $finalResult['bt'][1] = 0;
        $finalResult['tc'][1] = 0;
        $finalResult['bp'][1] = 0;
        $finalResult['sddtg'][1] = 0;
        $finalResult['sddttc'][1] = 0;
        $finalResult['tc_tc'][1] = 0;
        $finalResult['bp_tc'][1] = 0;
        $finalResult['sdd_tc'][1] = 0;
        $finalResult['bt'][2] = 0;
        $finalResult['tc'][2] = 0;
        $finalResult['bp'][2] = 0;
        $finalResult['sddtg'][2] = 0;
        $finalResult['sddttc'][2] = 0;
        $finalResult['tc_tc'][2] = 0;
        $finalResult['bp_tc'][2] = 0;
        $finalResult['sdd_tc'][2] = 0;

        if (!empty($bmiResults)) {
            foreach ($bmiResults as $result) {
                if ($result->getCategory() == 1) {
                    if ($result->getResultType() <= 4) {
                        $finalResult['bt'][$result->getGender()] += 1;
                    } else if ($result->getResultType() == 11.1 or $result->getResultType() == 12.1) {
                        $finalResult['tc'][$result->getGender()] += 1;
                    } else if ($result->getResultType() == 11.2 or $result->getResultType() == 12.2) {
                        $finalResult['bp'][$result->getGender()] += 1;
                    } else if ($result->getResultType() == 5.2 or $result->getResultType() == 5.4
                        or $result->getResultType() == 6.1 or $result->getResultType() == 6.2
                        or $result->getResultType() == 7.1 or $result->getResultType() == 7.2
                    ) {
                        $finalResult['sddtg'][$result->getGender()] += 1;
                    } else if ($result->getResultType() == 8.1 or $result->getResultType() == 8.2
                        or $result->getResultType() == 9.1 or $result->getResultType() == 9.2
                    ) {
                        $finalResult['sddttc'][$result->getGender()] += 1;
                    } else if ($result->getResultType() == 10.1 or $result->getResultType() == 10.3) {
                        $finalResult['tc_tc'][$result->getGender()] += 1;
                    } else if ($result->getResultType() == 10.2 or $result->getResultType() == 10.4) {
                        $finalResult['bp_tc'][$result->getGender()] += 1;
                    } else {
                        $finalResult['sdd_tc'][$result->getGender()] += 1;
                    }
                } else if ($result->getCategory() == 2) {
                    if ($result->getResultType() <= 4) {
                        $finalResult['bt'][$result->getGender()] += 1;
                    } else if ($result->getResultType() == 10 or $result->getResultType() == 11) {
                        $finalResult['tc'][$result->getGender()] += 1;
                    } else if ($result->getResultType() == 13 or $result->getResultType() == 14) {
                        $finalResult['bp'][$result->getGender()] += 1;
                    } else if ($result->getResultType() == 5 or $result->getResultType() == 6) {
                        $finalResult['sddtg'][$result->getGender()] += 1;
                    } else if ($result->getResultType() == 8 or $result->getResultType() == 9) {
                        $finalResult['sddttc'][$result->getGender()] += 1;
                    } else if ($result->getResultType() == 12) {
                        $finalResult['tc_tc'][$result->getGender()] += 1;
                    } else if ($result->getResultType() == 15) {
                        $finalResult['bp_tc'][$result->getGender()] += 1;
                    } else $finalResult['sdd_tc'][$result->getGender()] += 1;
                } else {

                }
            }
        }
        return $finalResult;
    }
}
