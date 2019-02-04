<?php

namespace Thinktomorrow\Chief\Management;

interface ManagerThatPreviews
{
    public function publish();

    public function draft();

    public function publicationStatusAsLabel($plain = false);
}
