<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DirectP extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'direct_p_s';

    protected $fillable = [
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

    protected $casts = [
        'Date_pengajuan' => 'date',
        'Total' => 'decimal:2',
    ];

    /* ================= RELATIONS ================= */

    // Direct Actual
    public function directas()
    {
        return $this->hasMany(Directa::class, 'direct_p_s_id', 'id');
    }

    // Workorder (INI YANG BENAR)
    public function workorder()
    {
        return $this->belongsTo(Workorder::class, 'workorder_id', 'id');
    }
}
