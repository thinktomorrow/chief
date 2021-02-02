<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Database;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\AssetLibrary\AssetTrait;
use Thinktomorrow\Chief\ManagedModels\ManagedModel;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;
use Thinktomorrow\Chief\ManagedModels\Assistants\ManagedModelDefaults;

final class FragmentModel extends Model implements ManagedModel, HasAsset
{
    use ManagedModelDefaults;
    use HasDynamicAttributes;
    use AssetTrait;

    public $table = 'context_fragments';
    public $guarded = [];

    // Allow for uuid
    protected $keyType = 'string';
    public $incrementing = false;

    public $dynamicKeys = ['*'];
    public $dynamicKeysBlacklist = [
        'id', 'context_id', 'model_reference', 'order', 'created_at', 'updated_at',
    ];

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
        return !$this->isOnline();
    }
}
