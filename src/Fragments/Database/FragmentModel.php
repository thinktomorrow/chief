<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thinktomorrow\AssetLibrary\AssetTrait;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\ManagedModels\Assistants\ManagedModelDefaults;
use Thinktomorrow\Chief\ManagedModels\ManagedModel;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

final class FragmentModel extends Model implements ManagedModel, HasAsset
{
    use ManagedModelDefaults;
    use HasDynamicAttributes;
    use AssetTrait;
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
    protected $casts = [ 'id' => 'integer' , 'meta' => 'array' ];

    public $dynamicKeys = ['*'];
    public $dynamicKeysBlacklist = [
        'id', 'model_reference', 'meta', 'created_at', 'updated_at',
    ];

    public static function managedModelKey(): string
    {
        return 'fragments';
    }

    public function fields(): iterable
    {
        return [];
    }

    protected function dynamicDocumentKey(): string
    {
        return 'data';
    }

    protected function dynamicLocales(): array
    {
        return config('chief.locales', []);
    }

    public function isOnline(): bool
    {
        // Default is online, except explicitly set offline
        return (null === $this->online_status || $this->online_status != 0);
    }

    public function isOffline(): bool
    {
        return ! $this->isOnline();
    }

    public function isShared(): bool
    {
        return (bool) $this->getMeta('shared');
    }

    public function setMeta(string $key, $value): void
    {
        if (! $this->meta) {
            $this->meta = [];
        }

        $this->meta = array_merge($this->meta, [$key => $value]);
    }

    private function getMeta(string $key)
    {
        if (! $this->meta || ! array_key_exists($key, $this->meta)) {
            return false;
        }

        return $this->meta[$key];
    }
}
