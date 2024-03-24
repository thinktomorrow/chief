<?php

namespace Thinktomorrow\Chief\Fragments\Tests;

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Assert;
use Thinktomorrow\Chief\Fragments\App\Actions\AttachFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\CreateFragment;
use Thinktomorrow\Chief\Fragments\App\Queries\FragmentsRenderer;
use Thinktomorrow\Chief\Fragments\Domain\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Domain\Models\ContextRepository;
use Thinktomorrow\Chief\Fragments\Domain\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Domain\Models\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\ManagedModels\Presets\Fragment;
use Thinktomorrow\Chief\Managers\Presets\FragmentManager;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Hero;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class FragmentTestAssist
{
    public static function assertFragmentCount(string $contextId, int $count)
    {
        Assert::assertCount($count, app(FragmentRepository::class)->getByContext($contextId));
    }

    public static function assertRenderedFragments(Model $owner, string $expected)
    {
        Assert::assertEquals($expected, app(FragmentsRenderer::class)->render($owner, []));
    }

    public static function firstFragment(string $contextId, callable $callback = null)
    {
        $fragments = app(FragmentRepository::class)->getByContext($contextId);

        if (! $fragments->first()) {
            throw new \Exception('Test failed. Context doesn\'t have any fragments.');
        }

        if ($callback) {
            $callback($fragments->first());
        }

        return $fragments->first();
    }


    public static function findOrCreateContext($owner, array $locales = []): ContextModel
    {
        $contexts = app(ContextRepository::class)->getByOwner($owner);

        if($contexts->isNotEmpty()) {
            return $contexts->first();
        }

        return static::createContext($owner, $locales);
    }

    public static function createContext($owner, array $locales = []): ContextModel
    {
        return app(ContextRepository::class)->create($owner, $locales);
    }

    public static function createFragment(string $fragmentClass, array $data = [], bool $register = true): Fragmentable
    {
        $fragmentKey = $fragmentClass::resourceKey();

        if($register && ! app(Registry::class)->exists($fragmentKey)) {
            chiefRegister()->fragment($fragmentClass);
        }

        return (new $fragmentClass)->setFragmentModel(FragmentModel::find(app(CreateFragment::class)->handle($fragmentKey, $data)));
    }

    public static function attachFragment($contextId, $fragmentId, $order = 0, array $data = []): void
    {
        app(AttachFragment::class)->handle($contextId, $fragmentId, $order, $data);
    }

    public static function createAndAttachFragment(string $fragmentClass, $contextId, $order = 0, array $data = [], bool $register = true): Fragmentable
    {
        $model = static::createFragment($fragmentClass, $data, $register);

        static::attachFragment($contextId, $model->getFragmentId(), $order, []);

        return $model;
    }

    public static function createContextAndAttachFragment($owner, string $fragmentClass, $order = 0, array $data = [], bool $register = true): array
    {
        $context = static::findOrCreateContext($owner);

        $model = static::createAndAttachFragment($fragmentClass, $context->id, $order, $data, $register);

        return [$context, $model];
    }

    public static function findFragment(string $fragmentId): Fragmentable
    {
        return app(FragmentRepository::class)->find($fragmentId);
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