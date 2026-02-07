<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectTeamMember extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    public $timestamps = false;

    public function team()
    {
        return $this->belongsTo(ProjectTeam::class, 'id_project_team');
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota');
    }
}
