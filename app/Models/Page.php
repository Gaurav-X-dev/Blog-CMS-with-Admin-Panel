<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory;
    protected $table = "pages";
    protected $primaryKey = "id";
    protected $fillable = [
        'title', 
        'slug', 
        'meta_title', 
        'meta_description', 
        'meta_keywords', 
        'template', 
        'created_by'
    ];
    
    public function user()
    {
        return $this->hasOne(User::class, 'id','created_by');
    }
     // Many-to-Many with Section
    public function sections()
    {
        return $this->belongsToMany(Section::class, 'page_section')
                    ->withPivot('order')
                    ->withTimestamps()
                    ->orderBy('pivot_order'); // Optional, based on your pivot column
    }
    // Automatically generate slug from title
    public static function boot()
    {
        parent::boot();
        static::creating(function ($page) {
            $page->slug = Str::slug($page->title);
        });
    }
}

