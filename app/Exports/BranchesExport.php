<?php

namespace App\Exports;

use App\Models\Club\Branch;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BranchesExport implements FromView
{
    private $filter = [];

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function view(): View
    {
        $lists = Branch::search($this->filter)->latest()->get();
        return view('admin.pages.clubs.branches.export',compact('lists'));
    }
}
