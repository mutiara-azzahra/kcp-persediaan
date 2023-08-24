<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Models\PembelianDBP;
use App\Models\InvoiceAOPHeader;

class PembelianDBPController extends Controller
{
    public function index(){

        $persediaan_dbp = PembelianDBP::all();

        return view('akun-pembelian.index', compact('persediaan_dbp'));
    }

    public function store(Request $request){

        $tanggal_awal   = $request->input('tanggal_awal');
        $tanggal_akhir  = $request->input('tanggal_akhir');
        $kd             = $request->input('area_inv');
        
        $insert = InvoiceAOPHeader::whereBetween('billingDocument_date', [$tanggal_awal, $tanggal_akhir])
            ->get();

        $successCount = 0;

        foreach ($insert as $i) {

            $details_dbp = $i->details;
            
            foreach($details_dbp as $d){
                $data = [
                        'id'              => $d->id,
                        'invoice_aop'     => $d->invoice_aop,
                        'customer_to'     => $d->customer_to,
                        'part_no'         => $d->part_no,
                        'qty'             => $d->qty,
                        'dbp'             => isset($d->dbp_jual->dbp) ? $d->dbp_jual->dbp : null,
                        'amount_dbp'      => isset($d->dbp_jual->dbp) ? $d->qty * $d->dbp_jual->dbp : null,
                        'no_sp_aop'       => $d->no_sp_aop,
                        'status'          => $d->status,
                        'ket_status'      => $d->ket_status,
                        'filename'        => $d->filename,
                        'crea_date'       => $d->crea_date,
                        'crea_by'         => $d->crea_by,
                        'modi_date'       => Carbon::now(),
                        'modi_by'         => $d->modi_by,
                    ];

                    $inserted = PembelianDBP::create($data);

                    if ($inserted) {
                        $successCount++;
                    }

            }
            
        }

        if ($successCount > 0) {
            return redirect()->route('akun-pembelian.index')->with('success', 'Data pembelian berhasil ditambahkan!');
        } else {
            return redirect()->route('akun-pembelian.index')->with('danger', 'Data pembelian gagal ditambahkan!');
        }
        
    }
}
