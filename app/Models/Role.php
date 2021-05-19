<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    // The IDs for basic roles
    const ID_ADMIN   = 1;
    const ID_TEACHER = 2;
    const ID_STUDENT = 3;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * Fields that can be directly assigned.
     *
     * @var array
     */    protected $fillable = ['name', 'description', 'abilities'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
