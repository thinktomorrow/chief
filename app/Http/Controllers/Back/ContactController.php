<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->get();

        return view('back.contacts.index',compact('contacts'));
    }
}