<?php

namespace App\Exports;

use App\Models\Activity;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ActivitiesExport implements FromView
{
    private $filter = [];

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function view(): View
    {
        $lists = Activity::search($this->filter)->latest()->get();
        return view('admin.pages.clubs.activities.export',compact('lists'));
    }
}
