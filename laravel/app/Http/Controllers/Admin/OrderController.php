<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Narudzba;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected array $statuses = [
        'U obradi',
        'Poslano',
        'Isporučeno',
        'Narudžba završena',
    ];

    public function index()
    {
        $orders = Narudzba::with('kupac')
            ->orderByDesc('Datum_narudzbe')
            ->paginate(20);

        $statuses = $this->statuses;

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    public function show(Narudzba $order)
    {
        $order->load(['kupac', 'detalji.proizvod', 'nacinPlacanja']);

        $statuses = $this->statuses;

        return view('admin.orders.show', compact('order', 'statuses'));
    }

    public function update(Request $request, Narudzba $order)
    {
        $data = $request->validate([
            'Status' => ['required', 'string', 'in:' . implode(',', $this->statuses)],
        ]);

        $order->update([
            'Status' => $data['Status'],
        ]);

        return back()->with('success', 'Status narudžbe je ažuriran.');
    }
}
