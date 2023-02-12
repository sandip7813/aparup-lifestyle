<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Blogs;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Categories extends Model
{
    use HasFactory, SoftDeletes, HasRecursiveRelationships;

    protected $fillable = ['name', 'slug', 'parent_id', 'content', 'page_title', 'metadata', 'keywords'];

    public static function boot(){
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
            $model->type = 'blog';
            $model->created_by = auth()->id() ?? 1;
        });

        self::updating(function ($model) {
            if ($model->isDirty()) {
                $model->updated_by = auth()->id();
            }
        });

        self::deleting(function ($model) {
            $model->deleted_by = auth()->id();
        });
    }

    public function blogs(){
        return $this->belongsToMany(Blogs::class);
    }

    public function subcategory(){
        return $this->hasMany(\App\Models\Categories::class, 'parent_id');
    }

    public function parent(){
        return $this->belongsTo(\App\Models\Categories::class, 'parent_id');
    }

    public static function generateSlug($name){
        $slug = Str::slug($name);
        $duplicate = static::withTrashed()->where('slug', 'like', '%' . $slug . '%')->count();
        return ($duplicate > 0) ? $slug . '-' . ($duplicate + 1) : $slug;
    }

}
