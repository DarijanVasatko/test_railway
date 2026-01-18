<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\Proizvod;
use App\Models\Kategorija;

class ProizvodController extends Controller
{
    public function home()
    {
        $orderColumn = Schema::hasColumn('proizvod', 'created_at') ? 'created_at' : 'Proizvod_ID';

        $proizvodi   = Proizvod::orderByDesc($orderColumn)->take(12)->get();
        $kategorije  = Kategorija::all();

        return view('index', [
            'proizvodi'  => $proizvodi,
            'kategorije' => $kategorije,
        ]);
    }

    public function list(Request $request)
    {
        [$proizvodi, $kategorije] = $this->queryProducts($request);
        return view('category', [
            'proizvodi'  => $proizvodi,
            'kategorije' => $kategorije,
            'categoryId' => null,
        ]);
    }

    public function kategorija(Request $request, $id)
    {
        [$proizvodi, $kategorije] = $this->queryProducts($request, (int)$id);
        
        return view('category', [
            'proizvodi'  => $proizvodi,
            'kategorije' => $kategorije,
            'categoryId' => (int)$id,
            
        ]);
        
    }

    public function ajaxSearch(Request $request)
    {
        $categoryId = $request->integer('categoryId') ?: null;
        [$proizvodi, $kategorije] = $this->queryProducts($request, $categoryId);

        $html = view('partials.products-grid', compact('proizvodi'))->render();
        $pagination = view('partials.products-pagination', compact('proizvodi'))->render();

        return response()->json([
            'html'       => $html,
            'pagination' => $pagination,
        ]);
    }

    private function queryProducts(Request $request, ?int $categoryId = null): array
    {
        $query = Proizvod::query();

        if (!is_null($categoryId)) {
            $query->where('kategorija', $categoryId);
        }

        // Search
        if ($search = $request->string('search')->toString()) {
            $query->where('Naziv', 'like', "%{$search}%");
        }

        // Determine if 'created_at' exists
        $hasCreatedAt = Schema::hasColumn('proizvod', 'created_at');
        $defaultOrderColumn = $hasCreatedAt ? 'created_at' : 'Proizvod_ID';

        // Sorting
        switch ($request->string('sort')->toString()) {
            case 'price_asc':
                $query->orderBy('Cijena', 'asc');  break;
            case 'price_desc':
                $query->orderBy('Cijena', 'desc'); break;
            case 'name_asc':
                $query->orderBy('Naziv', 'asc');   break;
            case 'name_desc':
                $query->orderBy('Naziv', 'desc');  break;
            case 'newest':
                $query->orderBy($defaultOrderColumn, 'desc'); break;
            default:
                $query->orderBy($defaultOrderColumn, 'desc');
        }

        $proizvodi = $query->paginate(9)->withQueryString();
        $kategorije = Kategorija::all();

        return [$proizvodi, $kategorije];
    }

    public function show($id)
{
    $proizvod = Proizvod::with('kategorija')->findOrFail($id);
   $slicni = Proizvod::where('kategorija', $proizvod->id_kategorija)
    ->where('Proizvod_ID', '!=', $proizvod->Proizvod_ID)
    ->limit(4)
    ->get();


    return view('product', [
        'proizvod' => $proizvod,
        'slicni' => $slicni
    ]);
}

}
