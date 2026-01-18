<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Narudzba;

class DriverOrderController extends Controller
{
    // GET /api/driver/orders
    public function index(Request $request)
{
    $orders = Narudzba::query()
        ->select(['Narudzba_ID','Datum_narudzbe','Ukupni_iznos','Adresa_dostave','Status'])
        ->where('Status', '!=', 'Dostavljeno')
        ->orderByDesc('Datum_narudzbe')
        ->limit(50)
        ->get();

    return response()->json(['data' => $orders]);
}

    // GET /api/driver/orders/{id}
    public function show($id)
{
    $order = Narudzba::with(['detalji.proizvod'])
        ->where('Narudzba_ID', $id)
        ->firstOrFail();

    return response()->json([
        'data' => [
            'id' => $order->Narudzba_ID,
            'status' => $order->Status,
            'total' => $order->Ukupni_iznos,
            'created_at' => $order->Datum_narudzbe,
            'address' => $order->Adresa_dostave,
            'items' => $order->detalji->map(function ($d) {
                return [
                    'product_id' => $d->Proizvod_ID,
                    'name' => optional($d->proizvod)->Naziv,
                    'qty' => $d->Kolicina,
                    'price' => optional($d->proizvod)->Cijena,
                ];
            })->values(),
        ]
    ]);
}
public function markDelivered($id)
{
    $order = Narudzba::where('Narudzba_ID', $id)->firstOrFail();

    $order->Status = 'Dostavljeno'; // choose your exact label
    $order->save();

    return response()->json([
        'ok' => true,
        'message' => 'Order marked as delivered.',
        'status' => $order->Status,
    ]);
}

public function markNotDelivered($id)
{
    $order = Narudzba::where('Narudzba_ID', $id)->firstOrFail();

    $order->Status = 'Neuspjela dostava'; // choose your exact label
    $order->save();

    return response()->json([
        'ok' => true,
        'message' => 'Order marked as not delivered.',
        'status' => $order->Status,
    ]);
}
}
