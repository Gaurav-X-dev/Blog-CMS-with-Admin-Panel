<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserStory extends Model
{
    protected $fillable = [
        'member_id',
        'title',
        'slug',
        'category_id',
        'page_link',
        'description',
        'tag',
        'views',
        'status',
    ];


    protected $casts = [
        'views' => 'integer',
    ];

    // Relationships
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
