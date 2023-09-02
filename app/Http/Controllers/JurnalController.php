<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\InvoiceAOPHeader;

class JurnalController extends Controller
{
    public function index(){

        

        return view('jurnal.index');
    }

    public function store(){

        $tanggal_awal   = $request->input('tanggal_awal');
        $tanggal_akhir  = $request->input('tanggal_akhir');

        $getPembelianAOP = InvoiceAOPHeader::where('kd_gudang', $kode->kode_gudang)
            ->whereBetween('billingDocument_date', [$tanggal_awal, $tanggal_akhir])
            ->get();

        return redirect()->route('jurnal.index')->with('success','Jurnal Berhasil diproses');
    }
}
