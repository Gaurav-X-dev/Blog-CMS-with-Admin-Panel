<?php

// app/Models/Advertisement.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Advertisement extends Model
{
    use HasFactory;

    protected $table = "advertisements";
    protected $primaryKey = "id";
    protected $fillable = ['title', 'image', 'link', 'position', 'is_active','company_name', 'company_email', 'company_phone','start_date', 'end_date','created_by'];
}
