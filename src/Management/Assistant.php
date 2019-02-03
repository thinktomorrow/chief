<?php

namespace Thinktomorrow\Chief\Management;

interface Assistant
{
    public function manager(Manager $manager);

    /**
     * Identifies the assistant with an unique string. The convention
     * is usually to take the first part of the className. e.g.
     * ArchiveAssistant could have 'archive' as its key.
     *
     * @return string
     */
    public static function key(): string;
}
