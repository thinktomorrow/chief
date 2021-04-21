<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls\ProvidesUrl;

use Closure;
use ReflectionClass;
use Thinktomorrow\Chief\Managers\Register\Registry;

final class PageRouteResolver
{
    private Registry $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Set a default route resolver for all visitable models.
     *
     * @param Closure $resolver
     */
    public function define(?Closure $resolver = null): void
    {
        foreach ($this->registry->models() as $modelClass) {
            if (!$this->providesUrlAndAllowsForCustomRouteResolver($modelClass)) {
                continue;
            }

            $modelClass::setRouteResolver($resolver);
        }
    }

    private function providesUrlAndAllowsForCustomRouteResolver($modelClass): bool
    {
        $ref = new ReflectionClass($modelClass);

        return $ref->implementsInterface(ProvidesUrl::class)
            && $ref->hasMethod('setRouteResolver')
            && (new \ReflectionMethod($modelClass, 'setRouteResolver'))->isStatic();
    }
}
