<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Resource;

use Illuminate\Support\Str;

class ResourceKeyFormat
{
    private string $className;

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function getKey(): string
    {
        return Str::snake(Str::singular($this->classBaseName()));
    }

    public function getLabel(): string
    {
        return Str::of($this->classBaseName())->singular()->snake()->replace('_', ' ')->__toString();
    }

    public function getPluralLabel(): string
    {
        return Str::of($this->classBaseName())->plural()->snake()->replace('_', ' ')->__toString();
    }

    private function classBaseName(): string
    {
        return (new \ReflectionClass($this->className))->getShortName();
    }
}
