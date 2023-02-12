<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Categories;
use App\Models\BlogContents;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blogs extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'slug', 'content', 'page_title', 'metadata', 'keywords', 'status'];

    public static function boot(){
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
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

    public function categories(){
        return $this->belongsToMany(Categories::class);
    }

    public function contents(){
        return $this->hasMany(BlogContents::class, 'blog_uuid', 'uuid');
    }

    public static function generateSlug($name){
        $slug = Str::slug($name);
        $duplicate = static::withTrashed()->where('slug', 'like', '%' . $slug . '%')->count();
        return ($duplicate > 0) ? $slug . '-' . ($duplicate + 1) : $slug;
    }
}
