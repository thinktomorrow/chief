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
        $context = max(0, min(200, (int) config('chief.audit.change_context_length', 40)));

        if (! is_string($before) || ! is_string($after)) {
            return [
                'before' => is_string($before) ? ['excerpt' => self::beginning($before, $context)] : $before,
                'after' => is_string($after) ? ['excerpt' => self::beginning($after, $context)] : $after,
            ];
        }

        [$beforeExcerpt, $afterExcerpt] = self::excerpts($old, $new, $context);

        return ['before' => ['excerpt' => $beforeExcerpt], 'after' => ['excerpt' => $afterExcerpt]];
    }

    private static function beginning(string $text, int $context): string
    {
        $length = min(mb_strlen($text), max(40, $context * 2));

        return 'Begin: '.mb_substr($text, 0, $length).($length < mb_strlen($text) ? '…' : '');
    }

    /** @return array{string, string} */
    private static function excerpts(string $old, string $new, int $context): array
    {
        $before = mb_str_split($old);
        $after = mb_str_split($new);
        $prefix = 0;
        while (isset($before[$prefix], $after[$prefix]) && $before[$prefix] === $after[$prefix]) {
            $prefix++;
        }
        $suffix = 0;
        while ($suffix < min(count($before), count($after)) - $prefix && $before[count($before) - $suffix - 1] === $after[count($after) - $suffix - 1]) {
            $suffix++;
        }

        $left = array_slice($before, $prefix, count($before) - $prefix - $suffix);
        $right = array_slice($after, $prefix, count($after) - $prefix - $suffix);
        $matches = self::matchingCharacters($left, $right);
        if ($matches === [] && (count($left) > 2 * $context + 100 || count($right) > 2 * $context + 100)) {
            return [
                self::largeReplacementExcerpt($before, $prefix, $suffix, $context),
                self::largeReplacementExcerpt($after, $prefix, $suffix, $context),
            ];
        }
        $ranges = [];
        $oldAt = $newAt = $prefix;
        foreach ([...$matches, [count($left), count($right)]] as [$oldMatch, $newMatch]) {
            $oldMatch += $prefix;
            $newMatch += $prefix;
            if ($oldAt !== $oldMatch || $newAt !== $newMatch) {
                $range = [
                    [max(0, $oldAt - $context), min(count($before), $oldMatch + $context)],
                    [max(0, $newAt - $context), min(count($after), $newMatch + $context)],
                ];
                $last = count($ranges) - 1;
                if ($last >= 0 && ($range[0][0] <= $ranges[$last][0][1] || $range[1][0] <= $ranges[$last][1][1])) {
                    $ranges[$last][0][1] = max($ranges[$last][0][1], $range[0][1]);
                    $ranges[$last][1][1] = max($ranges[$last][1][1], $range[1][1]);
                } else {
                    $ranges[] = $range;
                }
            }
            $oldAt = $oldMatch + 1;
            $newAt = $newMatch + 1;
        }

        return [self::renderExcerpt($before, $ranges, 0), self::renderExcerpt($after, $ranges, 1)];
    }

    /** @param list<string> $characters */
    private static function largeReplacementExcerpt(array $characters, int $prefix, int $suffix, int $context): string
    {
        $length = count($characters);
        $start = max(0, $prefix - $context);
        $end = min($length, $length - $suffix + $context);
        $slice = $context + 50;
        if ($end - $start <= 2 * $slice) {
            return ($start > 0 ? '…' : '').implode('', array_slice($characters, $start, $end - $start)).($end < $length ? '…' : '');
        }

        return ($start > 0 ? '…' : '').implode('', array_slice($characters, $start, $slice)).'…'
            .implode('', array_slice($characters, $end - $slice, $slice)).($end < $length ? '…' : '');
    }

    /**
     * Find unchanged characters with a bounded edit search. A wholesale rewrite is one changed region.
     *
     * @param  list<string>  $old
     * @param  list<string>  $new
     * @return list<array{int, int}>
     */
    private static function matchingCharacters(array $old, array $new): array
    {
        $n = count($old);
        $m = count($new);
        $frontier = [1 => 0];
        $trace = [];
        for ($distance = 0; $distance <= min($n + $m, 200); $distance++) {
            $trace[$distance] = $frontier;
            for ($diagonal = -$distance; $diagonal <= $distance; $diagonal += 2) {
                $x = ($diagonal === -$distance || ($diagonal !== $distance && ($frontier[$diagonal - 1] ?? -1) < ($frontier[$diagonal + 1] ?? -1)))
                    ? ($frontier[$diagonal + 1] ?? 0) : ($frontier[$diagonal - 1] ?? 0) + 1;
                $y = $x - $diagonal;
                while ($x < $n && $y < $m && $old[$x] === $new[$y]) {
                    $x++;
                    $y++;
                }
                $frontier[$diagonal] = $x;
                if ($x < $n || $y < $m) {
                    continue;
                }

                $matches = [];
                for ($step = $distance; $step >= 0; $step--) {
                    $previous = $trace[$step];
                    $diagonal = $x - $y;
                    if ($step === 0) {
                        $previousX = $previousY = 0;
                    } else {
                        $previousDiagonal = ($diagonal === -$step || ($diagonal !== $step && ($previous[$diagonal - 1] ?? -1) < ($previous[$diagonal + 1] ?? -1))) ? $diagonal + 1 : $diagonal - 1;
                        $previousX = $previous[$previousDiagonal] ?? 0;
                        $previousY = $previousX - $previousDiagonal;
                    }
                    while ($x > $previousX && $y > $previousY) {
                        $matches[] = [--$x, --$y];
                    }
                    $x = $previousX;
                    $y = $previousY;
                }

                return array_reverse($matches);
            }
        }

        return [];
    }

    /** @param list<array{array{int, int}, array{int, int}}> $ranges */
    private static function renderExcerpt(array $characters, array $ranges, int $side): string
    {
        $excerpt = '';
        $end = 0;
        foreach ($ranges as $range) {
            [$start, $next] = $range[$side];
            if ($start > $end) {
                $excerpt .= '…';
            }
            $excerpt .= implode('', array_slice($characters, $start, $next - $start));
            $end = $next;
        }

        return $excerpt.($end < count($characters) ? '…' : '');
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
