<?php

namespace App\Exports;

use App\Models\Activity;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DuesExport implements FromView
{
    private $lists = [];

    public function __construct($lists)
    {
        $this->lists = $lists;
    }

    public function view(): View
    {
        return view('admin.pages.dues.export',[
            "lists" => $this->lists
        ]);
    }
}
