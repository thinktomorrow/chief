<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Domain;

enum FragmentStatus: string
{
    case online = 'online';
    case offline = 'offline';
}
