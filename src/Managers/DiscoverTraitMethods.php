<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers;

final class DiscoverTraitMethods
{
    /**
     * Returns a list of discovered assistant methods for the given manager class.
     *
     * The assistant method naming convention is the method followed by its own classname, e.g.
     * PreviewAssistant::canPreviewAssistant. This not only avoids method collisions between
     * traits, it also allows for the plug & play functionality of an Assistant trait.
     *
     * @param string $class
     * @param string $methodPrefix
     * @return array
     */
    public static function belongingTo(string $class, string $methodPrefix): array
    {
        $traitMethods = [];

        foreach (class_uses_recursive($class) as $trait) {
            $method = $methodPrefix.class_basename($trait);

            if (public_method_exists($class, $method)) {
                $traitMethods[] = $method;
            }
        }

        return $traitMethods;
    }
}
