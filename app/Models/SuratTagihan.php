<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratTagihan extends Model
{
    use HasFactory;
    
    protected $table = 'surat_tagihan';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable =[
        'customer_number',
        'customer_name',
        'invoice_aop',
        'billing_document_date',
        'part_no',
        'billing_quantity',
        'billing_amount',
        'spb_no',
        'tanggal_cetak_faktur', 
        'tanggal_jatuh tempo', 
    ];
}
