<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Fields that can be directly assigned.
     *
     * @var array
     */
    protected $fillable = ['ra', 'cpf', 'name', 'email', 'password', 'role_id'];

    /**
     * Scope a query to only include admins.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdmins($query)
    {
        return $query->where('role_id', Role::ID_ADMIN);
    }

    /**
     * Scope a query to only include teachers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTeachers($query)
    {
        return $query->where('role_id', Role::ID_TEACHER);
    }

    /**
     * Scope a query to only include students.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStudents($query)
    {
        return $query->where('role_id', Role::ID_STUDENT);
    }

    //Relationships

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
