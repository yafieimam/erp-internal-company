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

class KartuPiutangExport implements FromView, ShouldAutoSize, WithEvents
{
    public function __construct($from_date, $to_date, $custid)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->custid = $custid;
    }

    public function view(): View
    {
    	$arrayForTable = [];
        $arrayForTable2 = [];
        $cek_cust = 0;

    	if($this->custid == null || $this->custid == ''){
    		$cek_cust = 0;

    		$data = DB::select("select pen.custid, pen.custname as customers, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as saldo from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.custid from penagihan as pena where pena.ppn = 0 group by pena.custid) pen2 on pen.custid=pen2.custid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (10 * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.custid from penagihan as pena where pena.ppn > 0 group by pena.custid) pen3 on pen.custid=pen3.custid where (pen.tanggal_do between ? and ?) group by pen.custid order by customers", [$this->from_date, $this->to_date]);

            foreach($data as $d){
                $arrayForTable[$d->custid] = [];

                $data2 = DB::select("select @lunas:= 0 as lunas, pp.tanggal_do as tanggal, pp.nosj, null as noar, pp.noinv, pp.itemid, pp.itemname as keterangan, null as selisih_hari, (pp.qty * pp.price) as sub_nominal, pp.tagihan as total_nominal, @running_total:=@running_total + pp.tagihan AS saldo from (select tanggal_do, nosj, itemid, noinv, itemname, qty, price, (CASE WHEN ppn = 0 THEN (sub_amount - diskon) ELSE (sub_amount - diskon) + (10 * (sub_amount - diskon) / 100) END) as tagihan from penagihan where lunas = 0 and custid = ? and (tanggal_do between ? and ?)) as pp join (select @running_total:=0) r union select @lunas:= 1 as lunas, p.tanggal, null as nosj, p.noar, null as noinv, null as itemid, p.keterangan, DATEDIFF(p.tanggal, p.tanggal_do) as selisih_hari, null as sub_nominal, p.total_nominal, @running_total:=@running_total + p.total_nominal AS saldo from (select tanggal, pelunasan.noar, pelunasan.keterangan, total_nominal, pn.tanggal_do from pelunasan left join pelunasan_detail as pd on pd.noar = pelunasan.noar left join penagihan as pn on pn.noinv = pd.noinv where pd.custid = ? and (tanggal between ? and ?) and EXISTS (SELECT pl.noar FROM pelunasan as pl left join pelunasan_detail as pd on pl.noar = pd.noar WHERE pd.custid = ?)) as p join (select @running_total:=0) r union select @lunas:= 1 as lunas, pp.tanggal_do as tanggal, pp.nosj, null as noar, pp.noinv, pp.itemid, pp.itemname as keterangan, DATEDIFF(pp.tanggal_pelunasan, pp.tanggal_do) as selisih_hari, (pp.qty * pp.price) as sub_nominal, pp.tagihan as total_nominal, @running_total:=@running_total + pp.tagihan AS saldo from (select tanggal_do, nosj, itemid, noinv, itemname, qty, price, tanggal_pelunasan, (CASE WHEN ppn = 0 THEN (sub_amount - diskon) ELSE (sub_amount - diskon) + (10 * (sub_amount - diskon) / 100) END) as tagihan from penagihan where lunas = 1 and custid = ? and (tanggal_do between ? and ?) and not EXISTS (SELECT pll.noar FROM pelunasan as pll left join pelunasan_detail as pdd on pll.noar = pdd.noar WHERE pdd.custid = ?)) as pp join (select @running_total:=0) r", [$d->custid, $this->from_date, $this->to_date, $d->custid, $this->from_date, $this->to_date, $d->custid, $d->custid, $this->from_date, $this->to_date, $d->custid]);

                foreach($data2 as $d2){
                    $temp = [];
                    $temp['lunas'] = $d2->lunas;
                    $temp['tanggal'] = $d2->tanggal;
                    $temp['nosj'] = $d2->nosj;
                    $temp['noar'] = $d2->noar;
                    $temp['noinv'] = $d2->noinv;
                    $temp['itemid'] = $d2->itemid;
                    $temp['keterangan'] = $d2->keterangan;
                    $temp['selisih_hari'] = $d2->selisih_hari;
                    $temp['sub_nominal'] = $d2->sub_nominal;
                    $temp['total_nominal'] = $d2->total_nominal;
                    $temp['saldo'] = $d2->saldo;
                    $arrayForTable[$d->custid][] = $temp;

                    if($d2->nosj){
                        $arrayForTable2[$d2->nosj] = [];

                        $data3 = DB::table('penagihan')->select('qty', 'price', 'diskon', DB::raw("(CASE WHEN ppn = 0 THEN 0 ELSE (10 * (sub_amount - diskon) / 100) END) as pajak"))->where('nosj', $d2->nosj)->where('itemid', $d2->itemid)->get();

                        foreach($data3 as $d3){
                            $temp2 = [];
                            $temp2['qty'] = $d3->qty;
                            $temp2['price'] = $d3->price;
                            $temp2['diskon'] = $d3->diskon;
                            $temp2['pajak'] = $d3->pajak;
                            $arrayForTable2[$d2->nosj][] = $temp2;
                        }
                    }

                    if($d2->noar){
                        $arrayForTable2[$d2->noar] = [];

                        $data3 = DB::select("select pd.noinv, pen.nosj, pen.tanggal_do, pen.itemname, pen.qty, pen.price, DATEDIFF(pl.tanggal, pen.tanggal_do) as selisih_hari, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from pelunasan_detail as pd left join penagihan as pen on pen.noinv = pd.noinv left join pelunasan as pl on pl.noar = pd.noar left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (10 * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join tbl_metode_pembayaran as met on met.id = pen.metode_pembayaran where pd.noar = ?", [$d2->noar]);

                        foreach($data3 as $d3){
                            $temp2 = [];
                            $temp2['noinv'] = $d3->noinv;
                            $temp2['nosj'] = $d3->nosj;
                            $temp2['tanggal_do'] = $d3->tanggal_do;
                            $temp2['itemname'] = $d3->itemname;
                            $temp2['qty'] = $d3->qty;
                            $temp2['price'] = $d3->price;
                            $temp2['tagihan'] = $d3->tagihan;
                            $temp2['selisih_hari'] = $d3->selisih_hari;
                            $arrayForTable2[$d2->noar][] = $temp2;
                        }
                    }
                }
            }

            $data_cust = [];
    	}else{
    		$cek_cust = 1;

            $data = DB::select("select @lunas:= 0 as lunas, pp.tanggal_do as tanggal, pp.nosj, null as noar, pp.noinv, pp.itemid, pp.itemname as keterangan, null as selisih_hari, (pp.qty * pp.price) as sub_nominal, pp.tagihan as total_nominal, @running_total:=@running_total + pp.tagihan AS saldo from (select tanggal_do, nosj, itemid, noinv, itemname, qty, price, (CASE WHEN ppn = 0 THEN (sub_amount - diskon) ELSE (sub_amount - diskon) + (10 * (sub_amount - diskon) / 100) END) as tagihan from penagihan where lunas = 0 and custid = ? and (tanggal_do between ? and ?)) as pp join (select @running_total:=0) r union select @lunas:= 1 as lunas, p.tanggal, null as nosj, p.noar, null as noinv, null as itemid, p.keterangan, DATEDIFF(p.tanggal, p.tanggal_do) as selisih_hari, null as sub_nominal, p.total_nominal, @running_total:=@running_total + p.total_nominal AS saldo from (select tanggal, pelunasan.noar, pelunasan.keterangan, total_nominal, pn.tanggal_do from pelunasan left join pelunasan_detail as pd on pd.noar = pelunasan.noar left join penagihan as pn on pn.noinv = pd.noinv where pd.custid = ? and (tanggal between ? and ?) and EXISTS (SELECT pl.noar FROM pelunasan as pl left join pelunasan_detail as pd on pl.noar = pd.noar WHERE pd.custid = ?)) as p join (select @running_total:=0) r union select @lunas:= 1 as lunas, pp.tanggal_do as tanggal, pp.nosj, null as noar, pp.noinv, pp.itemid, pp.itemname as keterangan, DATEDIFF(pp.tanggal_pelunasan, pp.tanggal_do) as selisih_hari, (pp.qty * pp.price) as sub_nominal, pp.tagihan as total_nominal, @running_total:=@running_total + pp.tagihan AS saldo from (select tanggal_do, nosj, itemid, noinv, itemname, qty, price, tanggal_pelunasan, (CASE WHEN ppn = 0 THEN (sub_amount - diskon) ELSE (sub_amount - diskon) + (10 * (sub_amount - diskon) / 100) END) as tagihan from penagihan where lunas = 1 and custid = ? and (tanggal_do between ? and ?) and not EXISTS (SELECT pll.noar FROM pelunasan as pll left join pelunasan_detail as pdd on pll.noar = pdd.noar WHERE pdd.custid = ?)) as pp join (select @running_total:=0) r", [$this->custid, $this->from_date, $this->to_date, $this->custid, $this->from_date, $this->to_date, $this->custid, $this->custid, $this->from_date, $this->to_date, $this->custid]);

    		foreach($data as $d){
    			if($d->nosj){
    				$arrayForTable[$d->nosj] = [];

    				$data2 = DB::table('penagihan')->select('qty', 'price', 'diskon', DB::raw("(CASE WHEN ppn = 0 THEN 0 ELSE (10 * (sub_amount - diskon) / 100) END) as pajak"))->where('nosj', $d->nosj)->where('itemid', $d->itemid)->get();

    				foreach($data2 as $d2){
    					$temp = [];
    					$temp['qty'] = $d2->qty;
    					$temp['price'] = $d2->price;
    					$temp['diskon'] = $d2->diskon;
    					$temp['pajak'] = $d2->pajak;
    					$arrayForTable[$d->nosj][] = $temp;
    				}
    			}

    			if($d->noar){
    				$arrayForTable[$d->noar] = [];

    				$data2 = DB::select("select pd.noinv, pen.nosj, pen.tanggal_do, pen.itemname, pen.qty, pen.price, DATEDIFF(pl.tanggal, pen.tanggal_do) as selisih_hari, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from pelunasan_detail as pd left join penagihan as pen on pen.noinv = pd.noinv left join pelunasan as pl on pl.noar = pd.noar left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (10 * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join tbl_metode_pembayaran as met on met.id = pen.metode_pembayaran where pd.noar = ?", [$d->noar]);

    				foreach($data2 as $d2){
    					$temp = [];
    					$temp['noinv'] = $d2->noinv;
    					$temp['nosj'] = $d2->nosj;
    					$temp['tanggal_do'] = $d2->tanggal_do;
    					$temp['itemname'] = $d2->itemname;
    					$temp['qty'] = $d2->qty;
    					$temp['price'] = $d2->price;
    					$temp['tagihan'] = $d2->tagihan;
    					$temp['selisih_hari'] = $d2->selisih_hari;
    					$arrayForTable[$d->noar][] = $temp;
    				}
    			}

    		}

    		$data_cust = DB::table('customers')->select('custid', 'custname', 'address', 'phone')->where('custid', $this->custid)->first();
    	}

        return view('excel_kartu_piutang', [
            'data' => $data, 'from_date' => $this->from_date, 'to_date' => $this->to_date, 'data_cust' => $data_cust, 'data_arr' => $arrayForTable, 'data_arr2' => $arrayForTable2, 'cek_cust' => $cek_cust
        ]);
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
                $event->sheet->getStyle('A1')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('A2')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('A10')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('B10')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('C10')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('D10')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('E10')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('F10')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('G10')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('H10')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('I10')->ApplyFromArray(['font' => ['bold' => true]]);
                $event->sheet->getStyle('A10:I10')->applyFromArray([
                    'borders'    => [
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
