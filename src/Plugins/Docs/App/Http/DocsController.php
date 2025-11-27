<?php

namespace Thinktomorrow\Chief\Plugins\Docs\App\Http;

use Illuminate\Support\Facades\File;
use Thinktomorrow\Chief\Plugins\Docs\Markdown;

class DocsController
{
    public function __construct(private Markdown $markdown) {}

    public function index()
    {
        $this->assertDocsViewFolderExists();

        $files = collect(File::files(resource_path('docs')))
            ->map(fn ($file) => pathinfo($file, PATHINFO_FILENAME));

        return view('chief-docs::index', compact('files'));
    }

    public function show($page)
    {
        $this->assertDocsViewFolderExists();

        $path = resource_path("docs/$page.md");

        abort_unless(File::exists($path), 404);

        $markdown = File::get($path);
        $html = $this->markdown->convert($markdown);

        return view('chief-docs::show', [
            'page' => $page,
            'html' => $html,
        ]);
    }

    private function assertDocsViewFolderExists()
    {
        $docsPath = resource_path('docs');

        if (! File::exists($docsPath)) {
            File::makeDirectory($docsPath, 0755, true);
            File::put($docsPath.'/welcome.md', "# Welcome to the Docs\n\nAdd your markdown files in this folder to document your Chief setup.");
        }
    }
}
