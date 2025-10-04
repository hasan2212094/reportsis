<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Indirectp extends Model
{
    use HasFactory, SoftDeletes;
    const DELETED_AT = 'deleted_at';
     protected $fillable =[
        'item_id',
        'Item',
        'Qty',
        'Unit',
        'Date_pengajuan',
        'Needed_by',
        'Total',
        'Notes',
    ];
     public function indirecta()
    {
        return $this->hasMany(Indirecta::class, 'indirectps_id');
    }
    
}
