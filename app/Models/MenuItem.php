<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuItem extends Model
{
    use HasFactory;

    protected $table = "menu_items";
    protected $primaryKey = "id";
    protected $fillable = [
  
        'name',
        'slug',
        'parent_id',
        'link_type',
        'link_value',
        'order',
        'created_by',

    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    // One-to-One with Page
    public function page()
    {
        return $this->hasOne(Page::class, 'slug', 'slug');
    }

    // Parent-Child Relationship for Submenus
    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id');
    }
    public function sections()
    {
        return $this->belongsToMany(Section::class, 'section_menu_items');
    }

}
