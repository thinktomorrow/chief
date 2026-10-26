<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Tests\App\Queries;

use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Fields\Textarea;
use Thinktomorrow\Chief\Fragments\App\Queries\GetMoveDestinations;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Fragments\Tests\Stubs\MoveContainerStub;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class GetMoveDestinationsTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        chiefRegister()->fragment(MoveContainerStub::class);
    }

    protected function tearDown(): void
    {
        SnippetStub::resetFieldsDefinition();

        parent::tearDown();
    }

    public function test_it_prefers_a_title_in_the_selected_locale_over_earlier_text(): void
    {
        SnippetStub::setFieldsDefinition(fn () => [
            Textarea::make('body'),
            Text::make('title_trans')->locales(),
        ]);

        $context = FragmentTestHelpers::createContext($this->setUpAndCreateArticle());
        $moving = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id);
        $candidate = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id, null, 1, [
            'body' => 'Some earlier body text',
            'title_trans' => ['nl' => 'Nederlandse titel', 'en' => 'English title'],
        ]);

        $dutch = collect(app(GetMoveDestinations::class)->handle($context->id, $moving->getFragmentId(), 'nl'))
            ->firstWhere('id', $candidate->getFragmentId());
        $english = collect(app(GetMoveDestinations::class)->handle($context->id, $moving->getFragmentId(), 'en'))
            ->firstWhere('id', $candidate->getFragmentId());

        $this->assertSame('snippet stub — Nederlandse titel', $dutch['label']);
        $this->assertSame('snippet stub — English title', $english['label']);
    }

    public function test_it_uses_the_first_text_field_when_there_is_no_title(): void
    {
        SnippetStub::setFieldsDefinition(fn () => [
            Text::make('heading'),
            Textarea::make('body'),
            Text::make('intro'),
        ]);

        $context = FragmentTestHelpers::createContext($this->setUpAndCreateArticle());
        $moving = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id);
        $candidate = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id, null, 1, [
            'body' => '<p>Hallo <strong>wereld</strong>!</p>  Nog een zin.',
            'intro' => 'Later text',
        ]);

        $destination = collect(app(GetMoveDestinations::class)->handle($context->id, $moving->getFragmentId(), 'nl'))
            ->firstWhere('id', $candidate->getFragmentId());

        $this->assertSame('snippet stub — Hallo wereld! Nog een zin.', $destination['label']);
    }

    public function test_it_uses_a_child_description_for_a_container_without_own_text(): void
    {
        $context = FragmentTestHelpers::createContext($this->setUpAndCreateArticle());
        $moving = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id);
        $container = FragmentTestHelpers::createAndAttachFragment(MoveContainerStub::class, $context->id, null, 1);
        $nested = FragmentTestHelpers::createAndAttachFragment(MoveContainerStub::class, $context->id, $container->getFragmentId());
        FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id, $nested->getFragmentId(), 0, [
            'title' => 'Eerste kindtekst',
        ]);
        FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id, $container->getFragmentId(), 1, [
            'title' => 'Tweede kindtekst',
        ]);

        $destinations = collect(app(GetMoveDestinations::class)->handle($context->id, $moving->getFragmentId(), 'nl'))
            ->keyBy('id');

        $this->assertSame('move container stub — Eerste kindtekst', $destinations[$container->getFragmentId()]['label']);
        $this->assertSame('move container stub — Eerste kindtekst', $destinations[$nested->getFragmentId()]['label']);
    }

    public function test_it_keeps_the_type_label_when_no_text_is_available(): void
    {
        $context = FragmentTestHelpers::createContext($this->setUpAndCreateArticle());
        $moving = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id);
        $empty = FragmentTestHelpers::createAndAttachFragment(MoveContainerStub::class, $context->id, null, 1);

        $destination = collect(app(GetMoveDestinations::class)->handle($context->id, $moving->getFragmentId(), 'nl'))
            ->firstWhere('id', $empty->getFragmentId());

        $this->assertSame('move container stub', $destination['label']);
    }

    public function test_it_shortens_long_text_descriptions(): void
    {
        SnippetStub::setFieldsDefinition(fn () => [Textarea::make('body')]);

        $context = FragmentTestHelpers::createContext($this->setUpAndCreateArticle());
        $moving = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id);
        $candidate = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id, null, 1, [
            'body' => str_repeat('a', 100),
        ]);

        $destination = collect(app(GetMoveDestinations::class)->handle($context->id, $moving->getFragmentId(), 'nl'))
            ->firstWhere('id', $candidate->getFragmentId());

        $this->assertSame('snippet stub — '.str_repeat('a', 72).'...', $destination['label']);
    }

    public function test_it_marks_the_current_root_location_even_if_the_type_is_no_longer_allowed_there(): void
    {
        $context = FragmentTestHelpers::createContext($this->setUpAndCreateArticle());
        $moving = FragmentTestHelpers::createAndAttachFragment(MoveContainerStub::class, $context->id);

        $root = collect(app(GetMoveDestinations::class)->handle($context->id, $moving->getFragmentId(), 'nl'))
            ->firstWhere('id', 'root');

        $this->assertSame('current-location', $root['reason']);
    }

    public function test_it_marks_the_current_parent_even_if_it_no_longer_allows_the_fragment_type(): void
    {
        $context = FragmentTestHelpers::createContext($this->setUpAndCreateArticle());
        $parent = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id);
        $moving = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id, $parent->getFragmentId());

        $destinations = collect(app(GetMoveDestinations::class)->handle($context->id, $moving->getFragmentId(), 'nl'))
            ->keyBy('id');

        $this->assertSame('current-location', $destinations[$parent->getFragmentId()]['reason']);
        $this->assertNull($destinations['root']['reason']);
    }
}
