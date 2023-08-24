<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PembelianDBP;

class PembelianDBPController extends Controller
{
    public function index(){

        $pembelian_dbp = PembelianDBP::all();

        return view('akun-pembelian.index', compact('pembelian_dbp'));
    }

    public function store(Request $request){

        $tanggal_awal   = $request->input('tanggal_awal');
        $tanggal_akhir  = $request->input('tanggal_akhir');
        $kd             = $request->input('area_inv');
        $aop            = 1;
        
        $part_aop = PartAOPMaster::where('id_part', $aop)->pluck('part_no');
        
        $insert = TransaksiInvoiceDetails::whereBetween('crea_date', [$tanggal_awal, $tanggal_akhir])
            ->whereIn('part_no', $part_aop)
            ->get();

        $successCount = 0;

        foreach ($insert as $i) {
            $data = [
                        'id'              => $i->id,
                        'noinv'           => $i->noinv,
                        'area_inv'        => $i->area_inv,
                        'kd_outlet'       => $i->kd_outlet,
                        'part_no'         => $i->part_no,
                        'nm_part'         => $i->nm_part,
                        'qty'             => $i->qty,
                        'dbp'             => $i->dbp_jual->dbp,
                        'nominal_total'   => $i->qty * $i->dbp_jual->dbp,
                        'status'          => $i->status,
                        'crea_date'       => $i->crea_date,
                        'crea_by'         => $i->crea_by,
                        'modi_date'       => Carbon::now(),
                        'modi_by'         => $i->modi_by,
                    ];

            $inserted = TransaksiInvoiceDetailsDbp::create($data);

            if ($inserted) {
                $successCount++;
            }
        }

        if ($successCount > 0) {
            return redirect()->route('akun-persediaan.index')->with('success', 'Data persediaan berhasil ditambahkan!');
        } else {
            return redirect()->route('akun-persediaan.index')->with('danger', 'Data persediaan gagal ditambahkan!');
        }
        
    }
}
