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
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

final class FragmentModel extends Model implements FragmentResource, HasAsset, ReferableModel
{
    use FragmentResourceDefault;
    use HasDynamicAttributes;
    use InteractsWithAssets;
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

    /**
     * TODO: In the future, every model should have a $model->bookmark property, which can be changed by the user.
     * For now, we'll use a default bookmark based on the fragment id.
     */
    public function getBookmark(): ?string
    {
        return 'bookmark-'.$this->id;
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
        return ChiefSites::locales();
    }

    protected function getDynamicFallbackLocales(): array
    {
        return ChiefSites::fallbackLocales();
    }

    protected function getAssetFallbackLocales(): array
    {
        return ChiefSites::assetFallbackLocales();
    }
}
