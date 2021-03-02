<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Database;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\AssetLibrary\AssetTrait;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\ManagedModels\Assistants\ManagedModelDefaults;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\ManagedModel;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

final class FragmentModel extends Model implements ManagedModel, HasAsset
{
    use ManagedModelDefaults;
    use HasDynamicAttributes;
    use AssetTrait;

    /**
     * Used as reference to the fragmentModel
     * as predefined owner of nested fragments
     */
    const MODELTYPE = 'fragmentmodel';

    public $table = 'context_fragments';
    public $guarded = [];

    // Allow for uuid
    protected $keyType = 'string';
    public $incrementing = false;

    public $dynamicKeys = ['*'];
    public $dynamicKeysBlacklist = [
        'id', 'model_reference', 'shared', 'created_at', 'updated_at',
    ];

    public function isShared(): bool
    {
        return ! ! $this->shared;
    }

    public static function managedModelKey(): string
    {
        return 'fragments';
    }

    public function fields(): Fields
    {
        return new Fields();
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
}
