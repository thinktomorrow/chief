<?php

namespace Thinktomorrow\Chief\App\Http\Middleware;

use Closure;
use Spatie\ImageOptimizer\OptimizerChain;

class OptimizeImages
{
    public function handle($request, Closure $next)
    {
        $optimizerChain = app(OptimizerChain::class);

        collect($request->allFiles())->each(function ($file) use ($optimizerChain) {
            if (is_array($file)) {
                collect($file)->each(function ($media) use ($optimizerChain) {
                    $optimizerChain->optimize($media->getPathname());
                });
            } else {
                $optimizerChain->optimize($file->getPathname());
            }
        });

        return $next($request);
    }
}
