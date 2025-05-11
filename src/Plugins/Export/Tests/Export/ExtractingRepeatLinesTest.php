<?php

namespace Thinktomorrow\Chief\Plugins\Export\Tests\Export;

use Thinktomorrow\Chief\Forms\Fields\Repeat;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Layouts\Grid;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Plugins\Export\Export\Lines\ComposeFieldLines;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

class ExtractingRepeatLinesTest extends ChiefTestCase
{
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
    }

    public function test_it_can_export_repeat_fields()
    {
        $this->disableExceptionHandling();
        $article = $this->setUpAndCreateArticle(
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
        $resource = app(Registry::class)->resource('article_page');

        $composeLines = app(ComposeFieldLines::class)
            ->ignoreEmptyValues()
            ->compose($resource, $article, ['nl', 'en']);

        $this->assertCount(6, $composeLines->getLines());

        // Validate lines
        $firstLine = $composeLines->getLines()->first();
        $this->assertEquals('', $firstLine->getValue());
        $this->assertEquals('CTA links > url', $firstLine->getFIeldLabel());
        $this->assertEquals('/prijs-aanvragen', $firstLine->getValue('nl'));
        $this->assertEquals('/demande-prix', $firstLine->getValue('en'));

        $secondLine = $composeLines->getLines()[1];
        $this->assertEquals('', $secondLine->getValue());
        $this->assertEquals('CTA links > label', $secondLine->getFIeldLabel());
        $this->assertEquals('Vraag offerte aan', $secondLine->getValue('nl'));
        $this->assertEquals('Demander un devis', $secondLine->getValue('en'));

        $thirdLine = $composeLines->getLines()[2];
        $this->assertEquals('', $thirdLine->getValue());
        $this->assertEquals('CTA links > url', $thirdLine->getFIeldLabel());
        $this->assertEquals('/waterhardheid-resultaat', $thirdLine->getValue('nl'));
        $this->assertEquals('/durete-eau', $thirdLine->getValue('en'));

        $fourthLine = $composeLines->getLines()[3];
        $this->assertEquals('', $fourthLine->getValue());
        $this->assertEquals('CTA links > label', $fourthLine->getFIeldLabel());
        $this->assertEquals('Ontdek jouw waterhardheid', $fourthLine->getValue('nl'));
        $this->assertEquals('Découvrez la dureté de votre eau', $fourthLine->getValue('en'));

        $fifthLine = $composeLines->getLines()[4];
        $this->assertEquals('', $fifthLine->getValue());
        $this->assertEquals('CTA links > url', $fifthLine->getFIeldLabel());
        $this->assertEquals(null, $fifthLine->getValue('nl'));
        $this->assertEquals(null, $fifthLine->getValue('en'));

        $sixthLine = $composeLines->getLines()[5];
        $this->assertEquals('', $sixthLine->getValue());
        $this->assertEquals('CTA links > label', $sixthLine->getFIeldLabel());
        $this->assertNull($sixthLine->getValue('nl'));
        $this->assertEquals('Troisième lien', $sixthLine->getValue('en'));
    }
}
