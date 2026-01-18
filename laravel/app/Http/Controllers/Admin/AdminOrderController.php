<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Narudzba;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Narudzba::with('kupac')->orderByDesc('Narudzba_ID')->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Narudzba $order)
    {
        $order->load(['kupac','detalji.proizvod','nacinPlacanja']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Narudzba $order)
    {
        $request->validate([
            'Status' => 'required|string'
        ]);

        $order->update(['Status' => $request->Status]);

        return back()->with('success', 'Status ažuriran.');
    }
}
