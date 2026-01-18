<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proizvod;
use App\Models\Narudzba;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Direct counts from DB
        $productsCount = Proizvod::count();
        $ordersCount   = Narudzba::count();
        $usersCount    = User::count();

        return view('admin.dashboard', compact(
            'productsCount',
            'ordersCount',
            'usersCount'
        ));
    }
}
