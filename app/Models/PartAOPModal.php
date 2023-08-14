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

    protected $fillable =[
        'het',
        'modi_date'
    ];

}
