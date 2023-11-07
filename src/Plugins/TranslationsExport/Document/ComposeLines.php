<?php

namespace Thinktomorrow\Chief\Plugins\TranslationsExport\Document;

use Illuminate\Support\Arr;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

class ComposeLines
{
    private Registry $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function compose($model, string $locale, array $targetLocales): array
    {
        $lines = new LinesCollection();

        $this->addModelMetadata($lines, $model);

        dd($lines);
//        $lines = $this->addSeparator($lines);
        $lines = $this->addModelFieldValues($lines, $model, $locale, $targetLocales);
        $lines = $this->addFragmentFieldValues($lines, $model, $locale, $targetLocales);



        return $lines;
        // TODO: nested fragments...
        // Get all model fields as well (textarea, text, html, repeatfield, ...)
    }

//    private function encryptModelReference(ReferableModel $model): string
//    {
//        return encrypt($model->modelReference()->get());
//    }
//
//    private function decryptModelReference(string $encryptedModelReference): ModelReference
//    {
//        return ModelReference::fromString(decrypt($encryptedModelReference));
//    }

//    private function writeToMemory(callable $callback)
//    {
//        $handle = fopen('php://memory', 'r+');
//
//        $callback($handle);
//
//        rewind($handle);
//        $output = stream_get_contents($handle);
//        fclose($handle);
//
//        return $output;
//    }

//    private function createListingByFields()
//    {
//
//    }
//
//    private function initiateRequestPool(string $locale): void
//    {
//        $generator = $this->createUrlGenerator($this->getModels($locale));
//
//        $pool = new Pool($this->httpClient, $generator, [
//            'concurrency' => 5,
//            'fulfilled' => function (Response $response, $index) {
//            dd($response->getBody()->getContents());
////                if ($response->getStatusCode() !== \Symfony\Component\HttpFoundation\Response::HTTP_OK) {
////                    unset($this->urls[$index]);
////                }
//            },
//            'rejected' => function ($_reason, $index) {
//            dd('sisis', $_reason);
//                //
//            },
//        ]);
//
//        // Initiate the transfers and create a promise
//        $promise = $pool->promise();
//
//        // Force the pool of requests to complete.
//        $promise->wait();
//    }
//
//    private function createUrlGenerator(iterable $urls): \Generator
//    {
//        foreach ($urls as $index => $url) {
//            yield $index => new Request('GET', $url);
//        }
//    }
//


//    public function compose()
//    {
//        $locale = 'nl';
//        $targetLocales = ['fr'];
//
////        dd([
////            'modelEncryptId' => [
////                'metadata' => [
////                    'reference' => 'page@1',
////                    'title' => '',
////                    'admin_url' => '',
//        //              'url' => '',
////                ],
////                'fields' => [
////                    'title' => [
////                        'nl' => 'dkdkdk',
////                        'en' => null,
////                    ],
////                ],
////                'fragments' => [
////                    'sdkfsierieriID' => [
////                        ''
////                    ],
////                ],
////                'sharedFragments' => [
////                    //
////                ],
////
////            ],
////        ]);
//
//        $batch = [];
//
//        // write each line to csv.
//        // check if it can be imported. first via command
//        // nested fragments
//        // shared fragments, seo, urls, static texts, ...
//
//        $output = $this->writeToMemory(function($handle) use($locale, $targetLocales) {
//            foreach($this->getModels($locale) as $model) {
//                dd($this->extractModel($model, $locale, $targetLocales));
////                fputs($handle, );
////                dd($this->extractModel($model, $locale, $targetLocales));
////                fputcsv($handle, , $this->delimiter, $this->enclosure);
//                // TODO: nested fragments...
//                // Get all model fields as well (textarea, text, html, repeatfield, ...)
//            }
//        });
//
//        dd($output);
//
//        // Loop all models
//        // loop each fragment
//        // render each fragment and reduce noice
//        // Shared fragments are separate
//    }
    private function addModelMetadata(LinesCollection $lines, $model): void
    {
        $lines->push(new InfoLine([
            $this->registry->findResourceByModel($model::class)->getPageTitle($model),
        ]));
    }

    private function addModelFieldValues($lines, $model, string $locale, array $targetLocales)
    {


        return array_merge($lines, $this->extractFieldValues($model, $locale, $targetLocales));
    }

    private function extractFieldValues($model, string $locale, array $targetLocales): LinesCollection
    {
        $lines = new LinesCollection();

        $modelFields = Fields::makeWithoutFlatteningNestedFields($model->fields($model))
            ->filterBy(fn($field) => in_array($field::class, [Fields\Text::class, Fields\Textarea::class, Fields\Html::class, Fields\Repeat::class]))
            ->model($model instanceof Fragmentable ? $model->fragmentModel() : $model);

        foreach($modelFields as $field) {

            if($field instanceof Fields\Repeat) {
                $lines->merge($this->extractRepeatField($field, $locale, $targetLocales))
            } else {
                $lines->push(new TranslationLine(
                    'id of fragment -> field non encrypted',
                    $field->getKey(),
                    $field->getValue($locale),
                    ...collect($targetLocales)->mapWithKeys(fn($targetLocale) => [$targetLocale => $field->getValue($targetLocale)])->all(),
                );
            }
        }

        if($model instanceof Fragmentable && $model instanceof FragmentsOwner) {
            $lines = array_merge($lines, $this->extractFieldValues($model, $locale, $targetLocales));
        }

        return $lines;
    }

    private function extractRepeatField(Fields\Repeat $field, string $locale, array $targetLocales): LinesCollection
    {
        $lines = new LinesCollection();

        if(!$field->getValue($locale)) return $lines;

        foreach($field->getValue($locale) as $i => $_values) {
            foreach($_values as $key => $values) {

                foreach(Arr::dot($values) as $prefix => $value) {
                    $_locale = false !== strpos($prefix, '.') ? substr($prefix, strrpos($prefix, '.') + 1) : $prefix;
                    $index = $field->getKey() . '.' . $i . '.' .$key . ($_locale !== $prefix ? '.'.str_replace( '.'.$_locale, '', $prefix) : '');

                    if(!in_array($_locale, [$locale, ... $targetLocales])) continue;

                    $lines->first(fn(Line $line) => $line->getReference());

                    if(!isset($lines[$index])) {
                        $lines[$index] = [
                            'ref' => 'id of fragment -> dynamic key encrypted',
                            'key' => $index,
                        ];
                    }

                    $lines[$index][$_locale] = $value;
                }

            }
        }

        return $lines;
    }

    private function addFragmentFieldValues(array $lines, $model, string $locale, array $targetLocales): array
    {
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

            $lines['fragments'][] = $fragmentEntry;
        }

//        $lines[] =

    }
}
