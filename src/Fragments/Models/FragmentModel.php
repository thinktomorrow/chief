<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Models;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\AssetLibrary\InteractsWithAssets;
use Thinktomorrow\Chief\Fragments\FragmentStatus;
use Thinktomorrow\Chief\Resource\FragmentResource;
use Thinktomorrow\Chief\Resource\FragmentResourceDefault;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;
use Thinktomorrow\Chief\Sites\Locales\ChiefLocales;
use Thinktomorrow\Chief\Sites\Locales\Localized;
use Thinktomorrow\Chief\Sites\Locales\LocalizedDefault;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

final class FragmentModel extends Model implements FragmentResource, HasAsset, Localized, ReferableModel
{
    use FragmentResourceDefault;
    use HasDynamicAttributes;
    use InteractsWithAssets;
    use LocalizedDefault;
    use ReferableModelDefault;

    /**
     * Used as reference to the fragmentModel
     * as predefined owner of nested fragments
     */
    const MODELTYPE = 'fragmentmodel';

    public $table = 'context_fragments';

    public $guarded = [];

    // Allow for uuid type behaviour
    public $keyType = 'string';

    public $incrementing = false;

    public $dynamicKeys = ['*'];

    public $dynamicKeysBlacklist = [
        'id', 'key', 'meta', 'created_at', 'updated_at',
    ];

    protected $casts = ['id' => 'string', 'meta' => 'array'];

    public static function resourceKey(): string
    {
        return 'fragmentmodel';
    }

    public function fields($model): iterable
    {
        return [];
    }

    public function setOnline(): void
    {
        $this->changeStatus(FragmentStatus::online);
    }

    public function setOffline(): void
    {
        $this->changeStatus(FragmentStatus::offline);
    }

    private function changeStatus(FragmentStatus $status): void
    {
        $this->online_status = $status->value;
    }

    public function isOffline(): bool
    {
        return ! $this->isOnline();
    }

    public function isOnline(): bool
    {
        // Default is online, except explicitly set offline
        return $this->online_status === null || $this->online_status === FragmentStatus::online->value;
    }

    public function isShared(): bool
    {
        return (bool) $this->getMeta('shared');
    }

    public function getBookmark(): ?string
    {
        return $this->bookmark ?? null;
    }

    private function getMeta(string $key)
    {
        if (! $this->meta || ! array_key_exists($key, $this->meta)) {
            return false;
        }

        return $this->meta[$key];
    }

    public function setMeta(string $key, $value): void
    {
        if (! $this->meta) {
            $this->meta = [];
        }

        $this->meta = array_merge($this->meta, [$key => $value]);
    }

    protected function dynamicDocumentKey(): string
    {
        return 'data';
    }

    protected function getDynamicLocales(): array
    {
        // Locales based on the sites of the model.
        return ChiefLocales::locales();
    }
}
