<?php

namespace Thinktomorrow\Chief\Plugins\Export\Tests\Export;

use Thinktomorrow\Chief\Forms\Fields\Repeat;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Layouts\Grid;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Plugins\Export\Export\Lines\ComposeFieldLines;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

class ExtractingRepeatLinesTest extends ChiefTestCase
{
    private ArticlePage $article;

    private \Thinktomorrow\Chief\Resource\PageResource $resource;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePageResource::setFieldsDefinition(function () {
            return [
                Repeat::make('hero_links')
                    ->label('CTA links')
                    ->locales()
                    ->items([
                        Grid::make('links_grid')->columns(2)->items([
                            Text::make('label')
                                ->label('Label')
                                ->placeholder('Contacteer ons, lees meer...'),
                            Text::make('url')
                                ->label('URL')
                                ->placeholder('/about-us/our-team, #contact...'),
                        ]),
                    ]),
            ];
        });

        $this->article = $this->setUpAndCreateArticle(
            [
                'title' => [
                    'en' => 'Home',
                    'nl' => 'Home',
                ],
                'hero_links' => [
                    'en' => [
                        ['url' => '/demande-prix', 'label' => 'Demander un devis'],
                        ['url' => '/durete-eau', 'label' => 'Découvrez la dureté de votre eau'],
                        ['url' => null, 'label' => 'Troisième lien'],
                    ],
                    'nl' => [
                        ['url' => '/prijs-aanvragen', 'label' => 'Vraag offerte aan'],
                        ['url' => '/waterhardheid-resultaat', 'label' => 'Ontdek jouw waterhardheid'],
                    ],
                ],
            ]
        );

        $this->resource = app(Registry::class)->resource('article_page');
    }

    public function test_it_can_export_repeat_field_with_field_key_reference()
    {
        $composeLines = app(ComposeFieldLines::class)
            ->ignoreEmptyValues()
            ->compose($this->resource, $this->article, ['nl', 'en']);

        $this->assertCount(6, $composeLines->getLines());

        // Validate lines
        $firstLine = $composeLines->getLines()->first();
        $this->assertEquals('', $firstLine->getValue());

        $this->assertStringEndsWith('|hero_links.0.url', decrypt($firstLine->getReference()));
    }

    public function test_it_can_export_repeat_fields()
    {
        $composeLines = app(ComposeFieldLines::class)
            ->ignoreEmptyValues()
            ->compose($this->resource, $this->article, ['nl', 'en']);

        $this->assertCount(6, $composeLines->getLines());

        // Validate lines
        $firstLine = $composeLines->getLines()->first();
        $this->assertEquals('', $firstLine->getValue());

        $this->assertEquals('CTA links > url', $firstLine->getFieldLabel());
        $this->assertEquals('/prijs-aanvragen', $firstLine->getValue('nl'));
        $this->assertEquals('/demande-prix', $firstLine->getValue('en'));

        $secondLine = $composeLines->getLines()[1];
        $this->assertEquals('', $secondLine->getValue());
        $this->assertEquals('CTA links > label', $secondLine->getFieldLabel());
        $this->assertEquals('Vraag offerte aan', $secondLine->getValue('nl'));
        $this->assertEquals('Demander un devis', $secondLine->getValue('en'));

        $thirdLine = $composeLines->getLines()[2];
        $this->assertEquals('', $thirdLine->getValue());
        $this->assertEquals('CTA links > url', $thirdLine->getFieldLabel());
        $this->assertEquals('/waterhardheid-resultaat', $thirdLine->getValue('nl'));
        $this->assertEquals('/durete-eau', $thirdLine->getValue('en'));

        $fourthLine = $composeLines->getLines()[3];
        $this->assertEquals('', $fourthLine->getValue());
        $this->assertEquals('CTA links > label', $fourthLine->getFieldLabel());
        $this->assertEquals('Ontdek jouw waterhardheid', $fourthLine->getValue('nl'));
        $this->assertEquals('Découvrez la dureté de votre eau', $fourthLine->getValue('en'));

        $fifthLine = $composeLines->getLines()[4];
        $this->assertEquals('', $fifthLine->getValue());
        $this->assertEquals('CTA links > url', $fifthLine->getFieldLabel());
        $this->assertEquals(null, $fifthLine->getValue('nl'));
        $this->assertEquals(null, $fifthLine->getValue('en'));

        $sixthLine = $composeLines->getLines()[5];
        $this->assertEquals('', $sixthLine->getValue());
        $this->assertEquals('CTA links > label', $sixthLine->getFieldLabel());
        $this->assertNull($sixthLine->getValue('nl'));
        $this->assertEquals('Troisième lien', $sixthLine->getValue('en'));
    }
}
