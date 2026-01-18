<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Country;

class OnboardingController extends Controller
{
   public function show()
{
    $user = Auth::user();

    // If the user already has an address → skip onboarding
    if ($user->addresses()->exists()) {
        return redirect()->route('dashboard');
    }

    $countries = Country::orderBy('name')->get();
    return view('auth.onboarding', compact('countries'));
}

    public function store(Request $request)
    {
        $request->validate([
            'ime' => 'required|string|max:255',
            'prezime' => 'required|string|max:255',
            'telefon' => 'nullable|string|max:30',
            'adresa' => 'required|string|max:255',
            'grad' => 'required|string|max:100',
            'postanski_broj' => 'required|string|max:20',
            'drzava' => 'required|string|max:100',
        ]);

        $user = Auth::user();

        // Update user basic info
        $user->update([
            'ime' => $request->ime,
            'prezime' => $request->prezime,
            'telefon' => $request->telefon,
        ]);

        // Save default address
        $user->addresses()->create([
            'adresa' => $request->adresa,
            'grad' => $request->grad,
            'postanski_broj' => $request->postanski_broj,
            'drzava' => $request->drzava,
            'is_default' => true,
        ]);

        return redirect()->route('dashboard')->with('success', 'Profil uspješno dovršen!');
    }
}
