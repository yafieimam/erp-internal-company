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

class LaporanRataProduksiExport implements WithCharts, FromView, WithTitle
{
    public function charts()
    {
        $labels_SA = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$4', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$5', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$6', null, 1)];
        $categories_SA = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$C$3:$N$3', null, 12)];
        $values_SA = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$4:$N$4', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$5:$N$5', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$6:$N$6', null, 12)];

        $labels_SB = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$7', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$8', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$9', null, 1)];
        $categories_SB = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$C$3:$N$3', null, 12)];
        $values_SB = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$7:$N$7', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$8:$N$8', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$9:$N$9', null, 12)];

        $labels_Mixer = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$10', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$11', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$12', null, 1)];
        $categories_Mixer = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$C$3:$N$3', null, 12)];
        $values_Mixer = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$10:$N$10', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$11:$N$11', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$12:$N$12', null, 12)];

        $labels_RA = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$13', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$14', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$15', null, 1)];
        $categories_RA = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$C$3:$N$3', null, 12)];
        $values_RA = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$13:$N$13', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$14:$N$14', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$15:$N$15', null, 12)];

        $labels_RB = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$16', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$17', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$18', null, 1)];
        $categories_RB = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$C$3:$N$3', null, 12)];
        $values_RB = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$16:$N$16', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$17:$N$17', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$18:$N$18', null, 12)];

        $labels_RC = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$19', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$20', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$21', null, 1)];
        $categories_RC = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$C$3:$N$3', null, 12)];
        $values_RC = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$19:$N$19', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$20:$N$20', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$21:$N$21', null, 12)];

        $labels_RD = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$22', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$23', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$24', null, 1)];
        $categories_RD = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$C$3:$N$3', null, 12)];
        $values_RD = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$22:$N$22', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$23:$N$23', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$24:$N$24', null, 12)];

        $labels_RE = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$25', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$26', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$27', null, 1)];
        $categories_RE = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$C$3:$N$3', null, 12)];
        $values_RE = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$25:$N$25', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$26:$N$26', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$27:$N$27', null, 12)];

        $labels_RF = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$28', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$29', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$30', null, 1)];
        $categories_RF = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$C$3:$N$3', null, 12)];
        $values_RF = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$28:$N$28', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$29:$N$29', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$30:$N$30', null, 12)];

        $labels_RG = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$31', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$32', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$33', null, 1)];
        $categories_RG = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$C$3:$N$3', null, 12)];
        $values_RG = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$31:$N$31', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$32:$N$32', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$33:$N$33', null, 12)];

        $labels_Total = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$34', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$35', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$B$36', null, 1)];
        $categories_Total = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data Rata-Rata Produksi'" . '!$C$3:$N$3', null, 12)];
        $values_Total = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$34:$N$34', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$35:$N$35', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data Rata-Rata Produksi'" . '!$C$36:$N$36', null, 12)];

        $chartSA = new Chart(
            'chart',
            new Title('Rata-Rata Produksi (SA)'),
            new Legend(),
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_SA) - 1), $labels_SA, $categories_SA, $values_SA)
            ])
        );

        $chartSB = new Chart(
            'chart',
            new Title('Rata-Rata Produksi (SB)'),
            new Legend(),
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_SB) - 1), $labels_SB, $categories_SB, $values_SB)
            ])
        );

        $chartMixer = new Chart(
            'chart',
            new Title('Rata-Rata Produksi (Mixer)'),
            new Legend(),
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_Mixer) - 1), $labels_Mixer, $categories_Mixer, $values_Mixer)
            ])
        );

        $chartRA = new Chart(
            'chart',
            new Title('Rata-Rata Produksi (RA)'),
            new Legend(),
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_RA) - 1), $labels_RA, $categories_RA, $values_RA)
            ])
        );

        $chartRB = new Chart(
            'chart',
            new Title('Rata-Rata Produksi (RB)'),
            new Legend(),
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_RB) - 1), $labels_RB, $categories_RB, $values_RB)
            ])
        );

        $chartRC = new Chart(
            'chart',
            new Title('Rata-Rata Produksi (RC)'),
            new Legend(),
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_RC) - 1), $labels_RC, $categories_RC, $values_RC)
            ])
        );

        $chartRD = new Chart(
            'chart',
            new Title('Rata-Rata Produksi (RD)'),
            new Legend(),
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_RD) - 1), $labels_RD, $categories_RD, $values_RD)
            ])
        );

        $chartRE = new Chart(
            'chart',
            new Title('Rata-Rata Produksi (RE)'),
            new Legend(),
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_RE) - 1), $labels_RE, $categories_RE, $values_RE)
            ])
        );

        $chartRF = new Chart(
            'chart',
            new Title('Rata-Rata Produksi (RF)'),
            new Legend(),
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_RF) - 1), $labels_RF, $categories_RF, $values_RF)
            ])
        );

        $chartRG = new Chart(
            'chart',
            new Title('Rata-Rata Produksi (RG)'),
            new Legend(),
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_RG) - 1), $labels_RG, $categories_RG, $values_RG)
            ])
        );

        $chartTotal = new Chart(
            'chart',
            new Title('Rata-Rata Produksi (Total)'),
            new Legend(),
            new PlotArea(null, [
                new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_Total) - 1), $labels_Total, $categories_Total, $values_Total)
            ])
        );

        $chartTotal->setTopLeftPosition('P3');
        $chartTotal->setBottomRightPosition('Z17');

        $chartSA->setTopLeftPosition('P19');
        $chartSA->setBottomRightPosition('Z33');

        $chartSB->setTopLeftPosition('P35');
        $chartSB->setBottomRightPosition('Z49');

        $chartMixer->setTopLeftPosition('P51');
        $chartMixer->setBottomRightPosition('Z65');

        $chartRA->setTopLeftPosition('P67');
        $chartRA->setBottomRightPosition('Z81');

        $chartRB->setTopLeftPosition('P83');
        $chartRB->setBottomRightPosition('Z97');

        $chartRC->setTopLeftPosition('AB3');
        $chartRC->setBottomRightPosition('AL17');

        $chartRD->setTopLeftPosition('AB19');
        $chartRD->setBottomRightPosition('AL33');

        $chartRE->setTopLeftPosition('AB35');
        $chartRE->setBottomRightPosition('AL49');

        $chartRF->setTopLeftPosition('AB51');
        $chartRF->setBottomRightPosition('AL65');

        $chartRG->setTopLeftPosition('AB67');
        $chartRG->setBottomRightPosition('AL81');

        return [$chartSA, $chartSB, $chartMixer, $chartRA, $chartRB, $chartRC, $chartRD, $chartRE, $chartRF, $chartRG, $chartTotal];
    }

    public function view(): View
    {
    	$tanggal_awal = date('Y-1-1', strtotime('-2 years'));
	    $tanggal_akhir = date('Y-12-31');
	    $tahun_awal = date('Y', strtotime('-2 years'));
	    $tahun_akhir = date('Y');

    	$data_produksi = DB::table('laporan_hasil_produksi as lap')->select('tbl_mesin.name as mesin', DB::raw("sum(jumlah_tonase) / count(distinct(tanggal_laporan_produksi)) AS total"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y-%m') AS new_date"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join("laporan_hasil_produksi_detail as det", "lap.nomor_laporan_produksi", "=", "det.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'det.mesin')->whereBetween('lap.tanggal_laporan_produksi', array($tanggal_awal, $tanggal_akhir))->groupBy('det.mesin', 'new_date')->orderBy('det.mesin', 'asc')->orderBy('new_date', 'asc')->get();

        $array = [];
        $tahun = date("Y");
        $arr_mesin = ['SA', 'SB', 'Mixer', 'RA', 'RB', 'RC', 'RD', 'RE', 'RF', 'RG'];
        $arr_bulan = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $arr_tahun = [$tahun - 2, $tahun - 1, $tahun];

        for($i = 0; $i < count($arr_mesin); $i++){
            $array[$arr_mesin[$i]] = [];
            for($j = 0; $j < count($arr_tahun); $j++){
                $array[$arr_mesin[$i]][$arr_tahun[$j]] = [];
                for($k = 0; $k < count($arr_bulan); $k++){
                    $array[$arr_mesin[$i]][$arr_tahun[$j]][$arr_bulan[$k]] = null;
                }
            }
        }

        foreach($data_produksi as $data_produksi){
            $array[$data_produksi->mesin][$data_produksi->tahun][$data_produksi->bulan] = $data_produksi->total;
        }

        // dd($tahun_akhir);

        return view('excel_laporan_rata_produksi', [
            'data' => $array,
            'tanggal_awal' => $tahun_awal,
            'tanggal_akhir' => $tahun_akhir
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Data Rata-Rata Produksi';
    }
}
