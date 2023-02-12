<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Blogs;

use Illuminate\Support\Str;

class BlogContents extends Model
{
    use HasFactory;

    protected $table = 'blog_contents';

    protected $fillable = ['blog_uuid', 'content_title', 'content', 'images'];

    protected $casts = [
        'images' => 'array',
    ];

    public static function boot(){
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
            $model->created_by = auth()->id() ?? 1;
        });
    }

    public function blog(){
        return $this->hasOne(Blogs::class);
    }
}
