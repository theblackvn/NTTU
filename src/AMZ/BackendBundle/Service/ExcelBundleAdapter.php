<?php

namespace AMZ\BackendBundle\Service;

use Liuggio\ExcelBundle\Factory;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ExcelBundleAdapter
{
    private $excel;
    private $excelObject;
    private $writer;
    private $response;

    public function __construct(Factory $phpExcel)
    {
        $this->excel = $phpExcel;
        $this->excelObject = $this->excel->createPHPExcelObject();
        $this->writer = $this->excel->createWriter($this->excelObject, 'Excel5');
        $this->response = $this->excel->createStreamedResponse($this->writer);
    }

    private function getActiveSheet()
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