<?php

namespace Thinktomorrow\Chief\Management;

interface ManagerThatPublishes
{
    public function isPublished(): bool;

    public function isDraft(): bool;

    public function publish();

    public function draft();

    public function publicationStatusAsLabel($plain = false);
}
