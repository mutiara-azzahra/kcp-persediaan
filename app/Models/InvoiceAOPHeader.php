<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceAOPHeader extends Model
{
    use HasFactory;

    protected $table = 'invoice_aop_headers';
    protected $primaryKey = 'invoice_aop';
    public $timestamps = false;


    public function details()
    {
        return $this->hasMany(InvoiceAOPDetails::class, 'invoice_aop', 'invoice_aop');
    }
    
    
}
