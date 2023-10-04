<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\AssetLibrary\InteractsWithAssets;
use Thinktomorrow\Chief\Fragments\FragmentStatus;
use Thinktomorrow\Chief\Locale\ChiefLocaleConfig;
use Thinktomorrow\Chief\Resource\FragmentResource;
use Thinktomorrow\Chief\Resource\FragmentResourceDefault;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

final class FragmentModel extends Model implements FragmentResource, HasAsset, ReferableModel
{
    use FragmentResourceDefault;
    use ReferableModelDefault;
    use HasDynamicAttributes;
    use InteractsWithAssets;
    use SoftDeletes;

    /**
     * Used as reference to the fragmentModel
     * as predefined owner of nested fragments
     */
    const MODELTYPE = 'fragmentmodel';

    public $table = 'context_fragments';
    public $guarded = [];

    // Allow for uuid type behaviour
    public $incrementing = false;

    // Force integer when query results come back (by default id is a string)
    public $dynamicKeys = ['*'];
    public $dynamicKeysBlacklist = [
        'id', 'model_reference', 'meta', 'created_at', 'updated_at',
    ];
    protected $casts = ['id' => 'integer', 'meta' => 'array'];
    private ?string $dynamicLocaleFallback = null;

    public static function resourceKey(): string
    {
        return 'fragments';
    }

    public function fields($model): iterable
    {
        return [];
    }

    public function setDynamicLocaleFallback(?string $dynamicLocaleFallback = null): void
    {
        $this->dynamicLocaleFallback = $dynamicLocaleFallback;
    }

    public function changeStatus(FragmentStatus $status): void
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
        return (null === $this->online_status || $this->online_status === FragmentStatus::online->value);
    }

    public function isShared(): bool
    {
        return (bool)$this->getMeta('shared');
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

    protected function dynamicLocales(): array
    {
        return ChiefLocaleConfig::getLocales();
    }

    protected function dynamicLocaleFallback(): ?string
    {
        return $this->dynamicLocaleFallback;
    }
}
