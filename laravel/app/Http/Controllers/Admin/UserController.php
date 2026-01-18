<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('ime')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('addresses'); // ako imaš relaciju

        return view('admin.users.show', compact('user'));
    }

    // (Ostale metode resource kontrolera ti ne trebaju, pa ih možeš obrisati)
}
