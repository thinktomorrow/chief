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

    /**
     * Retrieve a route as requested for this assistant
     * @param $verb
     * @return null|string
     */
    public function route($verb): ?string;

    /**
     * Check if the current request is allowed for the current session.
     *
     * @param $verb
     * @return bool
     */
    public function can($verb): bool;

    /**
     * Halts request when it is not allowed to be processed
     *
     * @param $verb
     * @return Assistant
     */
    public function guard($verb): self;
}
