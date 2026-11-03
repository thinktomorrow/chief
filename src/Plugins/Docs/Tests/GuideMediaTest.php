<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

use Thinktomorrow\Chief\Plugins\Docs\DocSources;

class GuideMediaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->useProjectGuides($this->fixture('media'));
        $this->app->make(DocSources::class)->add('acme', $this->fixture('media-acme'));
    }

    public function test_every_allowed_type_is_served_from_its_source(): void
    {
        $contentTypes = [
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'avif' => 'image/avif',
            'svg' => 'image/svg+xml',
            'mp4' => 'video/mp4',
            'webm' => 'video/webm',
        ];

        foreach ($contentTypes as $extension => $contentType) {
            $response = $this->asAdmin()->get('/admin/docs/opleidingen/bestanden/voorbeeld.'.$extension)
                ->assertOk()
                ->assertHeader('Content-Type', $contentType);

            $this->assertSame($this->fixture('media/opleidingen/bestanden/voorbeeld.'.$extension), $response->baseResponse->getFile()->getPathname());
        }
    }

    public function test_extensions_are_matched_regardless_of_case(): void
    {
        $this->asAdmin()->get('/admin/docs/opleidingen/bestanden/HOOFDLETTERS.PNG')
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png');
    }

    public function test_other_extensions_markdown_and_paths_leaving_the_source_are_not_served(): void
    {
        $this->asAdmin()->get('/admin/docs/opleidingen/bestanden/voorbeeld.txt')->assertNotFound();
        $this->asAdmin()->get('/admin/docs/opleidingen/index.md')->assertNotFound();
        $this->asAdmin()->get('/admin/docs/opleidingen/../start.png')->assertNotFound();
        $this->asAdmin()->get('/admin/docs/opleidingen/%2E%2E/start.png')->assertNotFound();
        $this->asAdmin()->get('/admin/docs/acme::../media/start.png')->assertNotFound();
        $this->asAdmin()->get('/admin/docs/opleidingen/bestaat-niet.png')->assertNotFound();
        $this->asAdmin()->get('/admin/docs/onbekend::scherm.png')->assertNotFound();
    }

    public function test_assets_are_only_served_to_admins(): void
    {
        $this->get('/admin/docs/opleidingen/schermen/aanmaken.png')->assertRedirect();
    }

    public function test_svg_is_sandboxed(): void
    {
        $this->asAdmin()->get('/admin/docs/opleidingen/bestanden/voorbeeld.svg')
            ->assertOk()
            ->assertHeader('Content-Security-Policy', 'sandbox');

        $this->asAdmin()->get('/admin/docs/opleidingen/bestanden/voorbeeld.png')
            ->assertHeaderMissing('Content-Security-Policy');
    }

    public function test_responses_can_be_revalidated(): void
    {
        $response = $this->asAdmin()->get('/admin/docs/opleidingen/schermen/aanmaken.png')
            ->assertOk()
            ->assertHeader('Last-Modified');

        $etag = $response->headers->get('ETag');
        $this->assertNotEmpty($etag);

        $this->asAdmin()->get('/admin/docs/opleidingen/schermen/aanmaken.png', ['If-None-Match' => $etag])
            ->assertStatus(304);
    }

    public function test_assets_of_hidden_sections_are_not_served(): void
    {
        $this->asAdmin()->get('/admin/docs/verborgen/scherm.png')->assertOk();

        config()->set('chief-docs.hidden', ['verborgen']);

        $this->asAdmin()->get('/admin/docs/verborgen/scherm.png')->assertNotFound();
    }

    public function test_assets_of_sections_the_admin_may_not_see_are_not_served(): void
    {
        $this->asAdmin()->get('/admin/docs/admins/scherm.png')->assertOk();
        $this->asAuthor()->get('/admin/docs/admins/scherm.png')->assertNotFound();
    }

    public function test_an_asset_in_a_folder_of_its_own_follows_the_section_around_it(): void
    {
        $this->asAdmin()->get('/admin/docs/opleidingen/schermen/aanmaken.png')->assertOk();

        config()->set('chief-docs.hidden', ['opleidingen']);

        $this->asAdmin()->get('/admin/docs/opleidingen/schermen/aanmaken.png')->assertNotFound();
    }

    public function test_a_project_file_replaces_a_shipped_asset(): void
    {
        $response = $this->asAdmin()->get('/admin/docs/acme::schermen/knop.png')->assertOk();
        $this->assertSame($this->fixture('media/vendor/acme/schermen/knop.png'), $response->baseResponse->getFile()->getPathname());

        $response = $this->asAdmin()->get('/admin/docs/acme::schermen/zonder-override.png')->assertOk();
        $this->assertSame($this->fixture('media-acme/schermen/zonder-override.png'), $response->baseResponse->getFile()->getPathname());

        $this->asAdmin()->get('/admin/docs/vendor/acme/schermen/knop.png')->assertNotFound();
    }

    public function test_images_render_as_lazy_loaded_figures_with_their_alt_text_as_caption(): void
    {
        $this->assertGuideShows(
            '<figure><img src="'.$this->assetUrl('opleidingen/schermen/aanmaken.png').'" alt="Een opleiding aanmaken" loading="lazy" data-guide-media />'
            .'<figcaption>Een opleiding aanmaken</figcaption></figure>'
        );
    }

    public function test_an_image_without_alt_text_has_no_caption(): void
    {
        $this->assertGuideShows('<figure><img src="'.$this->assetUrl('opleidingen/bestanden/voorbeeld.jpg').'" alt="" loading="lazy" data-guide-media /></figure>');
    }

    public function test_images_open_in_a_lightbox(): void
    {
        $this->asAdmin()->get('/admin/docs/opleidingen')
            ->assertOk()
            ->assertSee('id="guide-lightbox"', false)
            ->assertSee("\$event.target.matches('img[data-guide-media]') && openLightbox(\$event.target)", false);
    }

    public function test_videos_render_as_muted_inline_videos_with_the_alt_text_as_fallback(): void
    {
        $this->assertGuideShows(
            '<figure><video src="'.$this->assetUrl('opleidingen/bestanden/voorbeeld.mp4').'" controls muted playsinline preload="metadata" data-guide-media>Rondleiding</video></figure>'
        );
    }

    public function test_an_image_within_text_stays_inline(): void
    {
        $this->assertGuideShows('<p>Tekst met een <img src="'.$this->assetUrl('opleidingen/bestanden/voorbeeld.gif').'" alt="inline beeld" loading="lazy" data-guide-media /> erin.</p>');
    }

    public function test_absolute_and_root_relative_urls_pass_through(): void
    {
        $this->assertGuideShows('<img src="https://example.com/scherm.png" alt="Extern beeld"');
        $this->assertGuideShows('<img src="/images/scherm.png" alt="Absoluut beeld"');
    }

    public function test_an_unresolved_relative_image_renders_its_alt_text(): void
    {
        $this->assertGuideShows('<p>Ontbrekend beeld</p>');
        $this->assertGuideShows('<p>Buiten de bron</p>');
        $this->assertGuideShows('<p>Verkeerd type</p>');
    }

    public function test_an_image_of_a_section_the_admin_may_not_see_renders_its_alt_text(): void
    {
        config()->set('chief-docs.hidden', ['verborgen']);

        $this->assertGuideShows('<p>Verborgen beeld</p>');

        $this->asAuthor()->get('/admin/docs/opleidingen')
            ->assertOk()
            ->assertSee('<p>Beeld voor admins</p>', false);
    }

    public function test_an_image_can_leave_its_folder_within_the_source(): void
    {
        $this->assertGuideShows('src="'.$this->assetUrl('start.png').'"');
    }

    public function test_a_guide_shows_the_project_replacement_of_a_shipped_asset(): void
    {
        $this->asAdmin()->get('/admin/docs/acme::schermen')
            ->assertOk()
            ->assertSee('src="'.route('chief.docs.asset', 'acme::schermen/knop.png').'?v='.filemtime($this->fixture('media/vendor/acme/schermen/knop.png')).'"', false);
    }

    private function assertGuideShows(string $html): void
    {
        $this->asAdmin()->get('/admin/docs/opleidingen')->assertOk()->assertSee($html, false);
    }

    private function assetUrl(string $assetId): string
    {
        return route('chief.docs.asset', $assetId).'?v='.filemtime($this->fixture('media/'.$assetId));
    }
}
