<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use App\Models\DbpAop;
use App\Models\Kode;
use App\Models\InitMasterPart;
use App\Models\ReturAOPDetails;
use App\Models\ReturAOPHeader;
use App\Models\PartAOPMaster;
use App\Models\TransaksiInvoiceDetails;
use App\Models\InvoiceAOPDetails;
use App\Models\InvoiceAOPHeader;
use App\Models\NilaiPersediaan;
use App\Models\MstInit;
use App\Models\PartAOPDbp;
use App\Models\TransaksiReturHeader;
use App\Models\SuratTagihan;


class PembelianAOPController extends Controller
{
    public function index(){

        return view('pembelian-aop.index');
    }
    public function formUploadRekap(){

        return view('pembelian-aop.upload_rekap');
    }
    public function formUploadSurat(){

        return view('pembelian-aop.upload_surat');
    }

    public function uploadRekap (Request $request){


            if ($request->hasFile('rekap_tagihan')) {
                $file = $request->file('rekap_tagihan');
                $contents = file_get_contents($file);
                $rows = explode("\n", $contents);

                $header = array_shift($rows);
                
                foreach ($rows as $row) {
                    $data = explode("\t", $row); 
                    if (count($data) == 10) {
                        RekapTagihan::create([
                            'customer_number'       => $data[0],
                            'customer_name'         => $data[1],
                            'invoice_aop'           => $data[2],
                            'billing_document_date' => $data[3],
                            'tanggal_jatuh_tempo'   => $data[4],
                            'billing_amount_ppn'    => $data[5],
                            'additional_discount'   => $data[6],
                            'cash_discount'         => $data[7],
                            'extra_discount'        => $data[8], 
                        ]);
                    }
                }

                return redirect()->back()->with('success', 'Rekap Tagihan berhasil ditambahkan');
            }

        return redirect()->route('pembelian-aop.index')->with('warning','Rekap Tagihan Gagal Diupload');

    }

    public function uploadSurat (Request $request){

        if ($request->hasFile('surat_tagihan')) {
            
            $file = $request->file('surat_tagihan');
            $contents = file_get_contents($file);
            $rows = explode("\n", $contents);

            foreach ($rows as $row) {
                $data = explode("\t", $row); 
                SuratTagihan::create([
                    'customer_number'       => $row[1],
                    'customer_name'         => $row[2],
                    'invoice_aop'           => $row[3],
                    'billing_document_date' => $row[4],
                    'part_no'               => $row[5], //part_no
                    'billing_quantity'      => $row[6], //qty
                    'billing_amount'        => $row[7],
                    'spb_no'                => $row[8], //invoice_aop
                    'tanggal_cetak_faktur'  => $row[9], 
                    'tanggal_jatuh tempo'   => $row[10], 
                ]);
            }

            return redirect()->back()->with('success', 'Rekap Tagihan berhasil ditambahkan');
        }

        return redirect()->route('pembelian-aop.index')->with('warning','Rekap Tagihan Gagal Diupload');
        
    }

    public function proses(){

        $persediaan = NilaiPersediaan::all();

        return view('pembelian-aop.proses', compact('persediaan'));
    }

    public function prosesPersediaan(Request $request){

        $tanggal_awal   = $request->input('tanggal_awal');
        $tanggal_akhir  = $request->input('tanggal_akhir');
        $kd             = $request->input('area_inv');
        $aop            = 1;

        //dd($tanggal_awal);

        //PILIH PERSEDIAAN
        $tanggal = new Carbon($tanggal_awal);
        $bulan = $tanggal->format('m');
        $tahun = $tanggal->format('Y');
        
        //PILIH PERSEDIAAN AKHIR BULAN SEBELUMNYA
        $bulan_prev = $tanggal->subMonth()->format('m');

        $kode = Kode::where('kode_area',$kd)
            ->first();

        $stock = InitMasterPart::where('kd_gudang', $kode->kode_gudang)
            ->get();


        //Init Januari 2023
        $total_init = 0;

        foreach ($stock as $s) {
            if (
                isset($s->stock_akhir, $s->dbp->het, $s->master, $s->master->level, $s->master->level->diskon) &&
                is_numeric($s->stock_akhir) && is_numeric($s->dbp->het) && is_numeric($s->master->level->diskon)
            ) {
                $init = $s->stock_akhir * (($s->dbp->het * (100 - $s->master->level->diskon)) / 100) / 1.11;
                $total_init += $init;
            }
        }

        $test1 = 0;
        foreach($stock as $s){
            $test = $s->amt_stock_akhir;
            $test1+=$test;
        }

        //dd($test1);

        //PEMBELIAN AOP (/1.11)
        $getPembelianAOP = InvoiceAOPHeader::where('kd_gudang', $kode->kode_gudang)
            ->whereBetween('billingDocument_date', [$tanggal_awal, $tanggal_akhir])
            ->get();

        $dbp_pembelian = 0;

        //Pembelian AOP ex disc

            // foreach($getPembelianAOP as $p){

            // $invoice_details = $p->details;
            //     foreach($invoice_details as $h){

            //         $dbp = $h->qty * $h->dbp_jual->het;
            //         $dbp_pembelian += $dbp;
            //     }

            // }


        //Pembelian AOP het * disc

        foreach($getPembelianAOP as $p){
            $invoice_details = $p->details;

            foreach($invoice_details as $h){

                if (
                    isset($h->qty, $h->dbp->het, $h->master->level->diskon) &&
                    is_numeric($h->qty) && is_numeric($h->dbp->het) && is_numeric($h->master->level->diskon)
                ) {
                    $dbp = $h->qty * ($h->dbp->het * (100 - $h->master->level->diskon)/100) / 1.11;
                    $dbp_pembelian += $dbp;
                }

            }
        }

        //RETUR AOP
        $getReturAOP = ReturAOPHeader::whereBetween('crea_date', [$tanggal_awal, $tanggal_akhir])
            ->where('kd_gudang_aop', $kode->kode_kcp)
            ->where('approve_aop', 'Y')
            ->get();

        $retur_aop = $getReturAOP->sum('amount_total');

        //penjualan part_aop
        $part_aop = PartAOPMaster::where('id_part', $aop)->pluck('part_no');

        $getPenjualan = TransaksiInvoiceDetails::where('area_inv', $kode->kode_area)
            ->whereBetween('crea_date', [$tanggal_awal, $tanggal_akhir])
            ->whereIn('part_no', $part_aop)
            ->get();

        $penjualan = 0;
        
        foreach($getPenjualan as $p){

            $kurang_disc_ppn = $p->qty * ($p->master->dbp->het * ((100 - $p->master->level->diskon)/100) /1.11);
            $penjualan += $kurang_disc_ppn;

        }

        //PENJUALAN BARU

        $penjualan_1 = 0;

        foreach($getPenjualan as $p) {
            $penjualan_dbp = $p->qty * $p->dbp_jual->het;
            $penjualan_1 += $penjualan_dbp;

            // var_dump($p->part_no);
            // var_dump($penjualan_dbp);
            // dd($penjualan_dbp);

        }

        //RETUR PENJUALAN
        $getReturPenjualan = TransaksiReturHeader::whereBetween('flag_approve1_date', [$tanggal_awal, $tanggal_akhir])
            ->where('area_retur', $kode->kode_area)
            ->where('flag_approve1', 'Y')
            ->get();

        $total_retur = 0;

        foreach ($getReturPenjualan as $r) {

            $total_retur += $r->details_retur->nominal_total/1.11;
        }

        //INSERT KE TABEL NILAI PERSEDIAAN
        $checkData = NilaiPersediaan::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->where('area_inv', $kd)
            ->first();

        $getPersediaanAkhirPrev = NilaiPersediaan::where('bulan', $bulan_prev)->where('area_inv', $kd)->pluck('persediaan_akhir')->first();       

        // if ($checkData === null) {

        //     //init Januari 2023
        //     $data['bulan']                  = $bulan;
        //     $data['tahun']                  = $tahun;
        //     $data['area_inv']               = $kd;
        //     $data['persediaan_awal']        = $total_init;
        //     $data['pembelian']              = $dbp_pembelian;
        //     $data['retur_aop']              = $retur_aop;
        //     $data['modal_terjual']          = $penjualan;
        //     $data['persediaan_akhir']       = $total_init + $dbp_pembelian - $retur_aop - $penjualan + $total_retur ;
        //     $data['retur_modal_terjual']    = $total_retur;
        //     $data['status']                 = 'Y';
        //     $data['crea_date']              = Carbon::now();

        //     $inserted = NilaiPersediaan::create($data);

        //     if ($inserted) {
        //         return redirect()->route('pembelian-aop.proses')->with('success', 'Data persediaan berhasil ditambahkan.');

        //     } else {
        //         return redirect()->route('pembelian-aop.proses')->with('danger', 'Data persediaan gagal ditambahkan.');
        //     }
        // }  
        // elseif ($checkData !== null) {

        //     return redirect()->route('pembelian-aop.proses')->with('warning','Data persediaan sudah tersedia!');

        // }

        if ($checkData === null) {
            //Next Bulan
            $data['bulan']                  = $bulan;
            $data['tahun']                  = $tahun;
            $data['area_inv']               = $kd;
            $data['persediaan_awal']        = $getPersediaanAkhirPrev;
            $data['pembelian']              = $dbp_pembelian;
            $data['retur_aop']              = $retur_aop;
            $data['modal_terjual']          = $penjualan;
            $data['persediaan_akhir']       = $getPersediaanAkhirPrev + $dbp_pembelian - $retur_aop - $penjualan + $total_retur ;
            $data['retur_modal_terjual']    = $total_retur;
            $data['status']                 = 'Y';
            $data['crea_date']              = Carbon::now();
  
            //dd($data);
            $inserted = NilaiPersediaan::create($data);

            if ($inserted) {
                return redirect()->route('pembelian-aop.proses')->with('success', 'Data persediaan berhasil ditambahkan!');

            } else {
                return redirect()->route('pembelian-aop.proses')->with('danger', 'Data persediaan gagal ditambahkan!');
            }
        } 
        elseif ($checkData !== null) {

            return redirect()->route('pembelian-aop.proses')->with('warning','Data persediaan sudah tersedia!');

        }
            
    }


}
