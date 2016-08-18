<?php

namespace App\Http\Controllers;

use BNP\Faqs\Faq;
use BNP\Services\Module;
use BNP\Testimonials\Testimonial;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Session;
use Symfony\Component\DomCrawler\Crawler;

class SearchController extends Controller
{
    public function show(Request $request)
    {
        // Full text search
        $search_term = $text = $request->get('for');

        $modules = Module::search($text);
        $testimonials = Testimonial::search($text);
        $faqs = Faq::search($text);

        $amount = count($modules) + count($testimonials) + count($faqs);

        $data = [
            'modules' => $modules,
            'testimonials' => $testimonials,
            'faqs' => $faqs,
        ];

        return view('pages.search',compact('data','amount','search_term'));
    }

}