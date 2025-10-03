<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\admin\BlogController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Section;
use App\Models\Advertisement;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
            View::composer('*', function ($view) {
                $settings = Setting::find(1);
                $categories = Category::all();
                $menus = MenuItem::orderBy('order')->get();
                
                // ✅ Get all footer-type sections
                $footerSections = Section::where('type', 'footer')->get();
                $advertisements = Advertisement::where('is_active', '>', 0)
            ->get();

                $view->with([
                    'settings' => $settings,
                    'categories' => $categories,
                    'menus' => $menus,
                    'footerSections' => $footerSections,
                    'advertisements'=>$advertisements
                ]);
            });


    }
}
