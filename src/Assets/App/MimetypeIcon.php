<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Assets\App;

use Illuminate\Support\Str;

class MimetypeIcon
{
    /** @var string */
    private $mimetype;

    /** @var array */
    private $mapping = [
        'application/pdf' => 'chief::icon.pdf',
        'video' => 'chief::icon.video',
        'audio' => 'chief::icon.music-note',
        'application/vnd.ms-excel' => 'chief::icon.xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'chief::icon.xls',
        'text/csv' => 'chief::icon.csv',
    ];

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

        return 'chief::icon.attachment';
    }
}
