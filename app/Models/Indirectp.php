<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indirectp extends Model
{
    use HasFactory;
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
