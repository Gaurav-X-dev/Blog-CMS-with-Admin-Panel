<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SectionMenuItems extends Model
{
    use HasFactory;
    
    protected $table = "section_menu_items";
    protected $fillable = ['section_id', 'menu_item_id'];

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id', 'id');
    }
}

