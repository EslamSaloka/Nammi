<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AdminsExport implements FromView
{
    private $filter = [];

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function view(): View
    {
        $lists = User::search($this->filter)->where("id","!=",1)->whereHas('roles', function($q) {
                        return $q->where('name', User::TYPE_ADMIN);
                    })->latest()->get();
        return view('admin.pages.admins.export',compact('lists'));
    }
}
