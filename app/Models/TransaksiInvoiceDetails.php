<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiInvoiceDetails extends Model
{
    use HasFactory;

    protected $table = 'trns_inv_details';
    protected $primaryKey = 'id';
    public $timestamps = false;


    public function master()
    {
        return $this->belongsTo(PartAOPMaster::class, 'part_no', 'part_no');
    }

    public function dbp_jual()
    {
        return $this->belongsTo(PartAOPDbp::class, 'part_no', 'part_no');
    }
}
