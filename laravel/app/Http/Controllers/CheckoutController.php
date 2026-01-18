<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use App\Models\Kosarica;
use App\Models\UserAddress;
use App\Models\NacinPlacanja;
use App\Models\Narudzba;
use App\Models\DetaljiNarudzbe;
use App\Models\Payment;
use App\Mail\OrderReceiptMail;

class CheckoutController extends Controller
{    public function index()
    {
        $user = Auth::user();

        $addresses = UserAddress::where('user_id', $user->id)->get();
        $paymentMethods = NacinPlacanja::all();

        $cartItems = Kosarica::where('korisnik_id', $user->id)
            ->with('proizvod')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Vaša košarica je prazna.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->proizvod->Cijena * $item->kolicina;
        });

        return view('checkout', compact('addresses', 'paymentMethods', 'cartItems', 'total'));
    }


    public function store(Request $request)
    {
        $user = Auth::user();

        Log::info('CheckoutController@store called', ['user_id' => $user->id ?? null]);

        $validator = Validator::make($request->all(), [
            'adresa_dostave'    => 'required|string|max:255',
            'nacin_placanja_id' => 'required|exists:nacin_placanja,NacinPlacanja_ID',
        ]);

        if ($validator->fails()) {
            Log::warning('Checkout validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input'  => $request->all(),
            ]);

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $paymentMethod = NacinPlacanja::findOrFail($validated['nacin_placanja_id']);
        $isCardPayment = ((int) $paymentMethod->NacinPlacanja_ID === 7);

        $cartItems = Kosarica::where('korisnik_id', $user->id)
            ->with('proizvod')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Vaša košarica je prazna.');
        }
        $total = $cartItems->sum(function ($item) {
            return $item->proizvod->Cijena * $item->kolicina;
        });

        DB::beginTransaction();

        try {
            Log::info('Attempting to create order', [
                'user'  => $user->id ?? null,
                'total' => $total,
            ]);
            $order = Narudzba::create([
                'Kupac_ID'          => $user->id,
                'NacinPlacanja_ID'  => $paymentMethod->NacinPlacanja_ID,
                'Datum_narudzbe'    => now()->format('Y-m-d H:i:s'),
                'Ukupni_iznos'      => $total,
                'Adresa_dostave'    => $validated['adresa_dostave'],
                'Status'            => 'U obradi',
            ]);

            Log::info('Order created', ['Narudzba_ID' => $order->Narudzba_ID ?? null]);

            foreach ($cartItems as $item) {
                DetaljiNarudzbe::create([
                    'Narudzba_ID' => $order->Narudzba_ID,
                    'Proizvod_ID' => $item->proizvod_id,
                    'Kolicina'    => $item->kolicina,
                ]);

                $item->proizvod->decrement('StanjeNaSkladistu', $item->kolicina);
            }

            Kosarica::where('korisnik_id', $user->id)->delete();

            if ($isCardPayment) {
                $payment = Payment::create([
                    'narudzba_id' => $order->Narudzba_ID,
                    'provider'    => 'fakepay',
                    'reference'   => 'TS-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6)),
                    'amount'      => $order->Ukupni_iznos,
                    'currency'    => 'EUR',
                    'status'      => 'pending',
                ]);

                DB::commit();

                Log::info('Checkout completed (card), redirecting to FakePay', [
                    'user'        => $user->id ?? null,
                    'Narudzba_ID' => $order->Narudzba_ID ?? null,
                    'Payment_ID'  => $payment->id ?? null,
                ]);

                return redirect()->route('payments.fakepay', $payment->id);
            } else {
                $order->Status = 'Čeka plaćanje pouzećem';
                $order->save();

                DB::commit();

                $email = optional($order->user)->email;
                if ($email) {
                    Mail::to($email)->send(new OrderReceiptMail($order));
                }

                Log::info('Checkout completed (COD), redirecting to orders.show', [
                    'user'        => $user->id ?? null,
                    'Narudzba_ID' => $order->Narudzba_ID ?? null,
                ]);

                return redirect()
                    ->route('orders.show', $order->Narudzba_ID)
                    ->with('success', 'Narudžba je zaprimljena. Plaćanje pri pouzeću.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout failed', ['exception' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Došlo je do pogreške: ' . $e->getMessage());
        }
    }
}
