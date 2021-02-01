<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Modules;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Managers\Register\Registry;

final class Modules
{
    /** @var Registry */
    private Registry $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Enlist all available managed modules for creation.
     *
     * TODO: this is old behavior and will in time be replaced by the new pagebuilder logic. A 'Module' should not be handled differently
     * than any other resource that is used in the pagebuilder. The difference between used pagebuilder parts (module, shared modules, page, pageset, text, pagetitle) should be flattened.
     *
     * @return Collection of ManagedModelDetails
     */
    public function creatableModulesForSelect(): array
    {
        // Get the modules that can be set for the pagebuilder
        // TODO: how to assign for pagebuilder?? with tag, via the chief relations config or is this default
        $moduleManagerClasses = $this->registry->tagged('module')->get();
trap('working on it');
        $managers = collect(array_map(function($moduleManagerClass){
            return app($moduleManagerClass);
        }, $moduleManagerClasses));

        return $managers
            ->reject(function($manager){ return !$manager->can('create'); })
            ->map(function($manager) {
                return [
                    'label' => $manager->adminLabel('page_title'),
                    'value' => $manager->route('create')
                ];
            })
            ->toArray();
    }

    public function creatableShareableModulesForSelect(): array
    {
        // Get the modules that are set to be shareable.
        $moduleManagerClasses = $this->shareableModules();

        $managers = collect(array_map(function($moduleManagerClass){
            return app($moduleManagerClass);
        }, $moduleManagerClasses));

        return $managers
            ->reject(function($manager){ return !$manager->can('create-shared'); })
            ->map(function($manager){
                return [
                    'label' => $manager->adminLabel('page_title'),
                    'value' => $manager->route('create-shared')
                ];
            })
            ->toArray();
    }

    /**
     * Return true if there is at least one registered module
     */
    public function atLeastOneShareableRegistered(): bool
    {
        return count($this->shareableModules()) > 0;
    }

    /**
     * @return array
     */
    public function shareableModules(): array
    {
        return $this->registry->tagged('module')->tagged('shared')->managers();
    }
}
