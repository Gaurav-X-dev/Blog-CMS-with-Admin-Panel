<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = "categories";
    protected $primaryKey = "id";

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'created_by',
    ];

    // Creator user
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    // Related blogs
    // public function blogs()
    // {
    //     return $this->hasMany(Blog::class, 'category_id');
    // }

    // Self-reference: parent category
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Self-reference: child categories
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function userStories()
    {
        return $this->hasMany(UserStory::class);
    }

}


