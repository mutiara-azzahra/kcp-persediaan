<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodePart extends Model
{
    use HasFactory;

    protected $table = 'mst_kode_part';
    protected $primaryKey = 'id';
    public $timestamps = false;


    public function master(){
        return $this->hasMany(PartAOPMaster::class, 'id', 'id_part');
    }
    
}
