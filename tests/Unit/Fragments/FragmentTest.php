<?php

namespace Thinktomorrow\Chief\Tests\Unit\Fragments  ;

use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Tests\TestCase;

class FragmentTest extends TestCase
{
    private $model;

    protected function setUp(): void
    {
        parent::setUp();

        ModelStub::migrateUp();

        // test it out
        $this->model = new ModelStub();
        $this->model->save(); // Because we need an id for our db relation.
    }

    /** @test */
    public function an_eloquent_model_can_have_one_or_more_fragments()
    {
        $this->model->saveFragment(Fragment::fromNew('fragment-key', ['title' => 'title-one']), 1);
        $this->model->saveFragment(Fragment::fromNew('fragment-key', ['title' => 'title-two']), 2);

        $this->assertCount(2, $this->model->getFragments('fragment-key'));

        $fragment = $this->model->getFragments('fragment-key')[0];
        $this->assertInstanceOf(Fragment::class, $fragment);
        $this->assertEquals('title-one', $fragment->getValue('title'));
    }

    /** @test */
    public function fragments_are_saved_per_type()
    {
        $this->model->saveFragment(Fragment::fromNew('fragment-key', ['title' => 'title-one']), 1);
        $this->model->saveFragment(Fragment::fromNew('fragment-key', ['title' => 'title-two']), 2);
        $this->model->saveFragment(Fragment::fromNew('fragment-other-key', ['title' => 'title-three']), 3);

        $this->assertCount(2, $this->model->getFragments('fragment-key'));
        $this->assertEquals('title-one', $this->model->getFragments('fragment-key')[0]->getValue('title'));

        $this->assertCount(1, $this->model->getFragments('fragment-other-key'));
        $this->assertEquals('title-three', $this->model->getFragments('fragment-other-key')[0]->getValue('title'));
    }

    /** @test */
    public function fragments_are_ordered()
    {
        $this->model->saveFragment(Fragment::fromNew('fragment-key', ['title' => 'title-one']), 2);
        $this->model->saveFragment(Fragment::fromNew('fragment-key', ['title' => 'title-two']), 1);

        $this->assertEquals('title-two', $this->model->getFragments('fragment-key')[0]->getValue('title'));
    }
}
