<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Carbon\Carbon;
use App\Models\Blog;
use App\Models\Company;
use App\Models\Page;

class GenerateSitemap extends Command
{
    protected $signature = 'app:generate-sitemap';

    protected $description = 'Generate dynamic sitemap for the website';

    public function handle()
    {
        $sitemap = Sitemap::create();

        // Home page
        $sitemap->add(Url::create('/')
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(1.0));

        // All Blogs
        foreach (Blog::all() as $blog) {
            $sitemap->add(
                Url::create(route('blog.details', $blog->slug))
                    ->setLastModificationDate($blog->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8)
            );
        }

       // All Company
        foreach (Company::all() as $comapny) {
            $sitemap->add(
                Url::create(route('company.details', $comapny->slug))
                    ->setLastModificationDate($comapny->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8)
            );
        }

        // Static or CMS Pages (About, Contact, etc.)
        foreach (Page::all() as $page) {
            $sitemap->add(
                Url::create(url($page->slug))
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.6)
            );
        }

        // Save the sitemap file
        $sitemap->writeToFile('/home/sbxq5x4949wo/public_html/findsanything.com/sitemap.xml');

        $this->info('✅ Sitemap generated successfully!');
    }
}
