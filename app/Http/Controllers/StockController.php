<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\InitMasterPart;

class StockController extends Controller
{
    
    public function index(){

        $kg = 'G1';

        $stock = InitMasterPart::where('kd_gudang', $kg)->get();

        dd($stock);


        return view('stock.index');
    }
}
