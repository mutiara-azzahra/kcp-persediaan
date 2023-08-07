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
use App\Models\TransaksiInvoiceDetails;
use App\Models\InvoiceAOPDetails;
use App\Models\InvoiceAOPHeader;
use App\Models\NilaiPersediaan;
use App\Models\TransaksiReturHeader;
use App\Models\SuratTagihan;


class PembelianAOPController extends Controller
{
    public function index(){

        // $suratTagihan = SuratTagihan::all();
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
                            'customer_number' => $data[0],
                            'customer_name' => $data[1],
                            'invoice_aop' => $data[2],
                            'billing_document_date' => $data[3],
                            'tanggal_jatuh_tempo' => $data[4],
                            'billing_amount_ppn' => $data[5],
                            'additional_discount' => $data[6],
                            'cash_discount' => $data[7],
                            'extra_discount' => $data[8], 
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
                    'customer_number' => $row[1],
                    'customer_name' => $row[2],
                    'invoice_aop' => $row[3],
                    'billing_document_date' => $row[4],
                    'part_no' => $row[5], //part_no
                    'billing_quantity' => $row[6], //qty
                    'billing_amount' => $row[7],
                    'spb_no' => $row[8], //invoice_aop
                    'tanggal_cetak_faktur' => $row[9], 
                    'tanggal_jatuh tempo' => $row[10], 
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
        
        $total_init = 0;
        
        foreach($stock as $s){
            $init = $s->stock_akhir * $s->dbp->het;
            $total_init += $init;
        }


        //DBP AOP
        $subquery = DB::table('invoice_aop_headers as a')
            ->join('invoice_aop_details as b', 'a.invoice_aop', '=', 'b.invoice_aop')
            ->whereBetween('a.billingDocument_date', [$tanggal_awal, $tanggal_akhir])
            ->where('a.kd_gudang', $kode->kode_gudang)
            ->groupBy('b.part_no')
            ->select('b.part_no', DB::raw('SUM(b.qty) as qty_beli'));


        $query = DB::table('part_aop_modal_test as k')
            ->leftJoinSub($subquery, 'l', 'k.part_no', '=', 'l.part_no')
            ->select('k.id', 'k.part_no', 'k.het', DB::raw('(k.het * l.qty_beli) AS dbp'));

        $results = $query->get();

        $dbp = $results->sum('dbp');

        //dd($dbp);

        //PEMBELIAN AOP
        $getPembelianAOP = InvoiceAOPHeader::where('kd_gudang', $kode->kode_gudang)
            ->whereBetween('billingDocument_date', [$tanggal_awal, $tanggal_akhir])
            ->get();

        //RETUR AOP
        $getReturAOP = ReturAOPHeader::whereBetween('crea_date', [$tanggal_awal, $tanggal_akhir])
            ->where('kd_gudang_aop', $kode->kode_kcp)
            ->where('approve_aop', 'Y')
            ->get();

        $retur_aop = $getReturAOP->sum('amount_total');

        //PENJUALAN 
        $getPenjualan = TransaksiInvoiceDetails::where('area_inv', $kode->kode_area)
            ->whereBetween('crea_date', [$tanggal_awal, $tanggal_akhir])
            ->get();

        $penjualan = $getPenjualan->sum('nominal_total');

        //RETUR PENJUALAN
        $getReturPenjualan = TransaksiReturHeader::whereBetween('flag_approve1_date', [$tanggal_awal, $tanggal_akhir])
            ->where('area_retur', $kode->kode_area)
            ->where('flag_approve1', 'Y')
            ->get();

        $total_retur = 0;

        foreach ($getReturPenjualan as $returHeader) {
            $total_retur += $returHeader->details_retur->nominal_total;
        }

       // dd($total_retur);

       // dd($total_init, $dbp, $retur_aop, $penjualan, $total_retur);

        //INSERT KE TABEL NILAI PERSEDIAAN
        $checkBulan = NilaiPersediaan::where('bulan', $bulan)->first();
        $checkTahun = NilaiPersediaan::where('bulan', $bulan)->first();
        $checkArea = NilaiPersediaan::where('area_inv', $kd)->first();

        $getPersediaanAkhirPrev = NilaiPersediaan::where('bulan', $bulan_prev)->where('area_inv', $kd)->pluck('persediaan_akhir')->first();       


        // if ($checkBulan === null && $checkTahun === null && $checkArea === null) {
            //init Januari 2023
        //     $data['bulan']                  = $bulan;
        //     $data['tahun']                  = $tahun;
        //     $data['area_inv']               = $kd;
        //     $data['persediaan_awal']        = $total_init;
        //     $data['pembelian']              = $dbp;
        //     $data['retur_aop']              = $retur_aop;
        //     $data['modal_terjual']          = $penjualan;
        //     $data['retur_modal_terjual']    = $total_retur;
        //     $data['persediaan_akhir']       = $total_init + $dbp - $retur_aop - $penjualan + $total_retur ;
        //     $data['status']                 = 'Y';
        //     $data['crea_date']              = Carbon::now();

        //    //dd($data);
            
        //    NilaiPersediaan::create($data);
           
       // }

        if ($checkBulan === null && $checkTahun === null && $checkArea === null) {
            //Next Bulan
            $data['bulan']                  = $bulan;
            $data['tahun']                  = $tahun;
            $data['area_inv']               = $kd;
            $data['persediaan_awal']        = $getPersediaanAkhirPrev;
            $data['pembelian']              = $dbp;
            $data['retur_aop']              = $retur_aop;
            $data['modal_terjual']          = $penjualan;
            $data['retur_modal_terjual']    = $total_retur;
            $data['persediaan_akhir']       = $getPersediaanAkhirPrev + $dbp - $retur_aop - $penjualan + $total_retur ;
            $data['status']                 = 'Y';
            $data['crea_date']              = Carbon::now();
            
            dd($data);
          //  NilaiPersediaan::create($data);
        }
            
        return redirect()->route('pembelian-aop.proses')->with('success','Data baru berhasil ditambahkan');
    }


}
