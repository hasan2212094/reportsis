<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cnc extends Model
{
    protected $table = 'cncs';
    protected $fillable = [
        'plat_ke',
        'arus',
        'energi_cycle',
    ];

}
