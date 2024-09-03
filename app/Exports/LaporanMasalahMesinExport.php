<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use Maatwebsite\Excel\Concerns\WithCharts;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanMasalahMesinExport implements WithCharts, FromView, ShouldAutoSize, WithTitle
{
	public function __construct($no_mesin, $nama_mesin, $from_date, $to_date)
    {
        $this->no_mesin = $no_mesin;
        $this->nama_mesin = $nama_mesin;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }

    public function charts()
    {
        if($this->no_mesin > 10){
            $labels_SA = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$2', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$3', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$4', null, 1)];
            $categories_SA = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$C$1:$N$1', null, 12)];
            $values_SA = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$2:$N$2', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$3:$N$3', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$4:$N$4', null, 12)];

            $labels_SB = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$5', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$6', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$7', null, 1)];
            $categories_SB = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$C$1:$N$1', null, 12)];
            $values_SB = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$5:$N$5', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$6:$N$6', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$7:$N$7', null, 12)];

            $labels_Mixer = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$8', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$9', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$10', null, 1)];
            $categories_Mixer = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$C$1:$N$1', null, 12)];
            $values_Mixer = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$8:$N$8', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$9:$N$9', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$10:$N$10', null, 12)];

            $labels_RA = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$11', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$12', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$13', null, 1)];
            $categories_RA = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$C$1:$N$1', null, 12)];
            $values_RA = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$11:$N$11', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$12:$N$12', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$13:$N$13', null, 12)];

            $labels_RB = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$14', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$15', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$16', null, 1)];
            $categories_RB = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$C$1:$N$1', null, 12)];
            $values_RB = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$14:$N$14', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$15:$N$15', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$16:$N$16', null, 12)];

            $labels_RC = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$17', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$18', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$19', null, 1)];
            $categories_RC = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$C$1:$N$1', null, 12)];
            $values_RC = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$17:$N$17', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$18:$N$18', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$19:$N$19', null, 12)];

            $labels_RD = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$20', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$21', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$22', null, 1)];
            $categories_RD = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$C$1:$N$1', null, 12)];
            $values_RD = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$20:$N$20', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$21:$N$21', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$22:$N$22', null, 12)];

            $labels_RE = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$23', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$24', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$25', null, 1)];
            $categories_RE = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$C$1:$N$1', null, 12)];
            $values_RE = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$23:$N$23', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$24:$N$24', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$25:$N$25', null, 12)];

            $labels_RF = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$26', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$27', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$28', null, 1)];
            $categories_RF = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$C$1:$N$1', null, 12)];
            $values_RF = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$26:$N$26', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$27:$N$27', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$28:$N$28', null, 12)];

            $labels_RG = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$29', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$30', null, 1), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$B$31', null, 1)];
            $categories_RG = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'" . $this->nama_mesin . "'" . '!$C$1:$N$1', null, 12)];
            $values_RG = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$29:$N$29', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$30:$N$30', null, 12), new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'" . $this->nama_mesin . "'" . '!$C$31:$N$31', null, 12)];

            $chartSA = new Chart(
                'chart',
                new Title($this->nama_mesin .' Masalah Mesin (SA)'),
                new Legend(),
                new PlotArea(null, [
                    new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_SA) - 1), $labels_SA, $categories_SA, $values_SA)
                ])
            );

            $chartSB = new Chart(
                'chart',
                new Title($this->nama_mesin .' Masalah Mesin (SB)'),
                new Legend(),
                new PlotArea(null, [
                    new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_SB) - 1), $labels_SB, $categories_SB, $values_SB)
                ])
            );

            $chartMixer = new Chart(
                'chart',
                new Title($this->nama_mesin .' Masalah Mesin (Mixer)'),
                new Legend(),
                new PlotArea(null, [
                    new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_Mixer) - 1), $labels_Mixer, $categories_Mixer, $values_Mixer)
                ])
            );

            $chartRA = new Chart(
                'chart',
                new Title($this->nama_mesin .' Masalah Mesin (RA)'),
                new Legend(),
                new PlotArea(null, [
                    new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_RA) - 1), $labels_RA, $categories_RA, $values_RA)
                ])
            );

            $chartRB = new Chart(
                'chart',
                new Title($this->nama_mesin .' Masalah Mesin (RB)'),
                null,
                new PlotArea(null, [
                    new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_RB) - 1), $labels_RB, $categories_RB, $values_RB)
                ])
            );

            $chartRC = new Chart(
                'chart',
                new Title($this->nama_mesin .' Masalah Mesin (RC)'),
                new Legend(),
                new PlotArea(null, [
                    new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_RC) - 1), $labels_RC, $categories_RC, $values_RC)
                ])
            );

            $chartRD = new Chart(
                'chart',
                new Title($this->nama_mesin .' Masalah Mesin (RD)'),
                new Legend(),
                new PlotArea(null, [
                    new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_RD) - 1), $labels_RD, $categories_RD, $values_RD)
                ])
            );

            $chartRE = new Chart(
                'chart',
                new Title($this->nama_mesin .' Masalah Mesin (RE)'),
                new Legend(),
                new PlotArea(null, [
                    new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_RE) - 1), $labels_RE, $categories_RE, $values_RE)
                ])
            );

            $chartRF = new Chart(
                'chart',
                new Title($this->nama_mesin .' Masalah Mesin (RF)'),
                new Legend(),
                new PlotArea(null, [
                    new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_RF) - 1), $labels_RF, $categories_RF, $values_RF)
                ])
            );

            $chartRG = new Chart(
                'chart',
                new Title($this->nama_mesin .' Masalah Mesin (RG)'),
                new Legend(),
                new PlotArea(null, [
                    new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($values_RG) - 1), $labels_RG, $categories_RG, $values_RG)
                ])
            );

            $chartSA->setTopLeftPosition('P3');
            $chartSA->setBottomRightPosition('Z17');

            $chartSB->setTopLeftPosition('P19');
            $chartSB->setBottomRightPosition('Z33');

            $chartMixer->setTopLeftPosition('P35');
            $chartMixer->setBottomRightPosition('Z49');

            $chartRA->setTopLeftPosition('P51');
            $chartRA->setBottomRightPosition('Z65');

            $chartRB->setTopLeftPosition('P67');
            $chartRB->setBottomRightPosition('Z81');

            $chartRC->setTopLeftPosition('P83');
            $chartRC->setBottomRightPosition('Z97');

            $chartRD->setTopLeftPosition('AB3');
            $chartRD->setBottomRightPosition('AL17');

            $chartRE->setTopLeftPosition('AB19');
            $chartRE->setBottomRightPosition('AL33');

            $chartRF->setTopLeftPosition('AB35');
            $chartRF->setBottomRightPosition('AL49');

            $chartRG->setTopLeftPosition('AB51');
            $chartRG->setBottomRightPosition('AL65');

            return [$chartSA, $chartSB, $chartMixer, $chartRA, $chartRB, $chartRC, $chartRD, $chartRE, $chartRF, $chartRG];
        }else{
            return [];
        }
    }

    public function view(): View
    {
        $tanggal_dari = date('Y-m-01', strtotime($this->from_date));
        $tanggal_ke = date('Y-m-31', strtotime($this->to_date));
        $start_date = date('Y-1-1', strtotime('-2 years'));
        $end_date = date('Y-12-31');

        if($this->no_mesin <= 10){
            $arrayForTable = [];
            $arrayForTableA = [];
            $arrayForTableB = [];
            $arrayForTableC = [];

            $data_lab = DB::table('laporan_hasil_produksi as lap')->select('lap.tanggal_laporan_produksi as tanggal', 'lab.jam_waktu', 'tbl_mesin.name as mesin', 'lab.mesh', 'lab.rpm', 'lab.ssa', 'lab.d50', 'lab.d98', 'lab.cie86', 'lab.iso2470', 'lab.moisture', 'lab.residue', 'spek.residue_max', 'spek.whiteness_num as spek_whiteness', 'spek.moisture_num as spek_moisture', 'spek.residue_num as spek_residue')->join("laporan_hasil_produksi_lab as lab", "lap.nomor_laporan_produksi", "=", "lab.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'lab.mesin')->join('tbl_spesifikasi_mesin as spek', 'spek.mesin', '=', 'lab.mesin')->where('lab.mesin', $this->no_mesin)->whereBetween('tanggal_laporan_produksi', array($tanggal_dari, $tanggal_ke))->orderBy('lap.tanggal_laporan_produksi', 'asc')->orderBy('lab.jam_waktu', 'asc')->get();

            $data_tonase = DB::table('laporan_hasil_produksi as lap')->select('lap.tanggal_laporan_produksi as tanggal', DB::raw("sum(det.jumlah_tonase) as produksi_tonase"), 'tbl_mesin.name as mesin')->join("laporan_hasil_produksi_detail as det", "lap.nomor_laporan_produksi", "=", "det.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'det.mesin')->where('det.mesin', $this->no_mesin)->whereBetween('tanggal_laporan_produksi', array($tanggal_dari, $tanggal_ke))->orderBy('lap.tanggal_laporan_produksi', 'asc')->groupBy('lap.tanggal_laporan_produksi')->get();

            $data_masalah = DB::table('laporan_hasil_produksi as lap')->select('lap.tanggal_laporan_produksi as tanggal', 'mes.jam_awal', 'mes.jam_akhir', 'mes.masalah', 'mes.kategori', 'tbl_mesin.name as mesin')->join("laporan_hasil_produksi_mesin as mes", "lap.nomor_laporan_produksi", "=", "mes.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'mes.mesin')->where('mes.mesin', $this->no_mesin)->whereBetween('tanggal_laporan_produksi', array($tanggal_dari, $tanggal_ke))->orderBy('lap.tanggal_laporan_produksi', 'asc')->get();

            $d1 = strtotime($this->from_date);
            $d2 = strtotime($this->to_date);
            $min_date = min($d1, $d2);
            $max_date = max($d1, $d2);
            $count_month = 0;

            while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) {
                $count_month++;
            }

            for($j = 0; $j <= $count_month; $j++)
            {
                $tanggal = date('Y-m', strtotime($this->from_date. ' +'.$j.' month'));
                for($i = 1; $i <= date('t', strtotime($tanggal)); $i++)
                {
                    $arrayForTable[date('Y', strtotime($tanggal)) . "-" . date('m', strtotime($tanggal)) . "-" . str_pad($i, 2, '0', STR_PAD_LEFT)] = [];
                    $arrayForTableA[date('Y', strtotime($tanggal)) . "-" . date('m', strtotime($tanggal)) . "-" . str_pad($i, 2, '0', STR_PAD_LEFT)] = [];
                    $arrayForTableB[date('Y', strtotime($tanggal)) . "-" . date('m', strtotime($tanggal)) . "-" . str_pad($i, 2, '0', STR_PAD_LEFT)] = [];
                    $arrayForTableC[date('Y', strtotime($tanggal)) . "-" . date('m', strtotime($tanggal)) . "-" . str_pad($i, 2, '0', STR_PAD_LEFT)] = [];
                }
            }

            foreach($data_lab as $data_lab){
                $temp = [];
                $temp['jam_waktu'] = $data_lab->jam_waktu;
                $temp['mesh'] = $data_lab->mesh;
                $temp['rpm'] = $data_lab->rpm;
                $temp['ssa'] = $data_lab->ssa;
                $temp['d50'] = $data_lab->d50;
                $temp['d98'] = $data_lab->d98;
                $temp['cie86'] = $data_lab->cie86;
                $temp['iso2470'] = $data_lab->iso2470;
                $temp['moisture'] = $data_lab->moisture;
                $temp['spek_whiteness'] = $data_lab->spek_whiteness;
                $temp['spek_moisture'] = $data_lab->spek_moisture;
                $temp['spek_residue'] = $data_lab->spek_residue;
                $temp['residue'] = $data_lab->residue;
                $arrayForTable[$data_lab->tanggal][] = $temp;
            }

            foreach($data_masalah as $data_masalah){
                if($data_masalah->kategori == 1){
                    $temp = [];
                    $temp['jam_awal'] = $data_masalah->jam_awal;
                    $temp['jam_akhir'] = $data_masalah->jam_akhir;
                    $temp['masalah'] = $data_masalah->masalah;
                    $arrayForTableA[$data_masalah->tanggal]['masalah'][] = $temp;
                }else if($data_masalah->kategori == 2){
                    $temp = [];
                    $temp['jam_awal'] = $data_masalah->jam_awal;
                    $temp['jam_akhir'] = $data_masalah->jam_akhir;
                    $temp['masalah'] = $data_masalah->masalah;
                    $arrayForTableB[$data_masalah->tanggal]['masalah'][] = $temp;
                }else if($data_masalah->kategori == 3){
                    $temp = [];
                    $temp['jam_awal'] = $data_masalah->jam_awal;
                    $temp['jam_akhir'] = $data_masalah->jam_akhir;
                    $temp['masalah'] = $data_masalah->masalah;
                    $arrayForTableC[$data_masalah->tanggal]['masalah'][] = $temp;
                }
            }

            foreach($data_tonase as $data_tonase){
                $arrayForTableA[$data_tonase->tanggal]['tonase'] = $data_tonase->produksi_tonase;
            }

            return view('excel_laporan_masalah_mesin', [
                'data_lab' => $arrayForTable, 'data_lain_a' => $arrayForTableA, 'data_lain_b' => $arrayForTableB, 'data_lain_c' => $arrayForTableC, 'mesin' => $this->nama_mesin, 'no_mesin' => $this->no_mesin
            ]);
        }else{
            $data_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];
            $data_kategori = ['major', 'minor', 'lain'];

            $d1 = strtotime($start_date);
            $d2 = strtotime($end_date);
            $min_date = min($d1, $d2);
            $max_date = max($d1, $d2);
            $count_month = 0;

            while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) {
                $count_month++;
            }

            for($k = 1; $k <= count($data_mesin); $k++){
                ${$data_mesin[$k - 1]} = [];
                for($m = 1; $m <= count($data_kategori); $m++){
                    ${$data_kategori[$m - 1]} = [];

                    for($j = 0; $j <= $count_month; $j++)
                    {
                        $tanggal = date('Y-m', strtotime($start_date. ' +'.$j.' month'));
                        for($i = 1; $i <= date('t', strtotime($tanggal)); $i++)
                        {
                            ${$data_kategori[$m - 1]}[date('Y', strtotime($tanggal)) . "-" . date('m', strtotime($tanggal)) . "-" . str_pad($i, 2, '0', STR_PAD_LEFT)] = [];
                        }
                    }

                    ${$data_mesin[$k - 1]}[$m - 1] = ${$data_kategori[$m - 1]};
                }
            }

            for($j = 1; $j <= count($data_mesin); $j++){
                for($m = 1; $m <= count($data_kategori); $m++){
                    ${"data_" . $data_kategori[$m - 1]} = DB::table('laporan_hasil_produksi as lap')->select('lap.tanggal_laporan_produksi as tanggal', 'mes.jam_awal', 'mes.jam_akhir', 'mes.masalah', 'mes.kategori', 'tbl_mesin.name as mesin', DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join("laporan_hasil_produksi_mesin as mes", "lap.nomor_laporan_produksi", "=", "mes.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'mes.mesin')->where('mes.mesin', $j)->where('kategori', $m)->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->orderBy('lap.tanggal_laporan_produksi', 'asc')->get();

                    foreach(${"data_" . $data_kategori[$m - 1]} as $data){
                        $temp = [];
                        $temp['jam_awal'] = $data->jam_awal;
                        $temp['jam_akhir'] = $data->jam_akhir;
                        $temp['masalah'] = $data->masalah;
                        $temp['tahun'] = $data->tahun;
                        $temp['bulan'] = $data->bulan;
                        ${$data_mesin[$j - 1]}[$m - 1][$data->tanggal]['masalah'][] = $temp;
                    }
                }
            }

            if($this->no_mesin == 11){
                return view('excel_laporan_masalah_mesin', [
                'SA' => $sa[0], 'SB' => $sb[0], 'Mixer' => $mixer[0], 'RA' => $ra[0], 'RB' => $rb[0], 'RC' => $rc[0], 'RD' => $rd[0], 'RE' => $re[0], 'RF' => $rf[0], 'RG' => $rg[0], 'mesin' => $this->nama_mesin, 'no_mesin' => $this->no_mesin
                ]);
            }else if($this->no_mesin == 12){
                return view('excel_laporan_masalah_mesin', [
                'SA' => $sa[1], 'SB' => $sb[1], 'Mixer' => $mixer[1], 'RA' => $ra[1], 'RB' => $rb[1], 'RC' => $rc[1], 'RD' => $rd[1], 'RE' => $re[1], 'RF' => $rf[1], 'RG' => $rg[1], 'mesin' => $this->nama_mesin, 'no_mesin' => $this->no_mesin
                ]); 
            }else if($this->no_mesin == 13){
                return view('excel_laporan_masalah_mesin', [
                'SA' => $sa[2], 'SB' => $sb[2], 'Mixer' => $mixer[2], 'RA' => $ra[2], 'RB' => $rb[2], 'RC' => $rc[2], 'RD' => $rd[2], 'RE' => $re[2], 'RF' => $rf[2], 'RG' => $rg[2], 'mesin' => $this->nama_mesin, 'no_mesin' => $this->no_mesin
                ]);
            }else if($this->no_mesin == 14){
                return view('excel_laporan_masalah_mesin', [
                'SA' => $sa, 'SB' => $sb, 'Mixer' => $mixer, 'RA' => $ra, 'RB' => $rb, 'RC' => $rc, 'RD' => $rd, 'RE' => $re, 'RF' => $rf, 'RG' => $rg, 'mesin' => $this->nama_mesin, 'no_mesin' => $this->no_mesin
                ]);
            }
        }
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->nama_mesin;
    }
}
