<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs;

final class DocSource
{
    /**
     * @param  null|string  $namespace  Null for the project's own, un-namespaced guides.
     * @param  list<string>  $excludedFolders  Top-level folders whose guides are skipped.
     */
    public function __construct(
        public readonly ?string $namespace,
        public readonly string $path,
        public readonly array $excludedFolders = [],
    ) {}

    public function guideId(string $relativeId): string
    {
        return $this->namespace === null ? $relativeId : $this->namespace.'::'.$relativeId;
    }
}
