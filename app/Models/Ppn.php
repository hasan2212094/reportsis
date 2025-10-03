<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ppn extends Model
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

    public function ppnas()
    {
        return $this->hasMany(Ppna::class);
    }
}
