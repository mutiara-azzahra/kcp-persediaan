<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\DbpAop;
use App\Models\InitMasterPart;
use App\Imports\ExcelImport;
use Maatwebsite\Excel\Facades\Excel;

class UploadDBPController extends Controller
{
    public function index()
    {
        return view('upload-dbp.index');
    }

    public function uploadDbp(Request $request)
    {
        $file = $request->file('excel_file');

        if ($file) {
            $import = new ExcelImport();

            Excel::import($import, $file);

            $data   = $import->getData();
            $now    = Carbon::now();

            foreach ($data as $row) {

            $inserted = DbpAop::where('part_no', $row['part_no'])
                ->update([
                    'het' => $row['het'],
                    'modi_date' => $now
                ]);
            }

            if ($inserted) {
                return redirect()->route('upload-dbp.index')->with('success','Berhasil Upload');

            } else {
                return redirect()->route('upload-dbp.index')->with('warning','Upload gagal');
            }
        }

        return redirect()->route('upload-dbp.index')->with('danger','Upload gagal');
    }
}
