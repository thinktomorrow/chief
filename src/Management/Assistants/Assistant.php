<?php

namespace Thinktomorrow\Chief\Management\Assistants;

use Thinktomorrow\Chief\Management\Manager;

interface Assistant
{
    public function manager(Manager $manager);

    /**
     * Identifies the assistant with an unique string. The convention
     * is usually to take the first part of the className. e.g.
     * ArchiveAssistant has 'archive' as its key.
     *
     * @return string
     */
    public static function key(): string;

    /**
     * Compose an assistant route
     *
     * @param $verb
     * @return null|string
     */
    public function route($verb): ?string;

    /**
     * Check if the action is allowed.
     *
     * @param $verb
     * @return bool
     */
    public function can($verb): bool;
}
