<?php

namespace App\Exports;

use App\Models\Contact;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ContactsExport implements FromView
{
    private $filter = [];

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function view(): View
    {
        $lists = Contact::search($this->filter)->latest()->get();
        return view('admin.pages.contacts.export',compact('lists'));
    }
}
