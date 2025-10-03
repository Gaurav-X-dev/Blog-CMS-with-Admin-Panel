<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use HasFactory;

    protected $table = "blog";
    protected $primaryKey = "id";
    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'tag',
        'topic',
        'short_description',
        'description',
        'company_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'approved_id',
        'approval_date',
        'post_date',
        'views',
        'is_feature',
        'created_by',
        'banner',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id','created_by');
    }
    public function company()
    {
        return $this->hasOne(Company::class, 'id','company_id');
    }
    public function approve()
    {
        return $this->hasOne(User::class, 'id','approved_id');
    }
    // Blog.php
    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'blog_id', 'id');
    }

}

