<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kosarica;
use App\Models\Proizvod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CartController extends Controller
{
    // 🧺 Show cart contents
    public function index()
    {
        if (Auth::check()) {
            // Logged in → load from DB
            $cartItems = Kosarica::where('korisnik_id', Auth::id())
                ->with('proizvod')
                ->get();

            // Format to match session-style array
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
            // Guest → load from session
            $cart = session('cart', []);
        }

        return view('cart', compact('cart'));
    }

    // ➕ Add item to cart
    public function add(Request $request, $id)
{
    $product = Proizvod::findOrFail($id);
    $quantity = $request->input('quantity', 1);

    if (Auth::check()) {
        // Logged in user
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
        // Guest cart via session
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

    // ✅ Return AJAX response
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

    // ❌ Remove item from cart
    public function remove($id)
{
    if (Auth::check()) {
        // Remove from DB
        Kosarica::where('korisnik_id', Auth::id())
            ->where('proizvod_id', $id)
            ->delete();

        // Also remove from session (if any)
        $cart = session('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session(['cart' => $cart]);
        }
    } else {
        // Guest only
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);
    }

    return redirect()->route('cart.index')->with('success', 'Proizvod je uklonjen iz košarice.');
}


    // 🔄 Clear all items
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
