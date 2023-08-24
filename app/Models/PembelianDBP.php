<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianDBP extends Model
{
    use HasFactory;

    protected $table = 'invoice_aop_details_dbp';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable =[
        'id',
        'invoice_aop',
        'customer_to',
        'part_no',
        'qty',
        'dbp',
        'amount_dbp',
        'no_sp_aop',
        'status',
        'ket_status',
        'filename',
        'crea_date',
        'crea_by',
        'modi_date',
        'modi_by'
    ];
}
