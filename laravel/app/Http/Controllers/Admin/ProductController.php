<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proizvod;
use App\Models\Kategorija;
use App\Models\TipProizvoda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $category = $request->input('category');

        $query = Proizvod::with('kategorija');

        if ($q) {
            $query->where(function ($qq) use ($q) {
                $qq->where('Naziv', 'LIKE', '%' . $q . '%')
                    ->orWhere('sifra', 'LIKE', '%' . $q . '%');
            });
        }

        if ($category) {
            $query->where('kategorija', $category);
        }

        // nema paginacije – vraćamo sve
        $products = $query->orderByDesc('Proizvod_ID')->get();

        $categories = Kategorija::orderBy('ImeKategorija')->get();
        $types = TipProizvoda::orderBy('naziv_tip')->get();

        $defaultSifra = $this->generateRandomSifra();

        return view('admin.products.index', compact('products', 'categories', 'types', 'defaultSifra'));
    }

    public function create()
    {
        // nećeš ovo koristiti jer radimo modal,
        // ali neka ostane da resource ruta bude kompletna
        $categories = Kategorija::orderBy('ImeKategorija')->get();
        $types = TipProizvoda::orderBy('naziv_tip')->get();

        return view('admin.products.create', compact('categories', 'types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sifra' => ['required', 'string', 'max:50', 'unique:proizvod,sifra'],
            'Naziv' => ['required', 'string', 'max:100'],
            'Opis' => ['nullable', 'string'],
            'KratkiOpis' => ['nullable', 'string'],
            'Cijena' => ['required', 'numeric', 'min:0'],
            'kategorija' => ['required', 'exists:kategorija,id_kategorija'],
            'tip_id' => ['nullable', 'exists:tip_proizvoda,id_tip'],
            'StanjeNaSkladistu' => ['required', 'integer', 'min:0'],
            'Slika' => ['nullable', 'image'],
        ]);

        if ($request->hasFile('Slika')) {
            $file = $request->file('Slika');

            // store on "public" disk -> storage/app/public/uploads/products
            $path = $file->store('uploads/products', 'public');

            // store relative path in DB, e.g. "uploads/products/xxx.jpg"
            $data['Slika'] = $path;
        }



        Proizvod::create($data);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Proizvod je uspješno dodan.');
    }

    public function edit(Proizvod $product)
    {
        $categories = Kategorija::orderBy('ImeKategorija')->get();
        $types = TipProizvoda::orderBy('naziv_tip')->get();

        return view('admin.products.edit', compact('product', 'categories', 'types'));
    }

    public function update(Request $request, Proizvod $product)
    {
        $data = $request->validate([
            'sifra' => [
                'required',
                'string',
                'max:50',
                // unique: table, column, ignore_value, ignore_column
                'unique:proizvod,sifra,' . $product->Proizvod_ID . ',Proizvod_ID',
            ],
            'Naziv' => ['required', 'string', 'max:100'],
            'Opis' => ['nullable', 'string'],
            'KratkiOpis' => ['nullable', 'string'],
            'Cijena' => ['required', 'numeric', 'min:0'],
            'kategorija' => ['required', 'exists:kategorija,id_kategorija'],
            'tip_id' => ['nullable', 'exists:tip_proizvoda,id_tip'],
            'StanjeNaSkladistu' => ['required', 'integer', 'min:0'],
            'Slika' => ['nullable', 'image'],
        ]);

        if ($request->hasFile('Slika')) {

            // delete old file if exists
            if ($product->Slika) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->Slika);
            }

            $file = $request->file('Slika');
            $path = $file->store('uploads/products', 'public');
            $data['Slika'] = $path;
        }



        $product->update($data);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Proizvod je ažuriran.');
    }

    public function destroy(Proizvod $product)
    {
        if ($product->Slika) {
            Storage::disk('public')->delete($product->Slika);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Proizvod je obrisan.');
    }

    private function generateRandomSifra(): string
    {
        // e.g. ART-123456
        do {
            $candidate = 'ART-' . random_int(100000, 999999);
        } while (Proizvod::where('sifra', $candidate)->exists());

        return $candidate;
    }
}