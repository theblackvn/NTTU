<?php

namespace AMZ\BackendBundle\Service;

use Liuggio\ExcelBundle\Factory;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ExportExcel
{
    private $excel;
    private $excelObject;
    private $writer;
    private $response;

//        $excelAdapter = $this->get('application.excel');
//        $excelAdapter->setActiveSheet(0);
//        $excelAdapter->setCellValueByColumnAndRow(0, 1, 'Mã thợ sơn');
//        $excelAdapter->setCellValueByColumnAndRow(1, 1, 'AA');
//        $excelAdapter->setCellValueByColumnAndRow(2, 1, 'BB');
//        return $excelAdapter->export('test.xls');

    public function __construct(Factory $phpExcel)
    {
        $this->excel = $phpExcel;
        $this->excelObject = $this->excel->createPHPExcelObject();
        $this->writer = $this->excel->createWriter($this->excelObject, 'Excel5');
        $this->response = $this->excel->createStreamedResponse($this->writer);
    }

    public function getActiveSheet()
    {
        return $this->excelObject->getActiveSheet();
    }

    private function makeDisposition($file)
    {
        return $this->response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $file
        );
    }

    public function setActiveSheet($sheetNumber = 0)
    {
        $this->excelObject->setActiveSheetIndex($sheetNumber);
    }

    public function setCellValueByColumnAndRow($column, $row, $value)
    {
        $this->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $value);
    }

    public function setCellColor($cells,$color){
        return $this->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                'rgb' => $color
            )
        ));
    }

    public function setCellTextFormat($cells, $isBold=false,$color='ffffff',$size=11,$font='Arial'){
        $styleArray = array(
            'font'  => array(
                'bold'  => $isBold,
                'color' => array('rgb' => $color),
                'size'  => $size,
                'name'  => $font
            ));
        return $this->getActiveSheet()->getStyle($cells)->applyFromArray($styleArray);
    }

    public function export($file)
    {
        $dispositionHeader = $this->makeDisposition($file);
        $this->response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $this->response->headers->set('Pragma', 'public');
        $this->response->headers->set('Cache-Control', 'maxage=1');
        $this->response->headers->set('Content-Disposition', $dispositionHeader);

        return $this->response;
    }
}