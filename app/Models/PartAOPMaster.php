<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartAOPMaster extends Model
{
    use HasFactory;

    protected $table = 'part_aop_master';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function part_no()
    {
        return $this->hasOne(InitMasterPart::class, 'id_part_no', 'id');
    }

    public function modal()
    {
        return $this->belongsTo(PartAOPModal::class, 'id_part_no', 'id');
    }

    public function level(){
        return $this->belongsTo(MasterLevel4::class, 'id_level', 'id');
    }

    public function invoice_details()
    {
        return $this->hasMany(InvoiceAOPDetails::class, 'part_no', 'part_no');
    }

    public function kode_part(){
        return $this->belongsTo(KodePart::class, 'id_part', 'id');
    }

    public function transaksi(){
        return $this->hasMany(TransaksiInvoiceDetails::class, 'part_no', 'part_no');
    }

    public function dbp()
    {
        return $this->belongsTo(DbpAop::class, 'part_no', 'part_no');
    }
}
