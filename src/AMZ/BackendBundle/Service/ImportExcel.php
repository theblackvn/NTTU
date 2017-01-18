<?php

namespace AMZ\BackendBundle\Service;

class ImportExcel
{
    public function import()
    {
        $phpExcel = \PHPExcel_IOFactory::load(__DIR__ . '\test-import.xlsx');
        $sheet = $phpExcel->getActiveSheet();
        $numberRows = $sheet->getHighestRow();
        $numberColumns = $sheet->getHighestColumn(1);
        for ($i = 1; $i <= $numberRows; $i++) {
            $value = $sheet->getCellByColumnAndRow(0, $i);
            echo $value . '</br>';
        }
    }
}