<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use Maatwebsite\Excel\Concerns\WithCharts;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\DB;

class GrafikLabExport implements WithCharts, WithTitle
{

    public function __construct($mesh, $mesin)
    {
        $this->mesh = $mesh;
    }

    public function charts()
    {
        $labels     = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Mesh ".$this->mesh."'" . '!A$4', null)];
        $categories = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Mesh ".$this->mesh."'" . '!A$1', null)];
        $values     = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Mesh ".$this->mesh."'" . '!$B$4', null)];

        $layout = new Layout();
        
        $layout->setShowVal(true);
        $layout->setShowPercent(true);

        $chartSSA = new Chart(
            'chart',
            new Title('Mesh ' . $this->mesh . ' (SSA)'),
            null,
            new PlotArea($layout, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, 30), $labels, $categories, $values)
            ])
        );

        $chartSSA->setTopLeftPosition('A1');
        $chartSSA->setBottomRightPosition('H20');

        return [$chartSSA];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Grafik Mesh ' . $this->mesh;
    }
}
