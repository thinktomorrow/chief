<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Assets\App;

use Illuminate\Support\Str;

class MimetypeIcon
{
    private static $fallback = '<svg width="24" height="24"><use xlink:href="#icon-document"></use></svg>';

    private $mapping = [
        'application/pdf' => '<svg width="24" height="24"><use xlink:href="#icon-document"></use></svg>',
        'video' => '<svg width="24" height="24"><use xlink:href="#icon-video"></use></svg>',
    ];

    /** @var string */
    private $mimetype;

    private function __construct(string $mimetype)
    {
        $this->mimetype = $mimetype;
    }

    /**
     * @return static
     */
    public static function fromString(string $mimetype): self
    {
        return new static($mimetype);
    }

    public function icon(): string
    {
        if (isset($this->mapping[$this->mimetype])) {
            return $this->mapping[$this->mimetype];
        }

        foreach ($this->mapping as $mimetype => $icon) {
            if (Str::contains($this->mimetype, $mimetype)) {
                return $icon;
            }
        }

        return self::$fallback;
    }
}
