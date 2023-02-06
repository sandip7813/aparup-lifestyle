<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['name', 'slug'];

    public static function boot(){
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function roles(){
        return $this->belongsToMany(Role::class, 'permission_role');
    }

    public function users(){
        return $this->belongsToMany(User::class, 'permission_user');
    }
}
