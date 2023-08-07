<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceAOPDetails extends Model
{
    use HasFactory;

    protected $table = 'invoice_aop_details';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function header()
    {
        return $this->belongsTo(InvoiceAOPHeader::class, 'invoice_aop', 'invoice_aop');
    }
}
