<?php

namespace Thinktomorrow\Chief\Fragments\Tests;

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Assert;
use Thinktomorrow\Chief\Fragments\App\Actions\AttachFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\CreateFragment;
use Thinktomorrow\Chief\Fragments\App\ContextActions\ContextApplication;
use Thinktomorrow\Chief\Fragments\App\ContextActions\CreateContext;
use Thinktomorrow\Chief\Fragments\App\Queries\GetFragments;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Managers\Register\Registry;

class FragmentTestHelpers
{
    public static function assertFragmentCount(string $contextId, int $count, ?string $fragmentId = null, ?string $parentFragmentId = null)
    {
        $fragments = app(FragmentRepository::class)->getByContext($contextId);

        if ($fragmentId) {
            $fragments = $fragments->filter(fn (Fragment $fragment) => $fragment->getFragmentModel()->id === $fragmentId);
        }

        if ($parentFragmentId) {
            $fragments = $fragments->filter(fn (Fragment $fragment) => $fragment->getFragmentModel()->pivot->parent_id === $parentFragmentId);
        }

        Assert::assertCount($count, $fragments);
    }

    public static function assertRenderedFragments(Model $owner, string $expected)
    {
        Assert::assertEquals($expected, app(GetFragments::class)->render($owner, []));
    }

    public static function firstFragment(string $contextId, ?callable $callback = null)
    {
        $fragments = static::getFragments($contextId);

        if (! $fragments->first()) {
            throw new \Exception('Test failed. Context doesn\'t have any fragments.');
        }

        if ($callback) {
            $callback($fragments->first());
        }

        return $fragments->first();
    }

    public static function getFragments(string $contextId)
    {
        return app(FragmentRepository::class)->getByContext($contextId);
    }

    public static function findOrCreateContext($owner, array $locales = []): ContextModel
    {
        $contexts = app(ContextRepository::class)->getByOwner($owner->modelReference());

        if ($contexts->isNotEmpty()) {
            return $contexts->first();
        }

        return static::createContext($owner, $locales);
    }

    public static function createContext(ContextOwner $owner, array $locales = [], ?string $title = null): ContextModel
    {
        $contextId = app(ContextApplication::class)->create(new CreateContext($owner->modelReference(), $locales, $title));

        return app(ContextRepository::class)->find($contextId);
    }

    public static function createFragment(string $fragmentClass, array $data = [], bool $register = true): Fragment
    {
        $fragmentKey = $fragmentClass::resourceKey();

        $scopedLocales = [];

        if ($register && ! app(Registry::class)->exists($fragmentKey)) {
            chiefRegister()->fragment($fragmentClass);
        }

        return (new $fragmentClass)->setFragmentModel(FragmentModel::find(app(CreateFragment::class)->handle($fragmentKey, $scopedLocales, $data)));
    }

    public static function attachFragment($contextId, $fragmentId, ?string $parentFragmentId = null, $order = 0, array $data = []): void
    {
        app(AttachFragment::class)->handle($contextId, $fragmentId, $parentFragmentId, $order, $data);
    }

    public static function createAndAttachFragment(string $fragmentClass, $contextId, ?string $parentFragmentId = null, $order = 0, array $data = [], bool $register = true): Fragment
    {
        $model = static::createFragment($fragmentClass, $data, $register);

        static::attachFragment($contextId, $model->getFragmentId(), $parentFragmentId, $order, []);

        return $model;
    }

    public static function createContextAndAttachFragment($owner, string $fragmentClass, ?string $parentFragmentId = null, $order = 0, array $data = [], bool $register = true): array
    {
        $context = static::findOrCreateContext($owner);

        $model = static::createAndAttachFragment($fragmentClass, $context->id, $parentFragmentId, $order, $data, $register);

        return [$context, $model];
    }

    public static function findFragment(string $fragmentId): Fragment
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
