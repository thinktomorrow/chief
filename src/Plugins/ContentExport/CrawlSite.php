<?php

namespace Thinktomorrow\Chief\Plugins\ContentExport;

use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

class CrawlSite
{
    private Registry $registry;
    private string $delimiter = ';';
    private string $enclosure = '"';

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function handle()
    {
        $locale = 'nl';
        $targetLocales = ['fr'];

//        dd([
//            'modelEncryptId' => [
//                'metadata' => [
//                    'reference' => 'page@1',
//                    'title' => '',
//                    'admin_url' => '',
        //              'url' => '',
//                ],
//                'fields' => [
//                    'title' => [
//                        'nl' => 'dkdkdk',
//                        'en' => null,
//                    ],
//                ],
//                'fragments' => [
//                    'sdkfsierieriID' => [
//                        ''
//                    ],
//                ],
//                'sharedFragments' => [
//                    //
//                ],
//
//            ],
//        ]);

        $batch = [];

        // write each line to csv.
        // check if it can be imported. first via command
        // nested fragments
        // shared fragments, seo, urls, static texts, ...

        return $this->writeCsv(function($handle) use($locale, $targetLocales) {
            foreach($this->getModels($locale) as $model) {
                dd($this->extractModel($model, $locale, $targetLocales));
//                fputcsv($handle, , $this->delimiter, $this->enclosure);
                // TODO: nested fragments...
                // Get all model fields as well (textarea, text, html, repeatfield, ...)
            }
        });

        // Loop all models
        // loop each fragment
        // render each fragment and reduce noice
        // Shared fragments are separate
    }

    private function extractModel($model, string $locale, array $targetLocales)
    {
        $modelEntry = [
            'metadata' => [
                'reference' => $this->encryptModelReference($model),
                'title' => $this->registry->findResourceByModel($model::class)->getPageTitle($model),
            ],
            'fields' => $this->extractFieldValues($model, $locale, $targetLocales),
            'fragments' => [],
            'sharedFragments' => [],
        ];

        // TODO: locale as second argument is for upcoming changes to fragments logic.
        // TODO: this should be: get $locale context and than all those fragments
        /** @var Fragmentable $fragment */
        foreach(app(FragmentRepository::class)->getByOwner($model, $locale) as $fragment) {

            $fragmentEntry = [
                'metadata' => [
                    'reference' => $this->encryptModelReference($fragment),
                    'title' => $fragment->getLabel(),
                ],
                'fields' => $this->extractFieldValues($fragment, $locale, $targetLocales),
            ];

            $modelEntry['fragments'][] = $fragmentEntry;
        }

        return $modelEntry;
        // TODO: nested fragments...
        // Get all model fields as well (textarea, text, html, repeatfield, ...)
    }

    private function extractFieldValues($model, string $locale, array $targetLocales): array
    {
        $fields = [];

        $modelFields = Fields::makeWithoutFlatteningNestedFields($model->fields($model))
            ->filterBy(fn($field) => in_array($field::class, [Fields\Text::class, Fields\Textarea::class, Fields\Html::class, Fields\Repeat::class]))
            ->model($model instanceof Fragmentable ? $model->fragmentModel() : $model);

        foreach($modelFields as $field) {

            if($field instanceof Fields\Repeat) {
                $fields = array_merge($fields, $this->extractRepeatField($field, $locale, $targetLocales));
            } else {
                $fields[$field->getKey()] = [
                    $locale => $field->getValue($locale),
                    ...collect($targetLocales)->mapWithKeys(fn($targetLocale) => [$targetLocale => $field->getValue($targetLocale)])->all(),
                ];
            }
        }

        if($model instanceof Fragmentable && $model instanceof FragmentsOwner) {
            $fields = array_merge($fields, $this->extractFieldValues($model, $locale, $targetLocales));
        }

        return $fields;
    }

    private function extractRepeatField(Fields\Repeat $field, string $locale, array $targetLocales): array
    {
        $fields = [];

        foreach($field->getValue($locale) as $i => $_values) {
            foreach($_values as $key => $values) {

                foreach(Arr::dot($values) as $prefix => $value) {
                    $_locale = false !== strpos($prefix, '.') ? substr($prefix, strrpos($prefix, '.') + 1) : $prefix;
                    $index = $field->getKey() . '.' . $i . '.' .$key . ($_locale !== $prefix ? '.'.str_replace( '.'.$_locale, '', $prefix) : '');

                    if(!in_array($_locale, [$locale, ... $targetLocales])) continue;

                    if(!isset($fields[$index])) {
                        $fields[$index] = [];
                    }

                    $fields[$index][$_locale] = $value;
                }

            }
        }

        return $fields;
    }

    private function encryptModelReference(ReferableModel $model): string
    {
        return encrypt($model->modelReference()->get());
    }

    private function decryptModelReference(string $encryptedModelReference): ModelReference
    {
        return ModelReference::fromString(decrypt($encryptedModelReference));
    }

    private function writeCsv(callable $callback)
    {
        $handle = fopen('php://memory', 'r+');

        $callback($handle);

        rewind($handle);
        $output = stream_get_contents($handle);
        fclose($handle);

        return $output;
    }

    private function createListingByFields()
    {

    }

    private function initiateRequestPool(string $locale): void
    {
        $generator = $this->createUrlGenerator($this->getModels($locale));

        $pool = new Pool($this->httpClient, $generator, [
            'concurrency' => 5,
            'fulfilled' => function (Response $response, $index) {
            dd($response->getBody()->getContents());
//                if ($response->getStatusCode() !== \Symfony\Component\HttpFoundation\Response::HTTP_OK) {
//                    unset($this->urls[$index]);
//                }
            },
            'rejected' => function ($_reason, $index) {
            dd('sisis', $_reason);
                //
            },
        ]);

        // Initiate the transfers and create a promise
        $promise = $pool->promise();

        // Force the pool of requests to complete.
        $promise->wait();
    }

    private function createUrlGenerator(iterable $urls): \Generator
    {
        foreach ($urls as $index => $url) {
            yield $index => new Request('GET', $url);
        }
    }

    private function getModels(string $locale): Collection
    {
        return UrlRecord::allOnlineModels($locale)
            // In case the url is not found or present for given locale.
            ->reject(function (Visitable $model) use ($locale) {
                return ! $model->url($locale);
            });
    }
}
