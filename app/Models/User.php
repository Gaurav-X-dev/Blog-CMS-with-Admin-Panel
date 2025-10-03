<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Role;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'core_password',
        'address',
        'description',
        'photo',
        'status', /*default value 1*/
        'display_name',
        'created_by', // Track who created the user (Vendor ID)
    ];

    protected $guard_name = 'web';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationship: User created by another user (Vendor)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
