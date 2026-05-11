<?php

namespace App\Models;

use App\Models\Projectmanagerunit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProjectManagerTask;

class ProjectManager extends Model
{
    use HasFactory;
     protected $table = 'project_managers';
     protected $fillable = [
            'workorder_id',
            'workarea',
            'project',
            'pt_id',
            'user_pm',
            'qty',
            'target_date',
            'actualfinish_date',
            'status_pekerjaan',
            'persentase',
            'keterangan',
            'task_name',
            'pic',
            'activity_detail',
            'bl_start',
            'bl_finish',
            'actual_start',
            'duration',
            'unit',
            'date_awal',
            'date_akhir',
            'persentase_A',
            'persentase_B',
            'persentase_C',
          ];
          public function workorder()
          {
              return $this->belongsTo(Workorder::class);
          }
          public function imagesPM()
          {
              return $this->hasMany(ImagesPM::class);
          }
          public function units()
          {
               return $this->hasMany(Projectmanagerunit::class);
          }

          public function tasks()
            {
                return $this->hasMany(ProjectManagerTask::class);
            }
}
