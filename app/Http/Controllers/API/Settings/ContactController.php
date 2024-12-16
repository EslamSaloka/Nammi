<?php

namespace App\Http\Controllers\API\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Contacts\ContactStoreRequest;
use App\Models\Contact;

class ContactController extends Controller
{
    public function store(ContactStoreRequest $request) {
        Contact::create($request->validated());
        return (new \App\Support\API)->isOk(__("Thanks for this message"))->build();
    }
}
