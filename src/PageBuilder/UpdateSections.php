<?php

namespace Thinktomorrow\Chief\PageBuilder;

use Thinktomorrow\Chief\Common\FlatReferences\FlatReferenceCollection;
use Thinktomorrow\Chief\Common\FlatReferences\FlatReferenceFactory;
use Thinktomorrow\Chief\Modules\Application\CreateModule;
use Thinktomorrow\Chief\Modules\Application\UpdateModule;
use Thinktomorrow\Chief\Modules\TextModule;
use Thinktomorrow\Chief\Pages\Page;

class UpdateSections
{
    /** @var array */
    private $sorting;

    /** @var Page */
    private $page;

    /** @var array */
    private $relation_references;

    /** @var array */
    private $text_modules;

    private function __construct(Page $page, array $relation_references, array $text_modules, array $sorting)
    {
        $this->page = $page;
        $this->relation_references = $relation_references;
        $this->text_modules = $text_modules;
        $this->sorting = $sorting;
    }

    public static function forPage(Page $page, array $relation_references, array $text_modules, array $sorting)
    {
        return new static($page, $relation_references, $text_modules, $sorting);
    }

    public function updateModules()
    {
        // Remove existing relations expect the text ones
        $this->removeExistingModules();

        if (empty($this->relation_references)) {
            return $this;
        }

        $referred_instances = FlatReferenceCollection::fromFlatReferences($this->relation_references);

        foreach ($referred_instances as $instance) {
            $this->page->adoptChild($instance, ['sort' => 0]);
        }

        return $this;
    }

    private function removeExistingModules()
    {
        foreach ($this->page->children() as $instance) {
            if ($instance instanceof TextModule) {
                continue;
            }
            $this->page->rejectChild($instance);
        }
    }

    public function addTextModules()
    {
        if (!isset($this->text_modules['new']) || empty($this->text_modules['new'])) {
            return $this;
        }

        foreach ($this->text_modules['new'] as $text_module) {

            // Create page specific text module
            $module = app(CreateModule::class)->handle(
                (new TextModule)->collectionDetails()->key,
                $text_module['slug'],
                $this->page->id
            );

            // Connect to page - sorting will be set later on...
            $this->page->adoptChild($module, ['sort' => 0]);

            // Add content
            app(UpdateModule::class)->handle($module->id, $module->slug, $text_module['trans'], [], []);

            // Change slug representation in sorting to proper flat reference
            $index = (false !== $key = array_search($module->slug, $this->sorting)) ? $key : null;
            $this->sorting[$index] = $module->flatReference()->get();
        }

        return $this;
    }

    public function updateTextModules()
    {
        if (!isset($this->text_modules['replace']) || empty($this->text_modules['replace'])) {
            return $this;
        }

        foreach ($this->text_modules['replace'] as $text_module) {
            if (! $module = FlatReferenceFactory::fromString($text_module['id'])->instance()) {
                continue;
            }

            // Do not update if content of text is completely empty. We will remove this module instead
            if ($this->isTextCompletelyEmpty($text_module['trans'])) {
                $this->removeTextModule($module);
                continue;
            }

            // Replace content
            app(UpdateModule::class)->handle($module->id, $module->slug, $text_module['trans'], [], []);
        }

        return $this;
    }

    private function removeTextModule(TextModule $module)
    {
        $this->page->rejectChild($module);

        // In case of text module, we also delete the module itself
        $module->delete();
    }

    public function sort()
    {
        $children = $this->page->children();

        foreach ($this->sorting as $sorting => $reference) {

            // Reference can be null in case that the module has been removed (empty selection). This will avoid
            // in case of duplicate module references that the removed module will be used for the sorting instead.
            if (!$reference) {
                continue;
            }

            $child = $children->first(function ($c) use ($reference) {
                return $c->flatReference()->get() == $reference;
            });

            if (!$child) {
                continue;
            }

            $this->page->sortChild($child, $sorting);
        }

        return $this;
    }

    /**
     * Do we consider the translation payload to be 'empty'. This means
     * that each line of the translation only contains spaces or empty tags.
     *
     * @param $trans
     * @return bool
     */
    private function isTextCompletelyEmpty($trans): bool
    {
        $is_completely_empty = true;

        foreach ($trans as $locale => $lines) {
            foreach ($lines as $key => $line) {
                $stripped_line = $this->stripTagsBlacklist($line, ['p', 'br']);
                $stripped_line = trim($stripped_line);

                if ($stripped_line) {
                    $is_completely_empty = false;
                    break;
                }
            }
        }

        return $is_completely_empty;
    }

    /**
     * Pass a list of not allowed tags as they will be stripped out from the value.
     * e.g. ['p', 'br' ]
     *
     * @param $value
     * @param array $blacklist
     * @return mixed
     */
    private function stripTagsBlacklist($value, $blacklist = [])
    {
        foreach ($blacklist as $tag) {
            $value = preg_replace('/<\/?' . $tag . '(.|\s)*?>/', '', $value);
        }

        return $value;
    }
}
