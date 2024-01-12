<?php

namespace Thinktomorrow\Chief\Fragments\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use PHPUnit\Framework\Assert;
use Thinktomorrow\Chief\Fragments\App\Actions\AttachFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\CreateFragment;
use Thinktomorrow\Chief\Fragments\App\Queries\FragmentsRenderer;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Fragments\Domain\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Domain\Models\ContextRepository;
use Thinktomorrow\Chief\Fragments\Domain\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Domain\Models\FragmentRepository;
use Thinktomorrow\Chief\Managers\Presets\FragmentManager;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Hero;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class FragmentTestAssist
{
    public static function assertFragmentCount(Model $owner, string $locale, int $count)
    {
        Assert::assertCount($count, app(FragmentRepository::class)->getByOwner($owner, $locale));
    }

    public static function assertRenderedFragments(Model $owner, string $expected)
    {
        Assert::assertEquals($expected, app(FragmentsRenderer::class)->render($owner, []));
    }

    public static function firstFragment(Model $owner, string $locale, callable $callback = null)
    {
        $fragments = app(FragmentRepository::class)->getByOwner($owner, $locale);

        if (! $fragments->first()) {
            throw new \Exception('Test failed. Owner doesn\'t own any fragments.');
        }

        if ($callback) {
            $callback($fragments->first());
        }

        return $fragments->first();
    }


    public static function findOrCreateContext($owner, string $locale = 'nl'): ContextModel
    {
        return app(ContextRepository::class)->findOrCreateByOwner($owner, $locale);
    }

    public static function createFragment(string $fragmentClass, array $data = [], bool $register = true): Fragmentable
    {
        $fragmentKey = $fragmentClass::resourceKey();

        if($register && !app(Registry::class)->exists($fragmentKey)) {
            chiefRegister()->fragment($fragmentClass);
        }

        return (new $fragmentClass)->setFragmentModel(FragmentModel::find(app(CreateFragment::class)->handle($fragmentKey, $data)));
    }

    public static function createAndAttachFragment(string $fragmentClass, $contextId, $order = 0, array $data = [], bool $register = true): Fragmentable
    {
        $model = static::createFragment($fragmentClass, $data, $register);

        app(AttachFragment::class)->handle($contextId, $model->fragmentModel()->id, $order, []);

        return $model;
    }

    public static function createContextAndAttachFragment($owner, string $fragmentClass, string $locale = 'nl', $order = 0, array $data = [], bool $register = true): array
    {
        $context = static::findOrCreateContext($owner, $locale);

        $model = static::createFragment($fragmentClass, $data, $register);

        app(AttachFragment::class)->handle($context->id, $model->fragmentModel()->id, $order, []);

        return [$context, $model];
    }

//
//
//    public static function registerAndCreateFragment(FragmentsOwner $owner, $order = 0, $withSetup = true, array $values = [], string $locale = 'nl'): SnippetStub
//    {
//        if ($withSetup) {
//            chiefRegister()->fragment(SnippetStub::class);
//        }
//
//        $context = static::findOrCreateContext($owner, $locale);
//
//        return static::createAndAttachFragment(SnippetStub::resourceKey(), $context->id, $order, $values);
//    }
//
//    public static function setUpAndCreateSnippet(FragmentsOwner $owner, $order = 0, $withSetup = true, array $values = [], string $locale = 'nl'): SnippetStub
//    {
//        if ($withSetup) {
//            chiefRegister()->fragment(SnippetStub::class);
//        }
//
//        $context = static::findOrCreateContext($owner, $locale);
//
//        return static::createAndAttachFragment(SnippetStub::resourceKey(), $context->id, $order, $values);
//    }
//
//    public static function setUpAndCreateHero(FragmentsOwner $owner, $order = 0, $withSetup = true, string $locale = 'nl'): Hero
//    {
//        if ($withSetup) {
//            chiefRegister()->fragment(Hero::class);
//        }
//
//        $context = static::findOrCreateContext($owner, $locale);
//
//        return static::createAndAttachFragment(Hero::resourceKey(), $context->id, $order);
//    }


//    public static function setUpAndCreateQuote(FragmentsOwner $owner, array $values = [], $order = 0, $withSetup = true, string $locale = 'nl'): Quote
//    {
//        if ($withSetup) {
//            chiefRegister()->resource(Quote::class, FragmentManager::class);
//        }
//
//        $context = app(ContextRepository::class)->findByOwner($owner, $locale) ?: app(ContextRepository::class)->createForOwner($owner, $locale);
//
//        return static::createAndAttachFragment(Quote::resourceKey(), $context->id, $order, $values);
//    }
}
