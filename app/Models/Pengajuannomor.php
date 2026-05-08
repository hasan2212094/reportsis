<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuannomor extends Model
{
    use HasFactory;
    protected $fillable =['nomor'];
    public function Rabpengajuan ()
    {
        return $this->hasMany(Rabpengajuan::class, 'rab_id');
    }

}
