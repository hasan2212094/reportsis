<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectP extends Model
{
    use HasFactory;
    protected $fillable =[
        'item_id',
        'Item',
        'Qty',
        'Unit',
        'Needed_by',
        'Date_pengajuan',
        'Total',
        'Notes',
    ];
    public function Directa()
    {
        return $this->hasMany(Directa::class, 'direct_p_s_id');
    }
}
