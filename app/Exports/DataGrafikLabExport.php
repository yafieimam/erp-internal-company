<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use Maatwebsite\Excel\Concerns\WithCharts;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DataGrafikLabExport implements WithCharts, FromView, WithTitle
{
    public function __construct($mesh, $mesin, $nama_mesin)
    {
        $this->mesh = $mesh;
        $this->mesin = $mesin;
        $this->nama_mesin = $nama_mesin;
    }

    public function charts()
    {
        $labels_SSA = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Mesh ".$this->mesh.	" Mesin " . $this->nama_mesin . "'" . '!$A$3', null)];
        $categories_SSA = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Mesh ".$this->mesh. " Mesin " . $this->nama_mesin . "'" . '!$A$3', null)];
        $values_SSA = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Mesh ".$this->mesh. " Mesin " . $this->nama_mesin . "'" . '!$A$4:$A$34', null)];

        $labels_D50 = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Mesh ".$this->mesh.	" Mesin " . $this->nama_mesin . "'" . '!$B$3', null)];
        $categories_D50 = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Mesh ".$this->mesh. " Mesin " . $this->nama_mesin . "'" . '!$B$3', null)];
        $values_D50 = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Mesh ".$this->mesh. " Mesin " . $this->nama_mesin . "'" . '!$B$4:$B$34', null)];

        $labels_WHITENESS = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Mesh ".$this->mesh.	" Mesin " . $this->nama_mesin . "'" . '!$C$3', null)];
        $categories_WHITENESS = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Mesh ".$this->mesh. " Mesin " . $this->nama_mesin . "'" . '!$C$3', null)];
        $values_WHITENESS = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Mesh ".$this->mesh. " Mesin " . $this->nama_mesin . "'" . '!$C$4:$C$34', null)];

        $labels_MOISTURE = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Mesh ".$this->mesh.	" Mesin " . $this->nama_mesin . "'" . '!$D$3', null)];
        $categories_MOISTURE = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Mesh ".$this->mesh. " Mesin " . $this->nama_mesin . "'" . '!$D$3', null)];
        $values_MOISTURE = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Mesh ".$this->mesh. " Mesin " . $this->nama_mesin . "'" . '!$D$4:$D$34', null)];

        $labels_RESIDUE = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Mesh ".$this->mesh.	" Mesin " . $this->nama_mesin . "'" . '!$E$3', null)];
        $categories_RESIDUE = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Mesh ".$this->mesh. " Mesin " . $this->nama_mesin . "'" . '!$E$3', null)];
        $values_RESIDUE = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Mesh ".$this->mesh. " Mesin " . $this->nama_mesin . "'" . '!$E$4:$E$34', null)];

        $chartSSA = new Chart(
            'chart',
            new Title('Mesh ' . $this->mesh . ' (SSA) Mesin ' . $this->nama_mesin),
            null,
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_SSA) - 1), $labels_SSA, $categories_SSA, $values_SSA)
            ])
        );

        $chartD50 = new Chart(
            'chart',
            new Title('Mesh ' . $this->mesh . ' (D50) Mesin ' . $this->nama_mesin),
            null,
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_D50) - 1), $labels_D50, $categories_D50, $values_D50)
            ])
        );

        $chartWHITENESS = new Chart(
            'chart',
            new Title('Mesh ' . $this->mesh . ' (SSA) Mesin ' . $this->nama_mesin),
            null,
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_WHITENESS) - 1), $labels_WHITENESS, $categories_WHITENESS, $values_WHITENESS)
            ])
        );

        $chartMOISTURE = new Chart(
            'chart',
            new Title('Mesh ' . $this->mesh . ' (SSA) Mesin ' . $this->nama_mesin),
            null,
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_MOISTURE) - 1), $labels_MOISTURE, $categories_MOISTURE, $values_MOISTURE)
            ])
        );

        $chartRESIDUE = new Chart(
            'chart',
            new Title('Mesh ' . $this->mesh . ' (SSA) Mesin ' . $this->nama_mesin),
            null,
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_RESIDUE) - 1), $labels_RESIDUE, $categories_RESIDUE, $values_RESIDUE)
            ])
        );

        $chartSSA->setTopLeftPosition('H2');
        $chartSSA->setBottomRightPosition('O16');

        $chartD50->setTopLeftPosition('Q2');
        $chartD50->setBottomRightPosition('X16');

        $chartWHITENESS->setTopLeftPosition('H19');
        $chartWHITENESS->setBottomRightPosition('O33');

        $chartMOISTURE->setTopLeftPosition('Q19');
        $chartMOISTURE->setBottomRightPosition('X33');

        $chartRESIDUE->setTopLeftPosition('H36');
        $chartRESIDUE->setBottomRightPosition('O50');

        return [$chartSSA, $chartD50, $chartWHITENESS, $chartMOISTURE, $chartRESIDUE];
    }

    public function view(): View
    {
    	$currentMonth = date('m');
        $currentYear = date('Y');

    	$data_lab = DB::table('laporan_hasil_produksi_lab as lab')->select('lap.tanggal_laporan_produksi', 'lab.ssa', 'lab.d50', 'lab.cie86', 'lab.moisture', 'lab.residue')->join('laporan_hasil_produksi as lap', 'lap.nomor_laporan_produksi', '=', 'lab.nomor_laporan_produksi')->whereRaw('MONTH(lap.tanggal_laporan_produksi) = ?',[$currentMonth])->whereRaw('YEAR(lap.tanggal_laporan_produksi) = ?',[$currentYear])->where('mesh', $this->mesh)->where('mesin', $this->mesin)->where(function ($query) { $query->whereNotNull('lab.ssa')->orWhereNotNull('lab.d50')->orWhereNotNull('lab.cie86')->orWhereNotNull('lab.moisture')->orWhereNotNull('lab.residue'); })->groupBy('lap.tanggal_laporan_produksi')->get();

    	$data_ssa = array_fill(0, 31, 0);
    	$data_d50 = array_fill(0, 31, 0);
    	$data_whiteness = array_fill(0, 31, 0);
    	$data_moisture = array_fill(0, 31, 0);
    	$data_residue = array_fill(0, 31, 0);

    	foreach($data_lab as $key => $value) {
    		$data_ssa[date('d', strtotime($value->tanggal_laporan_produksi)) - 1] = $value->ssa;
    		$data_d50[date('d', strtotime($value->tanggal_laporan_produksi)) - 1] = $value->d50;
    		$data_whiteness[date('d', strtotime($value->tanggal_laporan_produksi)) - 1] = $value->cie86;
    		$data_moisture[date('d', strtotime($value->tanggal_laporan_produksi)) - 1] = $value->moisture;
    		$data_residue[date('d', strtotime($value->tanggal_laporan_produksi)) - 1] = $value->residue;
    	}

    	// dd($data_moisture);

        return view('excel_grafik_lab', [
            'data_ssa' => $data_ssa,
            'data_d50' => $data_d50,
            'data_whiteness' => $data_whiteness,
            'data_moisture' => $data_moisture,
            'data_residue' => $data_residue,
            'mesin' => $this->nama_mesin,
            'mesh' => $this->mesh
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Mesh ' . $this->mesh . ' Mesin ' . $this->nama_mesin;
    }
}
