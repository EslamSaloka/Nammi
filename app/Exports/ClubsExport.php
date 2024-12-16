<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ClubsExport implements FromView
{
    private $filter = [];

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function view(): View
    {
        $lists = User::search($this->filter)->whereHas('roles', function($q) {
                        return $q->where('name', User::TYPE_CLUB);
                    })->whereNotNull("accepted_at")->latest()->get();
        return view('admin.pages.clubs.export',compact('lists'));
    }
}
