<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Thinktomorrow\Locale\Locale;
use Thinktomorrow\Locale\LocaleUrl;

class LanguageSwitcher extends Controller
{
    public function store(Request $request)
    {
        $locale = $request->get('locale') ?: 'nl';

        // Set new locale
        app(Locale::class)->set($locale);
        $previous = LocaleUrl::to(URL::previous(),app(Locale::class)->get());

        return redirect()->to($previous);
    }
}