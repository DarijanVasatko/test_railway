<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Kosarica;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();

    $user = $request->user();
    $sessionCart = session('cart', []);

    // 🔹 Save session cart into DB
    foreach ($sessionCart as $productId => $item) {
        $row = Kosarica::where('korisnik_id', $user->id)
            ->where('proizvod_id', $productId)
            ->first();

        if ($row) {
            $row->kolicina += $item['quantity'];
            $row->save();
        } else {
            Kosarica::create([
                'korisnik_id' => $user->id,
                'proizvod_id' => $productId,
                'kolicina' => $item['quantity'],
            ]);
        }
    }

    // 🔹 Load merged DB cart into session
    $dbCart = Kosarica::where('korisnik_id', $user->id)
        ->with('proizvod')
        ->get()
        ->mapWithKeys(function ($item) {
            return [
                $item->proizvod_id => [
                    'name' => $item->proizvod->Naziv,
                    'price' => $item->proizvod->Cijena,
                    'quantity' => $item->kolicina,
                    'image' => $item->proizvod->Slika,
                ]
            ];
        })->toArray();

    session(['cart' => $dbCart]);

    return redirect()->intended('/');
}


    /**
     * Destroy an authenticated session.
     */
   public function destroy(Request $request): RedirectResponse
{
    $user = $request->user();
    if ($user) {
        $cart = session('cart', []);
        foreach ($cart as $productId => $item) {
            $row = Kosarica::updateOrCreate(
                ['korisnik_id' => $user->id, 'proizvod_id' => $productId],
                ['kolicina' => $item['quantity']]
            );
        }
    }

    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
}
}
