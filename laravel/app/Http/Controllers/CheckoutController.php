<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Kosarica;
use App\Models\Proizvod;
use App\Models\UserAddress;
use App\Models\NacinPlacanja;
use App\Models\Narudzba;
use App\Models\DetaljiNarudzbe;

class CheckoutController extends Controller
{
    /**
     * Show checkout page.
     */
    public function index()
    {
        $user = Auth::user();

        // Get addresses (delivery)
        $addresses = UserAddress::where('user_id', $user->id)->get();

        // Get payment methods
        $paymentMethods = NacinPlacanja::all();

        // Get items from the user's cart
        $cartItems = Kosarica::where('korisnik_id', $user->id)
            ->with('proizvod')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Vaša košarica je prazna.');
        }

        // Calculate total
        $total = $cartItems->sum(function ($item) {
            return $item->proizvod->Cijena * $item->kolicina;
        });

        return view('checkout', compact('addresses', 'paymentMethods', 'cartItems', 'total'));
    }

    /**
     * Handle order submission.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        Log::info('CheckoutController@store called', ['user_id' => $user->id ?? null]);

        // ✅ Validation (use Validator to log failures instead of silent redirect)
        $validator = Validator::make($request->all(), [
            'adresa_dostave' => 'required|string|max:255',
            'nacin_placanja_id' => 'required|exists:nacin_placanja,NacinPlacanja_ID',
        ]);

        if ($validator->fails()) {
            Log::warning('Checkout validation failed', ['errors' => $validator->errors()->toArray(), 'input' => $request->all()]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // Get cart items
        $cartItems = Kosarica::where('korisnik_id', $user->id)
            ->with('proizvod')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Vaša košarica je prazna.');
        }

        // Calculate total price
        $total = $cartItems->sum(function ($item) {
            return $item->proizvod->Cijena * $item->kolicina;
        });

        DB::beginTransaction();

        try {
            Log::info('Attempting to create order', ['user' => $user->id ?? null, 'total' => $total]);
            // ✅ Create order (use column names that match the migration / model)
            $order = Narudzba::create([
                'Kupac_ID' => $user->id,
                'NacinPlacanja_ID' => $validated['nacin_placanja_id'],
                'Datum_narudzbe' => now()->toDateString(),
                'Ukupni_iznos' => $total,
            ]);

            Log::info('Order created', ['Narudzba_ID' => $order->Narudzba_ID ?? null]);

            // ✅ Create order details
            foreach ($cartItems as $item) {
                DetaljiNarudzbe::create([
                    'Narudzba_ID' => $order->Narudzba_ID,
                    'Proizvod_ID' => $item->proizvod_id,
                    'Kolicina' => $item->kolicina,
                ]);

                // Optionally decrease stock
                $item->proizvod->decrement('StanjeNaSkladistu', $item->kolicina);
            }

            // ✅ Clear cart
            Kosarica::where('korisnik_id', $user->id)->delete();

            DB::commit();

            Log::info('Checkout completed, clearing cart and redirecting', ['user' => $user->id ?? null]);

            return redirect()->route('orders.index')->with('success', 'Narudžba je uspješno potvrđena!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout failed', ['exception' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Došlo je do pogreške: ' . $e->getMessage());
        }
    }
}
