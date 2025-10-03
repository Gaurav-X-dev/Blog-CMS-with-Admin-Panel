<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;
    protected $table = "setting";
    protected $primaryKey = "id";
    protected $fillable = [
        'company_name',
        'email',
        'mobile',
        'landline',
        'address',
        'logo',
        'footer_logo',
        'website',
        'google_a',
        'google_web',
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'youtube',
    ];
}
