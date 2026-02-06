<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use \App\Traits\Auditable;

    const ADMIN = 'admin';
    const GESTOR = 'gestor';
    const COLABORADOR = 'colaborador';

    protected $table = 'roles';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    // Relación con usuarios (muchos a muchos)
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
