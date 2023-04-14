<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Comments extends Model
{
    use HasFactory, SoftDeletes, HasRecursiveRelationships;

    protected $fillable = ['blog_uuid', 'parent_id', 'name', 'email', 'website', 'content'];

    public static function boot(){
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
            $model->ip = \Request::ip();
        });
    }

    public function subcategory(){
        return $this->hasMany(\App\Models\Comments::class, 'parent_id')->where('status', '1');
    }

    public function parent(){
        return $this->belongsTo(\App\Models\Comments::class, 'parent_id')->where('status', '1');
    }
}
