<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workorder extends Model
{
    use HasFactory;
    protected $fillable =['kode_wo'];
      public function directPs()
    {
        return $this->hasMany(DirectP::class, 'workorder_id');
    }
     public function ppns()
    {
        return $this->hasMany(Ppn::class, 'workorder_id');
    }
    public function directas ()
    {
        return $this->hasMany(Directa::class, 'workorder_id');
    }
     public function ppnas ()
    {
        return $this->hasMany(Ppna::class, 'workorder_id');
    }
    public function luarrab ()
    {
        return $this->hasMany(Luarrab::class, 'workorder_id');
    }

}
