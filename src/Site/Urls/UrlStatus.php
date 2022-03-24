<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls;

enum UrlStatus:string
{
    case online = 'online';
    case offline = 'offline';
}
