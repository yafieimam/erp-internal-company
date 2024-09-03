<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use Maatwebsite\Excel\Concerns\WithCharts;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;

class TrendInformasiMesinExport implements WithCharts, FromView
{
    public function __construct($periode, $mesh, $rpm)
    {
        $this->periode = $periode;
        $this->mesh = $mesh;
        $this->rpm = $rpm;
    }

    public function charts()
    {
        $labels_SA = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Trend Informasi Mesin'" . '!$B$3', null, 1)];
        $categories_SA = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Trend Informasi Mesin'" . '!$A$5:$I$5', null, 10)];
        $values_SA = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Trend Informasi Mesin'" . '!$A$6:$I$6', null, 10)];

        $labels_SB = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Trend Informasi Mesin'" . '!$B$8', null, 1)];
        $categories_SB = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Trend Informasi Mesin'" . '!$A$10:$I$10', null, 10)];
        $values_SB = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Trend Informasi Mesin'" . '!$A$11:$I$11', null, 10)];

        $labels_Mixer = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Trend Informasi Mesin'" . '!$B$13', null, 1)];
        $categories_Mixer = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Trend Informasi Mesin'" . '!$A$15:$I$15', null, 10)];
        $values_Mixer = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Trend Informasi Mesin'" . '!$A$16:$I$16', null, 10)];

        $labels_RA = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Trend Informasi Mesin'" . '!$B$18', null, 1)];
        $categories_RA = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Trend Informasi Mesin'" . '!$A$20:$I$20', null, 10)];
        $values_RA = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Trend Informasi Mesin'" . '!$A$21:$I$21', null, 10)];

        $labels_RB = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Trend Informasi Mesin'" . '!$B$23', null, 1)];
        $categories_RB = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Trend Informasi Mesin'" . '!$A$25:$I$25', null, 10)];
        $values_RB = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Trend Informasi Mesin'" . '!$A$26:$I$26', null, 10)];

        $labels_RC = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Trend Informasi Mesin'" . '!$B$28', null, 1)];
        $categories_RC = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Trend Informasi Mesin'" . '!$A$30:$I$30', null, 10)];
        $values_RC = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Trend Informasi Mesin'" . '!$A$31:$I$31', null, 10)];

        $labels_RD = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Trend Informasi Mesin'" . '!$B$33', null, 1)];
        $categories_RD = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Trend Informasi Mesin'" . '!$A$35:$I$35', null, 10)];
        $values_RD = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Trend Informasi Mesin'" . '!$A$36:$I$36', null, 10)];

        $labels_RE = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Trend Informasi Mesin'" . '!$B$38', null, 1)];
        $categories_RE = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Trend Informasi Mesin'" . '!$A$40:$I$40', null, 10)];
        $values_RE = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Trend Informasi Mesin'" . '!$A$41:$I$41', null, 10)];

        $labels_RF = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Trend Informasi Mesin'" . '!$B$43', null, 1)];
        $categories_RF = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Trend Informasi Mesin'" . '!$A$45:$I$45', null, 10)];
        $values_RF = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Trend Informasi Mesin'" . '!$A$46:$I$46', null, 10)];

        $labels_RG = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Trend Informasi Mesin'" . '!$B$48', null, 1)];
        $categories_RG = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Trend Informasi Mesin'" . '!$A$50:$I$50', null, 10)];
        $values_RG = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Trend Informasi Mesin'" . '!$A$51:$I$51', null, 10)];

        $chartSA = new Chart(
            'chart',
            new Title('Data Trend Informasi Mesin SA'),
            new Legend(),
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_SA) - 1), $labels_SA, $categories_SA, $values_SA)
            ])
        );

        $chartSB = new Chart(
            'chart',
            new Title('Data Trend Informasi Mesin SB'),
            new Legend(),
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_SB) - 1), $labels_SB, $categories_SB, $values_SB)
            ])
        );

        $chartMixer = new Chart(
            'chart',
            new Title('Data Trend Informasi Mesin Mixer'),
            new Legend(),
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_Mixer) - 1), $labels_Mixer, $categories_Mixer, $values_Mixer)
            ])
        );

        $chartRA = new Chart(
            'chart',
            new Title('Data Trend Informasi Mesin RA'),
            new Legend(),
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_RA) - 1), $labels_RA, $categories_RA, $values_RA)
            ])
        );

        $chartRB = new Chart(
            'chart',
            new Title('Data Trend Informasi Mesin RB'),
            new Legend(),
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_RB) - 1), $labels_RB, $categories_RB, $values_RB)
            ])
        );

        $chartRC = new Chart(
            'chart',
            new Title('Data Trend Informasi Mesin RC'),
            new Legend(),
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_RC) - 1), $labels_RC, $categories_RC, $values_RC)
            ])
        );

        $chartRD = new Chart(
            'chart',
            new Title('Data Trend Informasi Mesin RD'),
            new Legend(),
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_RD) - 1), $labels_RD, $categories_RD, $values_RD)
            ])
        );

        $chartRE = new Chart(
            'chart',
            new Title('Data Trend Informasi Mesin RE'),
            new Legend(),
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_RE) - 1), $labels_RE, $categories_RE, $values_RE)
            ])
        );

        $chartRF = new Chart(
            'chart',
            new Title('Data Trend Informasi Mesin RF'),
            new Legend(),
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_RF) - 1), $labels_RF, $categories_RF, $values_RF)
            ])
        );

        $chartRG = new Chart(
            'chart',
            new Title('Data Trend Informasi Mesin RG'),
            new Legend(),
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_RG) - 1), $labels_RG, $categories_RG, $values_RG)
            ])
        );

        $chartSA->setTopLeftPosition('L3');
        $chartSA->setBottomRightPosition('T14');

        $chartSB->setTopLeftPosition('L17');
        $chartSB->setBottomRightPosition('T28');

        $chartMixer->setTopLeftPosition('L31');
        $chartMixer->setBottomRightPosition('T42');

        $chartRA->setTopLeftPosition('L45');
        $chartRA->setBottomRightPosition('T56');

        $chartRB->setTopLeftPosition('L59');
        $chartRB->setBottomRightPosition('T70');

        $chartRC->setTopLeftPosition('W3');
        $chartRC->setBottomRightPosition('AE14');

        $chartRD->setTopLeftPosition('W17');
        $chartRD->setBottomRightPosition('AE28');

        $chartRE->setTopLeftPosition('W31');
        $chartRE->setBottomRightPosition('AE42');

        $chartRF->setTopLeftPosition('W45');
        $chartRF->setBottomRightPosition('AE56');

        $chartRG->setTopLeftPosition('W59');
        $chartRG->setBottomRightPosition('AE70');

        return [$chartSA, $chartSB, $chartMixer, $chartRA, $chartRB, $chartRC, $chartRD, $chartRE, $chartRF, $chartRG];
    }

    public function view(): View
    {
        $semua_mesh = 0;
        $semua_rpm = 0;
        $tanggal_awal = date("Y-m-d", strtotime("-" . $this->periode . " months"));
        $tanggal_sekarang = date("Y-m-d");

        if($this->mesh == "ALL"){
            $semua_mesh = 1;
        }

        if($this->rpm == "" || $this->rpm == null){
            $semua_rpm = 1;
        }

        if($semua_mesh && $semua_rpm){
            $data_lab = DB::table('laporan_hasil_produksi as lap')->select('tbl_mesin.name as mesin', 'lab.mesh', 'lab.rpm', 'lab.std_ssa', 'lab.ssa', 'lab.std_d50', 'lab.d50', 'lab.std_d98', 'lab.d98', 'lab.cie86', 'lab.iso2470', 'lab.moisture', 'lab.residue', 'spek.residue_max', 'spek.whiteness_num as spek_whiteness', 'spek.moisture_num as spek_moisture', 'spek.residue_num as spek_residue')->join("laporan_hasil_produksi_lab as lab", "lap.nomor_laporan_produksi", "=", "lab.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'lab.mesin')->join('tbl_spesifikasi_mesin as spek', 'spek.mesin', '=', 'lab.mesin')->whereBetween('lap.tanggal_laporan_produksi', array($tanggal_awal, $tanggal_sekarang))->orderBy('lab.mesin', 'asc')->get();
        }else if($semua_mesh && !$semua_rpm){
            $data_lab = DB::table('laporan_hasil_produksi as lap')->select('tbl_mesin.name as mesin', 'lab.mesh', 'lab.rpm', 'lab.std_ssa', 'lab.ssa', 'lab.std_d50', 'lab.d50', 'lab.std_d98', 'lab.d98', 'lab.cie86', 'lab.iso2470', 'lab.moisture', 'lab.residue', 'spek.residue_max', 'spek.whiteness_num as spek_whiteness', 'spek.moisture_num as spek_moisture', 'spek.residue_num as spek_residue')->join("laporan_hasil_produksi_lab as lab", "lap.nomor_laporan_produksi", "=", "lab.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'lab.mesin')->join('tbl_spesifikasi_mesin as spek', 'spek.mesin', '=', 'lab.mesin')->where('lab.rpm', $this->rpm)->whereBetween('lap.tanggal_laporan_produksi', array($tanggal_awal, $tanggal_sekarang))->orderBy('lab.mesin', 'asc')->get();
        }else if($semua_rpm && !$semua_mesh){
            $data_lab = DB::table('laporan_hasil_produksi as lap')->select('tbl_mesin.name as mesin', 'lab.mesh', 'lab.rpm', 'lab.std_ssa', 'lab.ssa', 'lab.std_d50', 'lab.d50', 'lab.std_d98', 'lab.d98', 'lab.cie86', 'lab.iso2470', 'lab.moisture', 'lab.residue', 'spek.residue_max', 'spek.whiteness_num as spek_whiteness', 'spek.moisture_num as spek_moisture', 'spek.residue_num as spek_residue')->join("laporan_hasil_produksi_lab as lab", "lap.nomor_laporan_produksi", "=", "lab.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'lab.mesin')->join('tbl_spesifikasi_mesin as spek', 'spek.mesin', '=', 'lab.mesin')->where('lab.mesh', $this->mesh)->whereBetween('lap.tanggal_laporan_produksi', array($tanggal_awal, $tanggal_sekarang))->orderBy('lab.mesin', 'asc')->get();
        }else{
            $data_lab = DB::table('laporan_hasil_produksi as lap')->select('tbl_mesin.name as mesin', 'lab.mesh', 'lab.rpm', 'lab.std_ssa', 'lab.ssa', 'lab.std_d50', 'lab.d50', 'lab.std_d98', 'lab.d98', 'lab.cie86', 'lab.iso2470', 'lab.moisture', 'lab.residue', 'spek.residue_max', 'spek.whiteness_num as spek_whiteness', 'spek.moisture_num as spek_moisture', 'spek.residue_num as spek_residue')->join("laporan_hasil_produksi_lab as lab", "lap.nomor_laporan_produksi", "=", "lab.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'lab.mesin')->join('tbl_spesifikasi_mesin as spek', 'spek.mesin', '=', 'lab.mesin')->where('lab.rpm', $this->rpm)->where('lab.mesh', $this->mesh)->whereBetween('lap.tanggal_laporan_produksi', array($tanggal_awal, $tanggal_sekarang))->orderBy('lab.mesin', 'asc')->get();
        }

        $arrayForTable = [];
        $mostFrequentArray = [];
        $arr_mesin = ['SA', 'SB', 'Mixer', 'RA', 'RB', 'RC', 'RD', 'RE', 'RF', 'RG'];

        for($i = 0; $i < count($arr_mesin); $i++){
            $arrayForTable[$arr_mesin[$i]] = [];
            $mostFrequentArray[$arr_mesin[$i]] = [];
        }

        foreach($data_lab as $data_lab){
            $temp = [];
            $temp['mesh'] = $data_lab->mesh;
            $temp['rpm'] = $data_lab->rpm;
            $temp['std_ssa'] = $data_lab->std_ssa;
            $temp['ssa'] = $data_lab->ssa;
            $temp['std_d50'] = $data_lab->std_d50;
            $temp['d50'] = $data_lab->d50;
            $temp['std_d98'] = $data_lab->std_d98;
            $temp['d98'] = $data_lab->d98;
            $temp['cie86'] = $data_lab->cie86;
            $temp['iso2470'] = $data_lab->iso2470;
            $temp['moisture'] = $data_lab->moisture;
            $temp['spek_whiteness'] = $data_lab->spek_whiteness;
            $temp['spek_moisture'] = $data_lab->spek_moisture;
            $temp['spek_residue'] = $data_lab->spek_residue;
            $temp['standart_residue'] = $data_lab->residue_max;
            $temp['residue'] = $data_lab->residue;
            $arrayForTable[$data_lab->mesin][] = $temp;
        }

        // var_dump($arrayForTable);

        for($i = 0; $i < count($arr_mesin); $i++){
            $resultMostFreqDataMesh = [];

            if(empty($arrayForTable[$arr_mesin[$i]])){
                $mostFrequentArray[$arr_mesin[$i]]['mesh'] = NULL;
                $mostFrequentArray[$arr_mesin[$i]]['rpm'] = 0;
                $mostFrequentArray[$arr_mesin[$i]]['ssa'] = 0;
                $mostFrequentArray[$arr_mesin[$i]]['d50'] = 0;
                $mostFrequentArray[$arr_mesin[$i]]['d98'] = 0;
                $mostFrequentArray[$arr_mesin[$i]]['cie86'] = 0;
                $mostFrequentArray[$arr_mesin[$i]]['iso2470'] = 0;
                $mostFrequentArray[$arr_mesin[$i]]['moisture'] = 0;
                $mostFrequentArray[$arr_mesin[$i]]['residue'] = 0;
            }else{
                $arrMesh = array_replace(array_column($arrayForTable[$arr_mesin[$i]], 'mesh'), array_fill_keys(array_keys(array_column($arrayForTable[$arr_mesin[$i]], 'mesh'), null), ''));
                $resultCountArrMesh = array_count_values($arrMesh);
                arsort($resultCountArrMesh);
                $resultMostFreqDataMesh = array_slice(array_keys($resultCountArrMesh), 0, 1, true);
                $mostFrequentArray[$arr_mesin[$i]]['mesh'] = reset($resultMostFreqDataMesh);
                if(reset($resultMostFreqDataMesh) == ""){
                    $resultMostFreqDataMesh = array_slice(array_keys($resultCountArrMesh), 1, 1, true);
                    if(empty($resultMostFreqDataMesh)){
                        $mostFrequentArray[$arr_mesin[$i]]['mesh'] = 0;
                    }else{
                        $mostFrequentArray[$arr_mesin[$i]]['mesh'] = reset($resultMostFreqDataMesh);
                    }
                }

                $resultFilterArr = array_filter($arrayForTable[$arr_mesin[$i]], function ($var) use ($resultMostFreqDataMesh) {
                    return ($var['mesh'] == reset($resultMostFreqDataMesh));
                });


                $arrRPM = array_replace(array_column($resultFilterArr, 'rpm'), array_fill_keys(array_keys(array_column($resultFilterArr, 'rpm'), null), ''));
                $resultCountArrRPM = array_count_values($arrRPM);
                arsort($resultCountArrRPM);
                $resultMostFreqDataRPM = array_slice(array_keys($resultCountArrRPM), 0, 1, true);
                $mostFrequentArray[$arr_mesin[$i]]['rpm'] = reset($resultMostFreqDataRPM);
                if(reset($resultMostFreqDataRPM) == ""){
                    $resultMostFreqDataRPM = array_slice(array_keys($resultCountArrRPM), 1, 1, true);
                    if(empty($resultMostFreqDataRPM)){
                        $mostFrequentArray[$arr_mesin[$i]]['rpm'] = 0;
                    }else{
                        $mostFrequentArray[$arr_mesin[$i]]['rpm'] = reset($resultMostFreqDataRPM);
                    }
                }

                $arrSSA = array_replace(array_column($resultFilterArr, 'ssa'), array_fill_keys(array_keys(array_column($resultFilterArr, 'ssa'), null), ''));
                $resultCountArrSSA = array_count_values($arrSSA);
                arsort($resultCountArrSSA);
                $resultMostFreqDataSSA = array_slice(array_keys($resultCountArrSSA), 0, 1, true);
                $mostFrequentArray[$arr_mesin[$i]]['ssa'] = reset($resultMostFreqDataSSA);
                if(reset($resultMostFreqDataSSA) == ""){
                    $resultMostFreqDataSSA = array_slice(array_keys($resultCountArrSSA), 1, 1, true);
                    if(empty($resultMostFreqDataSSA)){
                        $mostFrequentArray[$arr_mesin[$i]]['ssa'] = 0;
                    }else{
                        $mostFrequentArray[$arr_mesin[$i]]['ssa'] = reset($resultMostFreqDataSSA);
                    }
                }

                $arrD50 = array_replace(array_column($resultFilterArr, 'd50'), array_fill_keys(array_keys(array_column($resultFilterArr, 'd50'), null), ''));
                $arrD50 = array_map('strval', $arrD50);
                $resultCountArrD50 = array_count_values($arrD50);
                arsort($resultCountArrD50);
                $resultMostFreqDataD50 = array_slice(array_keys($resultCountArrD50), 0, 1, true);
                $mostFrequentArray[$arr_mesin[$i]]['d50'] = reset($resultMostFreqDataD50);
                if(reset($resultMostFreqDataD50) == ""){
                    $resultMostFreqDataD50 = array_slice(array_keys($resultCountArrD50), 1, 1, true);
                    if(empty($resultMostFreqDataD50)){
                        $mostFrequentArray[$arr_mesin[$i]]['d50'] = 0;
                    }else{
                        $mostFrequentArray[$arr_mesin[$i]]['d50'] = reset($resultMostFreqDataD50);
                    }
                }

                $arrD98 = array_replace(array_column($resultFilterArr, 'd98'), array_fill_keys(array_keys(array_column($resultFilterArr, 'd98'), null), ''));
                $arrD98 = array_map('strval', $arrD98);
                $resultCountArrD98 = array_count_values($arrD98);
                arsort($resultCountArrD98);
                $resultMostFreqDataD98 = array_slice(array_keys($resultCountArrD98), 0, 1, true);
                $mostFrequentArray[$arr_mesin[$i]]['d98'] = reset($resultMostFreqDataD98);
                if(reset($resultMostFreqDataD98) == ""){
                    $resultMostFreqDataD98 = array_slice(array_keys($resultCountArrD98), 1, 1, true);
                    if(empty($resultMostFreqDataD98)){
                        $mostFrequentArray[$arr_mesin[$i]]['d98'] = 0;
                    }else{
                        $mostFrequentArray[$arr_mesin[$i]]['d98'] = reset($resultMostFreqDataD98);
                    }
                }

                $arrCIE86 = array_replace(array_column($resultFilterArr, 'cie86'), array_fill_keys(array_keys(array_column($resultFilterArr, 'cie86'), null), ''));
                $arrCIE86 = array_map('strval', $arrCIE86);
                $resultCountArrCIE86 = array_count_values($arrCIE86);
                arsort($resultCountArrCIE86);
                $resultMostFreqDataCIE86 = array_slice(array_keys($resultCountArrCIE86), 0, 1, true);
                $mostFrequentArray[$arr_mesin[$i]]['cie86'] = reset($resultMostFreqDataCIE86);
                if(reset($resultMostFreqDataCIE86) == ""){
                    $resultMostFreqDataCIE86 = array_slice(array_keys($resultCountArrCIE86), 1, 1, true);
                    if(empty($resultMostFreqDataCIE86)){
                        $mostFrequentArray[$arr_mesin[$i]]['cie86'] = 0;
                    }else{
                        $mostFrequentArray[$arr_mesin[$i]]['cie86'] = reset($resultMostFreqDataCIE86);
                    }
                }

                $arrISO2470 = array_replace(array_column($resultFilterArr, 'iso2470'), array_fill_keys(array_keys(array_column($resultFilterArr, 'iso2470'), null), ''));
                $arrISO2470 = array_map('strval', $arrISO2470);
                $resultCountArrISO2470 = array_count_values($arrISO2470);
                arsort($resultCountArrISO2470);
                $resultMostFreqDataISO2470 = array_slice(array_keys($resultCountArrISO2470), 0, 1, true);
                $mostFrequentArray[$arr_mesin[$i]]['iso2470'] = reset($resultMostFreqDataISO2470);
                if(reset($resultMostFreqDataISO2470) == ""){
                    $resultMostFreqDataISO2470 = array_slice(array_keys($resultCountArrISO2470), 1, 1, true);
                    if(empty($resultMostFreqDataISO2470)){
                        $mostFrequentArray[$arr_mesin[$i]]['iso2470'] = 0;
                    }else{
                        $mostFrequentArray[$arr_mesin[$i]]['iso2470'] = reset($resultMostFreqDataISO2470);
                    }
                }

                $arrMoisture = array_replace(array_column($resultFilterArr, 'moisture'), array_fill_keys(array_keys(array_column($resultFilterArr, 'moisture'), null), ''));
                $arrMoisture = array_map('strval', $arrMoisture);
                $resultCountArrMoisture = array_count_values($arrMoisture);
                arsort($resultCountArrMoisture);
                $resultMostFreqDataMoisture = array_slice(array_keys($resultCountArrMoisture), 0, 1, true);
                $mostFrequentArray[$arr_mesin[$i]]['moisture'] = reset($resultMostFreqDataMoisture);
                if(reset($resultMostFreqDataMoisture) == ""){
                    $resultMostFreqDataMoisture = array_slice(array_keys($resultCountArrMoisture), 1, 1, true);
                    if(empty($resultMostFreqDataMoisture)){
                        $mostFrequentArray[$arr_mesin[$i]]['moisture'] = 0;
                    }else{
                        $mostFrequentArray[$arr_mesin[$i]]['moisture'] = reset($resultMostFreqDataMoisture);
                    }
                }

                $arrResidue = array_replace(array_column($resultFilterArr, 'residue'), array_fill_keys(array_keys(array_column($resultFilterArr, 'residue'), null), ''));
                $arrResidue = array_map('strval', $arrResidue);
                $resultCountArrResidue = array_count_values($arrResidue);
                arsort($resultCountArrResidue);
                $resultMostFreqDataResidue = array_slice(array_keys($resultCountArrResidue), 0, 1, true);
                $mostFrequentArray[$arr_mesin[$i]]['residue'] = reset($resultMostFreqDataResidue);
                if(reset($resultMostFreqDataResidue) == ""){
                    $resultMostFreqDataResidue = array_slice(array_keys($resultCountArrResidue), 1, 1, true);
                    if(empty($resultMostFreqDataResidue)){
                        $mostFrequentArray[$arr_mesin[$i]]['residue'] = 0;
                    }else{
                        $mostFrequentArray[$arr_mesin[$i]]['residue'] = reset($resultMostFreqDataResidue);
                    }
                }
            }
        }

        // dd($mostFrequentArray);

        return view('excel_trend_informasi_mesin', [
            'data' => $mostFrequentArray, 'tanggal_awal' => $tanggal_awal, 'tanggal_akhir' => $tanggal_sekarang, 'periode' => $this->periode
        ]);
    }
}
