<?php

namespace Thinktomorrow\Chief\PageBuilder;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Common\FlatReferences\FlatReferenceCollection;
use Thinktomorrow\Chief\Common\FlatReferences\FlatReferenceFactory;
use Thinktomorrow\Chief\Modules\Application\CreateModule;
use Thinktomorrow\Chief\Modules\Application\UpdateModule;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Modules\TextModule;
use Thinktomorrow\Chief\Pages\Page;

class UpdateSections
{
    /** @var array */
    private $order;

    /** @var Page */
    private $page;

    /** @var array */
    private $relation_references;

    /** @var array */
    private $text_modules;

    private function __construct(Page $page, array $relation_references, array $text_modules, array $order)
    {
        $this->page = $page;
        $this->relation_references = $relation_references;
        $this->text_modules = $text_modules;
        $this->order = $order;
    }

    public static function forPage(Page $page, array $relation_references, array $text_modules, array $order)
    {
        return new static($page, $relation_references, $text_modules, $order);
        // Add newly created text modules on the fly

        // Replace value of existing text modules

        // Remove any text modules

        // Attach existing modules

        // Detach removed modules

        // Sort all modules, now that we have all ids...

        // When no content has changed, we still like to sort
    }

    public function addModules()
    {
        if(!isset($this->relation_references['new']) || empty($this->relation_references['new'])) return $this;

        $referred_instances = FlatReferenceCollection::fromFlatReferences($this->relation_references['new']);

        foreach($referred_instances as $instance) {
            $this->page->adoptChild($instance, ['sort' => 0]);
        }

        return $this;
    }

    public function removeModules()
    {
        if(!isset($this->relation_references['remove']) || empty($this->relation_references['remove'])) return $this;

        $referred_instances = FlatReferenceCollection::fromFlatReferences($this->relation_references['remove']);

        foreach($referred_instances as $instance) {
            $this->page->rejectChild($instance);
        }

        return $this;
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
        }

        return $this;
    }

    public function replaceTextModules()
    {
        if(!isset($this->text_modules['replace']) || empty($this->text_modules['replace'])) return $this;

        foreach($this->text_modules['replace'] as $text_module) {

            $module = $this->page->children()->firstWhere('id', $text_module['id']);

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
}
