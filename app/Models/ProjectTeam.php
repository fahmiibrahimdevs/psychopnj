<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectTeam extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class, 'id_project');
    }

    public function members()
    {
        return $this->hasMany(ProjectTeamMember::class, 'id_project_team');
    }

    public function leader()
    {
        return $this->hasOne(ProjectTeamMember::class, 'id_project_team')->where('role', 'leader');
    }

    public function anggotas()
    {
        return $this->hasMany(ProjectTeamMember::class, 'id_project_team')->where('role', 'anggota');
    }
}
