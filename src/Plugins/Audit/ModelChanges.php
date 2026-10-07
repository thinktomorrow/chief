<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use JsonException;

final class ModelChanges
{
    private const SECRET_SEGMENTS = [
        '*password*', 'passwd', '*secret*', '*token*',
        '*apikey*', 'api_key', '*_key', 'authorization', 'cookie', '*credential*', 'private_key',
    ];

    /**
     * Capture while the model is dirty, before saving resets its original attributes.
     *
     * @param  list<string>|null  $paths
     * @param  list<string>  $exclusions
     * @return array<string, array{before: mixed, after: mixed}>
     */
    public static function capture(Model $model, ?array $paths = null, array $exclusions = []): array
    {
        $paths ??= method_exists($model, 'auditChangePaths') ? $model->auditChangePaths() : [];
        $exclusions = array_merge(
            self::SECRET_SEGMENTS,
            method_exists($model, 'auditChangeExclusions') ? $model->auditChangeExclusions() : [],
            $exclusions,
        );

        foreach ([$paths, $exclusions] as $patterns) {
            foreach ($patterns as $pattern) {
                if (! is_string($pattern) || $pattern === '') {
                    throw new InvalidArgumentException('Invalid audit change path.');
                }
            }
        }

        if ($paths === []) {
            return [];
        }

        $before = self::flatten($model, $model->getRawOriginal());
        $after = self::flatten($model, $model->getAttributes());
        self::flattenTranslations($model, $before, $after);
        $changes = [];

        foreach (array_keys($before + $after) as $path) {
            if (! self::selected($path, $paths) || self::excluded($path, $exclusions)) {
                continue;
            }

            $old = array_key_exists($path, $before) ? $before[$path] : ['missing' => true];
            $new = array_key_exists($path, $after) ? $after[$path] : ['missing' => true];

            if ($old !== $new) {
                $changes[$path] = self::textChange($old, $new);
            }
        }

        return $changes;
    }

    /** @return array{before: mixed, after: mixed} */
    private static function textChange(mixed $before, mixed $after): array
    {
        if ((! is_string($before) || mb_strlen($before) <= 500) && (! is_string($after) || mb_strlen($after) <= 500)) {
            return ['before' => $before, 'after' => $after];
        }

        $old = is_string($before) ? $before : '';
        $new = is_string($after) ? $after : '';
        $prefix = 0;
        $limit = min(mb_strlen($old), mb_strlen($new));

        while ($prefix < $limit && mb_substr($old, $prefix, 1) === mb_substr($new, $prefix, 1)) {
            $prefix++;
        }

        $suffix = 0;
        while ($suffix < $limit - $prefix && mb_substr($old, -$suffix - 1, 1) === mb_substr($new, -$suffix - 1, 1)) {
            $suffix++;
        }

        $context = max(0, min(200, (int) config('chief.audit.change_context_length', 40)));

        if (! is_string($before) || ! is_string($after)) {
            return [
                'before' => is_string($before) ? ['excerpt' => self::beginning($before, $context)] : $before,
                'after' => is_string($after) ? ['excerpt' => self::beginning($after, $context)] : $after,
            ];
        }

        return [
            'before' => ['excerpt' => self::excerpt($old, $prefix, $suffix, $context)],
            'after' => ['excerpt' => self::excerpt($new, $prefix, $suffix, $context)],
        ];
    }

    private static function beginning(string $text, int $context): string
    {
        $length = min(mb_strlen($text), max(40, $context * 2));

        return 'Begin: '.mb_substr($text, 0, $length).($length < mb_strlen($text) ? '…' : '');
    }

    private static function excerpt(string $text, int $prefix, int $suffix, int $context): string
    {
        $length = mb_strlen($text);
        $start = max(0, $prefix - $context);
        $end = min($length, $length - $suffix + $context);

        if ($end - $start <= 2 * $context + 100) {
            return ($start ? '…' : '').mb_substr($text, $start, $end - $start).($end < $length ? '…' : '');
        }

        return ($start ? '…' : '').mb_substr($text, $start, $context + 50).'…'.mb_substr($text, $end - $context - 50, $context + 50).($end < $length ? '…' : '');
    }

    /** @return array<string, scalar|null> */
    private static function flatten(Model $model, array $attributes): array
    {
        $result = [];

        foreach ($attributes as $key => $value) {
            $cast = $model->getCasts()[$key] ?? null;

            if (in_array($cast, ['array', 'json', 'object', 'collection'], true)) {
                try {
                    $value = is_string($value) ? json_decode($value, true, 64, JSON_THROW_ON_ERROR) : $value;
                } catch (JsonException) {
                    continue;
                }
            } elseif ($cast !== null && ! in_array($cast, ['int', 'integer', 'real', 'float', 'double', 'bool', 'boolean', 'string'], true)) {
                continue;
            } elseif ($value !== null) {
                $value = match ($cast) {
                    'int', 'integer' => (int) $value,
                    'real', 'float', 'double' => (float) $value,
                    'bool', 'boolean' => (bool) $value,
                    'string' => (string) $value,
                    default => $value,
                };
            }

            self::flattenValue((string) $key, $value, $result);
        }

        return $result;
    }

    /**
     * Loaded translations are captured in memory; fetching them later could only see post-save state.
     *
     * @param  array<string, mixed>  $before
     * @param  array<string, mixed>  $after
     */
    private static function flattenTranslations(Model $model, array &$before, array &$after): void
    {
        if (! $model->relationLoaded('translations') || ! method_exists($model, 'getLocaleKey') || ! method_exists($model, 'isTranslationAttribute')) {
            return;
        }

        foreach ($model->getRelation('translations') as $translation) {
            if (! $translation instanceof Model) {
                continue;
            }

            $locale = $translation->getRawOriginal($model->getLocaleKey()) ?? $translation->getAttributes()[$model->getLocaleKey()] ?? null;
            if (! is_string($locale) || $locale === '' || str_contains($locale, '.')) {
                continue;
            }

            self::appendTranslation($model, $translation, $translation->getRawOriginal(), $locale, $before);
            self::appendTranslation($model, $translation, $translation->getAttributes(), $locale, $after);
        }
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, mixed>  $target
     */
    private static function appendTranslation(Model $model, Model $translation, array $attributes, string $locale, array &$target): void
    {
        foreach (self::flatten($translation, $attributes) as $key => $value) {
            $attribute = explode('.', $key, 2)[0];
            if ($model->isTranslationAttribute($attribute)) {
                $target['translations.'.$locale.'.'.$key] = $value;
            }
        }
    }

    private static function flattenValue(string $path, mixed $value, array &$result, int $depth = 0): void
    {
        if ($depth > 32) {
            return;
        }

        if (is_array($value)) {
            foreach ($value as $key => $child) {
                if (str_contains((string) $key, '.') || (string) $key === '') {
                    continue;
                }
                self::flattenValue($path.'.'.$key, $child, $result, $depth + 1);
            }
        } elseif (is_scalar($value) || $value === null) {
            try {
                json_encode($value, JSON_THROW_ON_ERROR);
                $result[$path] = $value;
            } catch (JsonException) {
                return;
            }
        }
    }

    /** @param list<string> $paths */
    private static function selected(string $path, array $paths): bool
    {
        foreach ($paths as $pattern) {
            if ($pattern === '*' || $pattern === $path || str_starts_with($path, $pattern.'.') || fnmatch($pattern, $path, FNM_NOESCAPE)) {
                return true;
            }
        }

        return false;
    }

    /** @param list<string> $exclusions */
    private static function excluded(string $path, array $exclusions): bool
    {
        foreach ($exclusions as $pattern) {
            foreach (explode('.', $path) as $segment) {
                if (fnmatch(strtolower($pattern), strtolower($segment), FNM_NOESCAPE)) {
                    return true;
                }
            }

            if (fnmatch(strtolower($pattern), strtolower($path), FNM_NOESCAPE)) {
                return true;
            }
        }

        return false;
    }
}
