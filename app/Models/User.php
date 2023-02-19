<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Role;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const role_super_admin = 'super-admin';
    const role_admin = 'admin';
    const role_user = 'user';
    const role_editor = 'editor';
    const role_author = 'author';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function boot(){
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function role(){
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function getRoleName(){
        $role = $this->role()->first();
        return $role->name ?? null;
    }

    public function getRoleSlug(){
        $role = $this->role()->first();
        return $role->slug ?? null;
    }

    public function roleSuperAdmin(){
        return self::role_super_admin == $this->getRoleSlug();
    }

    public function roleAdmin(){
        return self::role_admin == $this->getRoleSlug();
    }

    public function roleUser(){
        return self::role_user == $this->getRoleSlug();
    }

    public function roleEditor(){
        return self::role_editor == $this->getRoleSlug();
    }

    public function roleAuthor(){
        return self::role_author == $this->getRoleSlug();
    }

    public function profile_picture(){
        return $this->hasOne(Medias::class, 'source_uuid', 'uuid')->where('media_type', 'user_profile');
    }
}
