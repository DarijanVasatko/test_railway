<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\UserAddress;


class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user()->load('addresses');
        return view('profile', compact('user'));
    }



    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'ime' => 'required|string|max:255',
            'prezime' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'telefon' => 'nullable|string|max:50',
            'password' => 'nullable|confirmed|min:8',
        ]);

        $user->update([
            'ime' => $request->ime,
            'prezime' => $request->prezime,
            'email' => $request->email,
            'telefon' => $request->telefon,
            'password' => $request->password
                ? Hash::make($request->password)
                : $user->password,
        ]);

        return redirect()->back()->with('success', 'Podaci su uspješno ažurirani!');
    }


    public function destroy()
    {
        $user = Auth::user();
        $user->delete();

        return redirect('/')->with('success', 'Vaš profil je izbrisan.');
    }

    public function addAddress(Request $request)
{
    $request->validate([
        'adresa' => 'required|string|max:255',
        'grad' => 'required|string|max:100',
        'postanski_broj' => 'required|string|max:20',
        'drzava' => 'required|string|max:100',
    ]);

    $user = Auth::user();

    $address = $user->addresses()->create([
        'adresa' => $request->adresa,
        'grad' => $request->grad,
        'postanski_broj' => $request->postanski_broj,
        'drzava' => $request->drzava,
        'is_default' => $request->has('is_default'),
    ]);

    return back()->with('success', 'Adresa je uspješno dodana.');
}



    public function deleteAddress($id)
    {
        $address = UserAddress::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $address->delete();

        return redirect()->back()->with('success', 'Adresa je uspješno obrisana!');
    }

    public function setDefaultAddress($id)
{
    $user = Auth::user();

    // Reset all addresses for this user
    $user->addresses()->update(['is_default' => false]);

    // Mark selected one as default
    $address = $user->addresses()->findOrFail($id);
    $address->update(['is_default' => true]);

    return back()->with('success', 'Zadana adresa je uspješno postavljena.');
}

}
