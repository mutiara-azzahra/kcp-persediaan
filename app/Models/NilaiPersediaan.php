<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiPersediaan extends Model
{
    use HasFactory;

    protected $table = 'nilai_persediaan';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable =[
        'tanggal_awal',
        'tanggal_akhir',
        'bulan',
        'tahun',
        'area_inv',
        'persediaan_awal',
        'pembelian',
        'retur_aop',
        'modal_terjual',
        'retur_modal_terjual',   
        'persediaan_akhir',
        'status',
        'crea_date',
        'modi_date'
    ];

    public function kode()
    {
        return $this->hasOne(Kode::class, 'kode_area', 'area_inv');
    }
}
