<?php

namespace Thinktomorrow\Chief\App\Console;

use Psr\Http\Message\UriInterface;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends BaseCommand
{
    protected $signature    = 'chief:sitemap';
    protected $description  = 'Generate a sitemap by crawling the site';

    public function handle()
    {
        $this->info('Starting sitemap generation for: '. config('app.url'));

        $locales = config('translatable.locales');

        foreach(config('translatable.locales') as $key => $locale)
        {
            $this->info('Generating for locale: '.$locale);

            // here we assume the first locale in the array is the 'default' and thus isn't used in the url.
            $url = config('app.url');
            if(count($locales) > 1 && $key > 0) $url = config('app.url').'/'.$locale;

            SitemapGenerator::create($url)
                ->hasCrawled(function (Url $url){
                    if (strpos($url->url, '?') === false) {
                        return $url;
                    }

                    return;
                })->shouldCrawl(function (UriInterface $url) use($key, $locale){
                    if($key == 0) return true;

                    if(strpos($url->getPath(), '/'.$locale) !== false)
                    {
                        return true;
                    }

                    return false;
                })
                ->writeToFile(public_path('sitemap-'.$locale.'.xml'));
        }

        $this->info('Sitemap generated and placed at: '. public_path());
    }
}
