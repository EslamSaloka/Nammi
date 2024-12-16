<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OrdersExport implements FromView
{
    private $filter = [];

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function view(): View
    {
        $lists = Order::search($this->filter)->latest()->get();
        return view('admin.pages.orders.export',compact('lists'));
    }
}
