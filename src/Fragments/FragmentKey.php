<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

use Illuminate\Support\Str;
use ReflectionClass;

class FragmentKey
{
    private string $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * Default fragment key creation from Fragment class name.
     * e.g. 'TextFragment' -> 'text', 'CallToActionFragment' -> 'call-to-action'
     */
    public static function fromClass(string $className): static
    {
        try {
            $classBaseName = (new ReflectionClass($className))->getShortName();
        } catch (\ReflectionException $e) {
            $classBaseName = $className;
        }

        $key = Str::kebab(Str::singular($classBaseName));

        return new static(
            Str::of($key)->remove('_fragment')->trim()->__toString()
        );
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
