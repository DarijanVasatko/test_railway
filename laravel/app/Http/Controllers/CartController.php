<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kosarica;
use App\Models\Proizvod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CartController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $cartItems = Kosarica::where('korisnik_id', Auth::id())
                ->with('proizvod')
                ->get();

            $cart = [];
            foreach ($cartItems as $item) {
                $cart[$item->proizvod_id] = [
                    'name' => $item->proizvod->Naziv,
                    'price' => $item->proizvod->Cijena,
                    'image' => $item->proizvod->Slika,
                    'quantity' => $item->kolicina,
                ];
            }
        } else {
            $cart = session('cart', []);
        }

        return view('cart', compact('cart'));
    }

    public function add(Request $request, $id)
{
    $product = Proizvod::findOrFail($id);
    $quantity = $request->input('quantity', 1);

    if (Auth::check()) {
        $cartItem = DB::table('kosarica')
            ->where('korisnik_id', Auth::id())
            ->where('proizvod_id', $id)
            ->first();

        if ($cartItem) {
            DB::table('kosarica')
                ->where('id', $cartItem->id)
                ->update(['kolicina' => $cartItem->kolicina + $quantity]);
        } else {
            DB::table('kosarica')->insert([
                'korisnik_id' => Auth::id(),
                'proizvod_id' => $id,
                'kolicina' => $quantity
            ]);
        }

        $cartCount = DB::table('kosarica')->where('korisnik_id', Auth::id())->sum('kolicina');
    } else {
        $cart = session('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                'product' => $product,
                'quantity' => $quantity
            ];
        }
        session(['cart' => $cart]);
        $cartCount = collect($cart)->sum('quantity');
    }

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'product' => $product->Naziv,
            'quantity' => $quantity,
            'cartCount' => $cartCount,
        ]);
    }

    return redirect()->back()->with('success', 'Proizvod dodan u košaricu!');
}

    public function remove($id)
{
    if (Auth::check()) {
        Kosarica::where('korisnik_id', Auth::id())
            ->where('proizvod_id', $id)
            ->delete();

        $cart = session('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session(['cart' => $cart]);
        }
    } else {
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);
    }

    return redirect()->route('cart.index')->with('success', 'Proizvod je uklonjen iz košarice.');
}


    public function clear()
    {
        if (Auth::check()) {
            Kosarica::where('korisnik_id', Auth::id())->delete();
        } else {
            session()->forget('cart');
        }

        return redirect()->route('cart.index')->with('success', 'Košarica je ispražnjena.');
    }
}
