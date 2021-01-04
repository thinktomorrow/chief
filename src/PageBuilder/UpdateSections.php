<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\PageBuilder;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Sets\SetReference;
use Thinktomorrow\Chief\Modules\TextModule;
use Thinktomorrow\Chief\Relations\Relation;
use Thinktomorrow\Chief\Relations\ActsAsParent;
use Thinktomorrow\Chief\Modules\PagetitleModule;
use Thinktomorrow\Chief\Modules\Application\CreateModule;
use Thinktomorrow\Chief\Modules\Application\UpdateModule;
use Thinktomorrow\Chief\FlatReferences\FlatReferenceFactory;
use Thinktomorrow\Chief\FlatReferences\FlatReferenceCollection;
use Thinktomorrow\Chief\Sets\StoredSetReference;

class UpdateSections
{
    /** @var ActsAsParent */
    private $model;

    /** @var array */
    private $relation_references;

    /** @var array */
    private $text_modules;

    /**@var array */
    private $set_refs;

    /** @var array */
    private $sorting;

    final private function __construct(ActsAsParent $model, array $relation_references, array $text_modules, array $set_refs, array $sorting)
    {
        $this->model = $model;
        $this->relation_references = $relation_references;
        $this->text_modules = $text_modules;
        $this->set_refs = $set_refs;
        $this->sorting = $sorting;
    }

    public static function forModel(ActsAsParent $model, array $relation_references, array $text_modules, array $set_refs, array $sorting)
    {
        return new static($model, $relation_references, $text_modules, $set_refs, $sorting);
    }

    public function updateModules()
    {
        $referred_instances = FlatReferenceCollection::fromFlatReferences($this->relation_references);

        $this->removeModules($referred_instances);

        foreach ($referred_instances as $i => $instance) {
            if(!$relation = Relation::find($this->model->getMorphClass(), $this->model->id, $instance->getMorphClass(), $instance->id)) {
                $this->model->adoptChild($instance, ['sort' => $i]);
            }
        }

        return $this;
    }

    public function updateSets()
    {
        $stored_set_refs = collect($this->set_refs)->reject(function($ref){
            return !$ref;
        })->map(function($flat_set_ref){
            return $this->findOrCreateStoredSetReference($flat_set_ref);
        });

        $this->removeSets($stored_set_refs);

        foreach ($stored_set_refs as $i => $stored_set_ref) {
            if(!$relation = Relation::find($this->model->getMorphClass(), $this->model->id, $stored_set_ref->getMorphClass(), $stored_set_ref->id)) {
                $this->model->adoptChild($stored_set_ref, ['sort' => $i]);
            }
        }

        return $this;
    }

    /**
     * Get all module children and only those not passed so we know to delete these ones
     * @param Collection $referred_instances
     */
    private function removeModules(Collection $referred_instances): void
    {
        $this->model->children()->filter(function ($instance) {
            return (!$instance instanceof StoredSetReference && !$instance instanceof TextModule && !$instance instanceof PagetitleModule);
        })->reject(function ($instance) use ($referred_instances) {
            // If this model still exists in the update request, we'll reject it from the removed list.
            return $referred_instances->filter(function ($referred_instance) use ($instance) {
                return (get_class($referred_instance) == get_class($instance) && $referred_instance->id == $instance->id);
            })->isNotEmpty();
        })->each(function ($instance) {
            $this->model->rejectChild($instance);
        });
    }

    /**
     * Get all module children and only those not passed so we know to delete these ones
     * @param Collection $referred_instances
     */
    private function removeSets(Collection $referred_instances): void
    {
        $this->model->children()->filter(function ($instance) {
            return ($instance instanceof StoredSetReference);
        })->reject(function ($instance) use ($referred_instances) {
            // If this model still exists in the update request, we'll reject it from the removed list.
            return $referred_instances->filter(function ($referred_instance) use ($instance) {
                return (get_class($referred_instance) == get_class($instance) && $referred_instance->id == $instance->id);
            })->isNotEmpty();
        })->each(function ($instance) {
            $this->model->rejectChild($instance);
        });
    }

//    private function removeExistingModules()
//    {
//        foreach ($this->model->children() as $instance) {
//            if ($instance instanceof StoredSetReference || $instance instanceof TextModule || $instance instanceof PagetitleModule) {
//                continue;
//            }
//            $this->model->rejectChild($instance);
//        }
//    }
//
//    private function removeExistingSets()
//    {
//        foreach ($this->model->children() as $instance) {
//            if (!$instance instanceof StoredSetReference) {
//                continue;
//            }
//            $this->model->rejectChild($instance);
//        }
//    }

    public function addTextModules()
    {
        if (!isset($this->text_modules['new']) || empty($this->text_modules['new'])) {
            return $this;
        }

        foreach ($this->text_modules['new'] as $text_module) {

            // Create pagetitle text module
            if (isset($text_module['type']) && $text_module['type'] == 'pagetitle') {
                $module = app(CreateModule::class)->handle(
                    (new PagetitleModule())->morphKey(),
                    $text_module['slug'],
                    $this->model->getMorphClass(),
                    $this->model->id
                );
            } // Create page specific text module
            else {
                $module = app(CreateModule::class)->handle(
                    (new TextModule())->morphKey(),
                    $text_module['slug'],
                    $this->model->getMorphClass(),
                    $this->model->id
                );
            }

            // Connect to page - sorting will be set later on...
            $this->model->adoptChild($module, ['sort' => 0]);

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
            if (!$module = FlatReferenceFactory::fromString($text_module['id'])->instance()) {
                continue;
            }

            // Do not update if content of text is completely empty. We will remove this module instead
            if ($this->isTextCompletelyEmpty($text_module['trans'])) {
                $this->removeTextualModule($module);
                continue;
            }

            foreach ($text_module['trans'] as $locale => $content) {
                $text_module['trans'][$locale]['content'] = $content['content'];
            }

            // Replace content
            app(UpdateModule::class)->handle($module->id, $module->slug, $text_module['trans'], [], []);
        }

        return $this;
    }

    private function removeTextualModule($module)
    {
        if (!$module instanceof TextModule && !$module instanceof PagetitleModule) {
            throw new \Exception('Invalid request to remove non textual module');
        }

        $this->model->rejectChild($module);

        // In case of a textual module, we also delete the module itself
        $module->delete();
    }

    public function sort()
    {
        $children = $this->model->children();

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

            $this->model->sortChild($child, $sorting);
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

    private function findOrCreateStoredSetReference(string $flat_set_ref)
    {
        list($className, $id) = explode('@', $flat_set_ref);

        /** If set reference is not stored yet, we will do this now */
        if ($className == SetReference::class) {
            return SetReference::find($id)->store();
        }

        return FlatReferenceFactory::fromString($flat_set_ref)->instance();
    }
}
