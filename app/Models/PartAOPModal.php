<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartAOPModal extends Model
{
    use HasFactory;

    protected $table = 'part_aop_modal';
    protected $primaryKey = 'id';
    public $timestamps = false;


    public function part_no()
    {
        return $this->hasOne(PartAOPMaster::class, 'id', 'id_part_no');
    }

    public function kode_part()
    {
        return $this->belongsTo(KodePart::class, 'id_part', 'id');
    }
}
