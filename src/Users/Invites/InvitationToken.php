<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Users\Invites;

use Illuminate\Support\Str;

class InvitationToken
{
    public static function generate()
    {
        return hash_hmac('sha256', Str::random(20), static::hashKey());
    }

    private static function hashKey(): string
    {
        $key = config('app.key');

        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        return $key;
    }
}
