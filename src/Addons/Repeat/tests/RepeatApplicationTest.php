<?php

namespace Thinktomorrow\Chief\Addons\Repeat;

use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;
use Thinktomorrow\Chief\Addons\Repeat\tests\PageStub;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Managers\Presets\FragmentManager;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class RepeatApplicationTest extends ChiefTestCase
{
    private $manager;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        chiefRegister()->model(PageStub::class, PageManager::class);

        $this->manager = $this->manager(PageStub::managedModelKey());
    }

    /** @test */
    public function it_can_be_stored()
    {
        $this->asAdmin()->post($this->manager->route('store'), [
            'title' => 'new title',
            'authors' => $this->authorsValues(),
        ]);

        $this->assertEquals($this->authorsValues(), PageStub::first()->authors);
    }

    /** @test */
    public function it_can_be_updated()
    {
        $this->asAdmin()->put($this->manager->route('update', PageStub::create()), [
            'authors' => $this->authorsValues(),
        ]);

        $this->assertEquals($this->authorsValues(), PageStub::first()->authors);
    }

    /** @test */
    public function it_can_save_a_nested_repeatfield()
    {

    }

    /**
     * @return array[]
     */
    private function authorsValues(): array
    {
        return [
            [
                'title' => [
                    'nl' => 'first nl title',
                    'en' => 'first en title',
                ],
                'name'  => 'first Jeroen Brouwers',
            ],
            [
                'title' => [
                    'nl' => 'second nl title',
                    'en' => 'second en title',
                ],
                'name'  => 'second Jeroen Brouwers',
            ]
        ];
    }

}
