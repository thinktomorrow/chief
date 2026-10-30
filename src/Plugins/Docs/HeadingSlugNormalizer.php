<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs;

use Illuminate\Support\Str;
use League\CommonMark\Normalizer\TextNormalizerInterface;

/**
 * Slugs heading text to ASCII, so `Wat is één fragment?` links as `#wat-is-een-fragment`.
 */
final class HeadingSlugNormalizer implements TextNormalizerInterface
{
    /**
     * @param  array{length?: int}  $context
     */
    public function normalize(string $text, array $context = []): string
    {
        $slug = Str::slug($text, dictionary: []);

        return isset($context['length']) && $context['length'] > 0 ? rtrim(substr($slug, 0, $context['length']), '-') : $slug;
    }
}
