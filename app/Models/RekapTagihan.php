<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapTagihan extends Model
{
    use HasFactory;

    protected $table = 'rekap_tagihan';
    protected $primaryKey = 'id';

    protected $fillable =[
        'customer_number',
        'customer_name',
        'invoice_aop',
        'billing_document_date',
        'tanggal_jatuh_tempo',
        'billing_amount_ppn',
        'additional_discount',
        'cash_discount',
        'extra_discount',
    ];

}
