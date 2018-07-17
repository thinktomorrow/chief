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

        if(empty($this->relation_references)) return $this;

        $referred_instances = FlatReferenceCollection::fromFlatReferences($this->relation_references);

        foreach($referred_instances as $instance) {
            $this->page->adoptChild($instance, ['sort' => 0]);
        }

        return $this;
    }

    private function removeExistingModules()
    {
        foreach($this->page->children() as $instance) {
            if($instance instanceof TextModule) continue;
            $this->page->rejectChild($instance);
        }
    }

    public function addTextModules()
    {
        if(!isset($this->text_modules['new']) || empty($this->text_modules['new'])) return $this;

        foreach($this->text_modules['new'] as $text_module) {
            // Create text module
            $module = app(CreateModule::class)->handle((new TextModule)->collectionDetails()->key, $text_module['slug']);

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

    public function replaceTextModules()
    {
        if(!isset($this->text_modules['replace']) || empty($this->text_modules['replace'])) return $this;

        foreach($this->text_modules['replace'] as $text_module) {

            $flatReference = FlatReferenceFactory::fromString($text_module['id']);
            $module = $this->page->children()->firstWhere('id', $flatReference->id());

            if(!$module) continue;

            // Replace content
            app(UpdateModule::class)->handle($module->id, $module->slug, $text_module['trans'], [], []);
        }

        return $this;
    }

    public function removeTextModules()
    {
        if(!isset($this->text_modules['remove']) || empty($this->text_modules['remove'])) return $this;

        foreach($this->text_modules['remove'] as $text_module_id) {

            $module = $this->page->children()->firstWhere('id', $text_module_id);

            if(!$module) continue;

            $this->page->rejectChild($module);
        }

        return $this;
    }

    public function sort()
    {
        $children = $this->page->children();

        foreach($this->sorting as $sorting => $reference) {

            $child = $children->first(function($c) use($reference){
                return $c->flatReference()->get() == $reference;
            });

            if(!$child) continue;

            $this->page->sortChild($child, $sorting);
        }

        return $this;
    }
}
