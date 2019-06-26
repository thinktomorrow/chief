<?php

namespace Thinktomorrow\Chief\Setup;

interface Task
{
    public function check(): bool;

    public function run();
}