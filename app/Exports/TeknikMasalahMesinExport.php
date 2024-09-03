<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TeknikMasalahMesinExport implements FromView, ShouldAutoSize
{
    public function __construct($tanggal)
    {
        $this->tanggal = $tanggal;
    }

    public function view(): View
    {
        $data_lab = DB::table('laporan_hasil_produksi as lap')->select('lab.jam_waktu', 'tbl_mesin.name as mesin', 'lab.mesh', 'lab.rpm', 'lab.ssa', 'lab.d50', 'lab.d98', 'lab.cie86', 'lab.iso2470', 'lab.moisture', 'lab.residue', 'spek.residue_max', 'spek.whiteness_num as spek_whiteness', 'spek.moisture_num as spek_moisture', 'spek.residue_num as spek_residue')->join("laporan_hasil_produksi_lab as lab", "lap.nomor_laporan_produksi", "=", "lab.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'lab.mesin')->join('tbl_spesifikasi_mesin as spek', 'spek.mesin', '=', 'lab.mesin')->where('lap.tanggal_laporan_produksi', $this->tanggal)->orderBy('lab.mesin', 'asc')->orderBy('lab.jam_waktu', 'asc')->get();

        $data_tonase = DB::table('laporan_hasil_produksi as lap')->select(DB::raw("sum(det.jumlah_tonase) as produksi_tonase"), 'tbl_mesin.name as mesin')->join("laporan_hasil_produksi_detail as det", "lap.nomor_laporan_produksi", "=", "det.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'det.mesin')->where('lap.tanggal_laporan_produksi', $this->tanggal)->orderBy('det.mesin', 'asc')->groupBy('det.mesin')->get();

        $data_masalah = DB::table('laporan_hasil_produksi as lap')->select('mes.jam_awal', 'mes.jam_akhir', 'mes.masalah', 'mes.kategori', 'tbl_mesin.name as mesin')->join("laporan_hasil_produksi_mesin as mes", "lap.nomor_laporan_produksi", "=", "mes.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'mes.mesin')->where('lap.tanggal_laporan_produksi', $this->tanggal)->orderBy('mes.mesin', 'asc')->get();

        $arrayForTable = [];
        $arrayForTableA = [];
        $arrayForTableB = [];
        $arrayForTableC = [];
        $arr_mesin = ['SA', 'SB', 'Mixer', 'RA', 'RB', 'RC', 'RD', 'RE', 'RF', 'RG'];

        for($i = 0; $i < count($arr_mesin); $i++){
            $arrayForTable[$arr_mesin[$i]] = [];
            $arrayForTableA[$arr_mesin[$i]] = [];
            $arrayForTableB[$arr_mesin[$i]] = [];
            $arrayForTableC[$arr_mesin[$i]] = [];
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
            $temp['standart_residue'] = $data_lab->residue_max;
            $temp['residue'] = $data_lab->residue;
            $arrayForTable[$data_lab->mesin][] = $temp;
        }

        foreach($data_masalah as $data_masalah){
            if($data_masalah->kategori == 1){
                $temp = [];
                $temp['jam_awal'] = $data_masalah->jam_awal;
                $temp['jam_akhir'] = $data_masalah->jam_akhir;
                $temp['masalah'] = $data_masalah->masalah;
                $arrayForTableA[$data_masalah->mesin]['masalah'][] = $temp;
            }else if($data_masalah->kategori == 2){
                $temp = [];
                $temp['jam_awal'] = $data_masalah->jam_awal;
                $temp['jam_akhir'] = $data_masalah->jam_akhir;
                $temp['masalah'] = $data_masalah->masalah;
                $arrayForTableB[$data_masalah->mesin]['masalah'][] = $temp;
            }else if($data_masalah->kategori == 3){
                $temp = [];
                $temp['jam_awal'] = $data_masalah->jam_awal;
                $temp['jam_akhir'] = $data_masalah->jam_akhir;
                $temp['masalah'] = $data_masalah->masalah;
                $arrayForTableC[$data_masalah->mesin]['masalah'][] = $temp;
            }
        }

        foreach($data_tonase as $data_tonase){
            $arrayForTableA[$data_tonase->mesin]['tonase'] = $data_tonase->produksi_tonase;
        }

        return view('excel_hasil_teknik', [
            'data_lab' => $arrayForTable, 'data_lain_a' => $arrayForTableA, 'data_lain_b' => $arrayForTableB, 'data_lain_c' => $arrayForTableC, 'tanggal' => $this->tanggal
        ]);
    }
}
