<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'footer_links', 'banner_content', 'content', 'created_by'];

    protected $casts = [
        'footer_links' => 'array',
        'banner_content' => 'array',
    ];

    // Many-to-Many with Page
    public function pages()
    {
        return $this->belongsToMany(Page::class, 'page_section')->withPivot('order')->withTimestamps();
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function sectionMenuItems()
    {
        return $this->hasMany(SectionMenuItems::class, 'section_id', 'id');
    }

    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'section_menu_items');
    }



    // Ensure null never happens
    public function getMenuItemsAttribute($value)
    {
        return $value ?? collect([]);
    }


    // Decode footer links JSON
    public function getFooterLinksAttribute($value)
    {
        return json_decode($value, true);
    }
}

