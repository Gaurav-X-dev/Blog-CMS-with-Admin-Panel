<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run()
    {
        Setting::firstOrCreate([
            'company_name' => 'My Bookmarking',
            'email' => 'info@mybookmarking.com',
            'mobile' => '9876543210',
            'landline' => '011-12345678',
            'address' => '123 Business Street, New Delhi, India',
            'logo' => null,
            'footer_logo' => null,
            'website' => 'https://www.mybookmarking.com',
            'google_a' => 'UA-12345678-1',
            'google_web' => 'https://www.google.com/webmasters/',
            'facebook' => 'https://facebook.com/mybookmarking',
            'twitter' => 'https://twitter.com/mybookmarking',
            'instagram' => 'https://instagram.com/mybookmarking',
            'linkedin' => 'https://linkedin.com/company/mybookmarking',
            'youtube' => 'https://youtube.com/mybookmarking',
        ]);
    }
}

