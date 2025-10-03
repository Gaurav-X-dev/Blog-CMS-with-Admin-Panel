<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PageSection extends Model
{
    use HasFactory;

    protected $table = 'page_section';

    protected $fillable = ['page_id', 'section_id', 'order', 'created_by'];

    
    public function user()
    {
        return $this->hasOne(User::class, 'id','created_by');
    }
    public function sections()
    {
        return $this->belongsToMany(Section::class, 'page_section')
                    ->withPivot('order')
                    ->orderBy('page_section.order') // Add this to order by the `order` column
                    ->withTimestamps();
    }


    public function page()
    {
        return $this->belongsTo(Page::class);
    }
    
}

