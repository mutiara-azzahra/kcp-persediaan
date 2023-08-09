<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kode extends Model
{
    use HasFactory;

    protected $table = 'kode';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function persediaan()
    {
        return $this->hasMany(NilaiPersediaan::class, 'area_inv', 'kode_area');
    }

}
