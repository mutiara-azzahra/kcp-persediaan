<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PartNonHeader;
use App\Models\PartNonDetails;
use App\Models\NilaiPersediaanNon;

class PembelianNonController extends Controller
{

    public function index(){

        $getPersediaan = NilaiPersediaanNon::all();

        return view('non-aop.index', compact('getPersediaan'));
    }

    public function prosesPersediaan(Request $request){

        $tanggal_awal   = $request->input('tanggal_awal');
        $tanggal_akhir  = $request->input('tanggal_akhir');
        $kd             = $request->input('area_inv');
        $aop            = 2;

        $non = PartNonHeader::whereBetween('tgl_nota', [$tanggal_awal, $tanggal_akhir])->get();

        $total_harga = 0;

        foreach($non as $n){
            
            $harga = $n->total_harga;
            $total_harga += $harga;

        }

        dd($total_harga);

        return redirect()->route('pembelian-aop.index')->with('success', 'Data persediaan berhasil ditambahkan!');
    }

}
