<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DirectP extends Model
{
    use HasFactory, SoftDeletes;
    const DELETED_AT = 'deleted_at';
    protected $fillable =[
        'item_id',
        'Item',
        'Qty',
        'Workorder_id',
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
    
 public function workorder()
    {
        return $this->belongsTo(Workorder::class, 'workorder_id');
    }
}
