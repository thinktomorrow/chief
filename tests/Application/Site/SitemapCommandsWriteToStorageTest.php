<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Site;

use Illuminate\Support\Facades\File;
use Mockery;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Thinktomorrow\Chief\App\Console\GenerateImageSitemap;
use Thinktomorrow\Chief\App\Console\GenerateSitemap;
use Thinktomorrow\Chief\Site\Sitemap\ImageSitemapXml;
use Thinktomorrow\Chief\Site\Sitemap\ImageSitemapXmlFile;
use Thinktomorrow\Chief\Site\Sitemap\SitemapXml;
use Thinktomorrow\Chief\Site\Sitemap\SitemapXmlFile;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class SitemapCommandsWriteToStorageTest extends ChiefTestCase
{
    protected function tearDown(): void
    {
        File::deleteDirectory(storage_path('app/feeds'));

        $this->resetChiefSitesCache();

        Mockery::close();

        parent::tearDown();
    }

    public function test_chief_sitemap_command_writes_locale_and_default_files_to_storage(): void
    {
        config()->set('chief.sites', [
            ['locale' => 'nl', 'name' => 'NL', 'short_name' => 'NL', 'domain' => 'localhost', 'active' => true, 'primary' => true],
            ['locale' => 'en', 'name' => 'EN', 'short_name' => 'EN', 'domain' => 'localhost', 'active' => true, 'primary' => false],
        ]);
        config()->set('app.fallback_locale', 'nl');
        $this->resetChiefSitesCache();

        $sitemapXmlFile = Mockery::mock(SitemapXmlFile::class);
        $sitemapXmlFile->shouldReceive('create')->once()->with('nl', storage_path('app/feeds/sitemap-nl.xml'), Mockery::on(static fn (array $locales): bool => array_values($locales) === ['en']))->andReturnUsing(function (string $locale, string $filepath): void {
            File::put($filepath, '<urlset>chief-'.$locale.'</urlset>');
            File::put(dirname($filepath).'/sitemap.xml', '<urlset>chief-'.$locale.'</urlset>');
        });
        $sitemapXmlFile->shouldReceive('create')->once()->with('en', storage_path('app/feeds/sitemap-en.xml'), Mockery::on(static fn (array $locales): bool => array_values($locales) === ['nl']))->andReturnUsing(function (string $locale, string $filepath): void {
            File::put($filepath, '<urlset>chief-'.$locale.'</urlset>');
        });
        $command = new GenerateSitemap($sitemapXmlFile);
        $command->setLaravel($this->app);
        $command->run(new ArrayInput([]), new NullOutput);

        $this->assertSame('<urlset>chief-nl</urlset>', File::get(storage_path('app/feeds/sitemap.xml')));
        $this->assertSame('<urlset>chief-nl</urlset>', File::get(storage_path('app/feeds/sitemap-nl.xml')));
        $this->assertSame('<urlset>chief-en</urlset>', File::get(storage_path('app/feeds/sitemap-en.xml')));
    }

    public function test_chief_image_sitemap_command_writes_locale_and_default_files_to_storage(): void
    {
        config()->set('chief.sites', [
            ['locale' => 'nl', 'name' => 'NL', 'short_name' => 'NL', 'domain' => 'localhost', 'active' => true, 'primary' => true],
            ['locale' => 'en', 'name' => 'EN', 'short_name' => 'EN', 'domain' => 'localhost', 'active' => true, 'primary' => false],
        ]);
        config()->set('app.fallback_locale', 'nl');
        $this->resetChiefSitesCache();

        $imageSitemapXmlFile = Mockery::mock(ImageSitemapXmlFile::class);
        $imageSitemapXmlFile->shouldReceive('create')->once()->with('nl', storage_path('app/feeds/image-sitemap-nl.xml'))->andReturnUsing(function (string $locale, string $filepath): void {
            File::put($filepath, '<urlset>image-'.$locale.'</urlset>');
            File::put(dirname($filepath).'/image-sitemap.xml', '<urlset>image-'.$locale.'</urlset>');
        });
        $imageSitemapXmlFile->shouldReceive('create')->once()->with('en', storage_path('app/feeds/image-sitemap-en.xml'))->andReturnUsing(function (string $locale, string $filepath): void {
            File::put($filepath, '<urlset>image-'.$locale.'</urlset>');
        });
        $command = new GenerateImageSitemap($imageSitemapXmlFile);
        $command->setLaravel($this->app);
        $command->run(new ArrayInput([]), new NullOutput);

        $this->assertSame('<urlset>image-nl</urlset>', File::get(storage_path('app/feeds/image-sitemap.xml')));
        $this->assertSame('<urlset>image-nl</urlset>', File::get(storage_path('app/feeds/image-sitemap-nl.xml')));
        $this->assertSame('<urlset>image-en</urlset>', File::get(storage_path('app/feeds/image-sitemap-en.xml')));
    }

    public function test_sitemap_xml_file_writes_default_file_next_to_locale_file(): void
    {
        config()->set('app.fallback_locale', 'nl');

        $sitemapXml = Mockery::mock(SitemapXml::class);
        $sitemapXml->shouldReceive('generate')->once()->with('nl', [])->andReturn('<urlset>chief-nl</urlset>');

        $writer = new SitemapXmlFile($sitemapXml);
        $writer->create('nl', storage_path('app/feeds/sitemap-nl.xml'));

        $this->assertSame('<urlset>chief-nl</urlset>', File::get(storage_path('app/feeds/sitemap-nl.xml')));
        $this->assertSame('<urlset>chief-nl</urlset>', File::get(storage_path('app/feeds/sitemap.xml')));
    }

    public function test_image_sitemap_xml_file_writes_default_file_next_to_locale_file(): void
    {
        config()->set('app.fallback_locale', 'nl');

        $imageSitemapXml = Mockery::mock(ImageSitemapXml::class);
        $imageSitemapXml->shouldReceive('generate')->once()->with('nl')->andReturn('<urlset>image-nl</urlset>');

        $writer = new ImageSitemapXmlFile($imageSitemapXml);
        $writer->create('nl', storage_path('app/feeds/image-sitemap-nl.xml'));

        $this->assertSame('<urlset>image-nl</urlset>', File::get(storage_path('app/feeds/image-sitemap-nl.xml')));
        $this->assertSame('<urlset>image-nl</urlset>', File::get(storage_path('app/feeds/image-sitemap.xml')));
    }

    private function resetChiefSitesCache(): void
    {
        $reflection = new \ReflectionClass(ChiefSites::class);
        $property = $reflection->getProperty('cachedSites');
        $property->setValue(null, null);
    }
}
