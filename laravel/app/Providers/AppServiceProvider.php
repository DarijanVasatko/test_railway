<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Kategorija;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ✅ Global view composer for categories and cart count
        View::composer('*', function ($view) {
            // 🧭 Share categories globally
            $view->with('kategorije', Kategorija::all());
            $view->with('categoryId', null);

            // 🛒 Share cart count globally
            if (Auth::check()) {
                // Logged-in user → count from DB
                $cartCount = DB::table('kosarica')
                    ->where('korisnik_id', Auth::id())
                    ->sum('kolicina');
            } else {
                // Guest → count from session
                $cart = session('cart', []);
                $cartCount = collect($cart)->sum('quantity');
            }

            $view->with('cartCount', $cartCount);
        });
    }
}
