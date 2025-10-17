<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ppn extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'ppns'; // <--- tambahkan ini
    const DELETED_AT = 'deleted_at';
    protected $fillable =[
        'item_id',
        'Item',
        'Qty',
        'workorder_id',
        'Unit',
        'Needed_by',
        'Date_pengajuan',
        'Total',
        'Notes',
    ];

    public function ppnas()
    {
        return $this->hasMany(Ppna::class, 'ppns_id', 'id');
    }
    public function workorder()
    {
      return $this->belongsTo(Workorder::class, 'workorder_id', 'id', 'kode_wo');
    }
    public function ppn()
{
    return $this->belongsTo(Ppn::class, 'ppns_id', 'id');
}
}
