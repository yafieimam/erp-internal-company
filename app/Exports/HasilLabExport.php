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

class HasilLabExport implements FromView, ShouldAutoSize, WithColumnFormatting, WithEvents
{
	public function __construct($tanggal)
    {
        $this->tanggal = $tanggal;
    }

    public function view(): View
    {
        $data_lab = DB::table('laporan_hasil_produksi as lap')->select('lab.jam_waktu', 'tbl_mesin.name as mesin', 'lab.mesh', 'lab.rpm', 'lab.std_ssa', 'lab.ssa', 'lab.std_d50', 'lab.d50', 'lab.std_d98', 'lab.d98', 'lab.cie86', 'lab.iso2470', 'lab.moisture', 'lab.residue', 'spek.residue_max', 'spek.whiteness_num as spek_whiteness', 'spek.moisture_num as spek_moisture', 'spek.residue_num as spek_residue')->join("laporan_hasil_produksi_lab as lab", "lap.nomor_laporan_produksi", "=", "lab.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'lab.mesin')->join('tbl_spesifikasi_mesin as spek', 'spek.mesin', '=', 'lab.mesin')->where('lap.tanggal_laporan_produksi', $this->tanggal)->orderBy('lab.mesin', 'asc')->orderBy('lab.jam_waktu', 'asc')->get();

        $arrayForTable = [];
        $arr_mesin = ['SA', 'SB', 'Mixer', 'RA', 'RB', 'RC', 'RD', 'RE', 'RF', 'RG'];

        for($i = 0; $i < count($arr_mesin); $i++){
            $arrayForTable[$arr_mesin[$i]] = [];
        }
        foreach($data_lab as $data_lab){
            $temp = [];
            $temp['jam_waktu'] = $data_lab->jam_waktu;
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

        return view('excel_hasil_lab', [
            'data' => $arrayForTable, 'tanggal' => $this->tanggal
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
            'I' => NumberFormat::FORMAT_TEXT,
            'J' => NumberFormat::FORMAT_TEXT,
            'K' => NumberFormat::FORMAT_TEXT,
            'L' => NumberFormat::FORMAT_TEXT,
            'M' => NumberFormat::FORMAT_TEXT,
            'N' => NumberFormat::FORMAT_TEXT,
            'O' => NumberFormat::FORMAT_TEXT,
            'P' => NumberFormat::FORMAT_TEXT,
            'T' => NumberFormat::FORMAT_TEXT,
            'U' => NumberFormat::FORMAT_TEXT,
            'V' => NumberFormat::FORMAT_TEXT,
            'W' => NumberFormat::FORMAT_TEXT,
            'X' => NumberFormat::FORMAT_TEXT,
            'Y' => NumberFormat::FORMAT_TEXT,
            'Z' => NumberFormat::FORMAT_TEXT,
            'AC' => NumberFormat::FORMAT_TEXT,
            'AD' => NumberFormat::FORMAT_TEXT,
            'AE' => NumberFormat::FORMAT_TEXT,
            'AF' => NumberFormat::FORMAT_TEXT,
            'AG' => NumberFormat::FORMAT_TEXT,
            'AH' => NumberFormat::FORMAT_TEXT,
            'AI' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function registerEvents(): array
    {
        
        $styleArray = [
        'font' => [
        'name' => 'Times New Roman',
        'size' => 14
        ]
        ];
            
        return [
            AfterSheet::class => function(AfterSheet $event) use ($styleArray)
            {
                $event->sheet->getStyle('S30')->ApplyFromArray(['font' => ['name' => 'Times New Roman', 'size' => 14]]);
                $event->sheet->getStyle('T32')->ApplyFromArray(['font' => ['name' => 'Arial', 'size' => 12]]);
                $event->sheet->getStyle('AB30')->ApplyFromArray(['font' => ['name' => 'Times New Roman', 'size' => 14]]);
                $event->sheet->getStyle('AC32')->ApplyFromArray(['font' => ['name' => 'Arial', 'size' => 12]]);
                $event->sheet->getStyle('T38')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('AC38')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('U38')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('AD38')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('X38')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('AG38')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('Y38')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('AH38')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('U39')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('AD39')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('V39')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('AE39')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('V40')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('AE40')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('W40')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('AF40')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('A3:P25')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                $event->sheet->getStyle('T38:Z50')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                $event->sheet->getStyle('AC38:AI50')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
}
