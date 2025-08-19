<?php

namespace Thinktomorrow\Chief\App\Console;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Sites\ChiefSites;

class LocalizeRepeatFieldCommand extends BaseCommand
{
    protected $signature = 'chief:localize-repeat-field {classes} {key}';

    protected $description = 'Move localized repeat items to localized repeat format';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $classes = explode(',', $this->argument('classes'));
        $fieldKey = $this->argument('key');

        foreach ($classes as $class) {

            $models = ((new \ReflectionClass($class))->hasMethod('withoutGlobalScopes')
                ? $class::withoutGlobalScopes()->get()
                : $class::all()
            );

            $models->each(function ($model) use ($fieldKey) {
                $this->localizeRepeatField($model, $fieldKey);
            });

            $this->info('All ['.$class.'] models have their ['.$fieldKey.'] values updated.');
        }
    }

    private function localizeRepeatField(Model $model, string $fieldKey): void
    {
        $value = $model->dynamic($fieldKey);

        if (! isset($value)) {
            $this->warn('Model ['.$model::class.' '.$model->id.'] does not have a ['.$fieldKey.'] field.');

            return;
        }

        if (! is_array($value) || empty($value)) {
            $this->warn('Model ['.$model::class.' '.$model->id.'] does not have a ['.$fieldKey.'] field that is an array.');

            return;
        }

        $keys = array_keys($value);

        if (! is_int($keys[0])) {
            $this->info('Model ['.$model::class.' '.$model->id.'] is already a converted ['.$fieldKey.'] field.');

            return;
        }

        $value = $this->transformLocalizedArray($value);

        $model->setDynamic($fieldKey, $value);
        $model->save();

        $this->info('Model ['.$model::class.' '.$model->id.'] has ['.$fieldKey.'] repeat value localized.');
    }

    private function transformLocalizedArray(array $items): array
    {
        $result = [];

        foreach ($items as $item) {
            $locales = [];

            foreach ($item as $property => $translations) {
                // We hope that at least one subitem is localized...
                if (is_array($translations)) {
                    $locales = array_unique(array_merge($locales, array_keys($translations)));
                }
            }

            if (empty($locales)) {
                $locales = ChiefSites::locales();
            }

            foreach ($locales as $locale) {
                $entry = [];

                foreach ($item as $property => $translations) {
                    if (! is_array($translations)) {
                        $entry[$property] = $translations;

                        continue;
                    }

                    $entry[$property] = $translations[$locale] ?? null;
                }

                $result[$locale][] = $entry;
            }
        }

        return $result;
    }
}
