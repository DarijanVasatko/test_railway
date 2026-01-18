@extends('layouts.admin')

@section('title', 'Proizvodi — TechShop Admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-box-seam me-2"></i> Proizvodi
            </h2>
            <p class="text-muted mb-0">
                Pronađeno proizvoda: {{ $products->count() }}
            </p>
        </div>

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productCreateModal">
            <i class="bi bi-plus-lg me-1"></i> Novi proizvod
        </button>


    </div>


    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                {{-- Search term --}}
                <div class="col-md-5">
                    <label class="form-label small text-muted">Pretraži</label>
                    <input type="text" name="q" class="form-control" placeholder="Naziv ili šifra proizvoda..."
                        value="{{ request('q') }}">
                </div>

                {{-- Category filter
                <div class="col-md-4">
                    <label class="form-label small text-muted">Kategorija</label>
                    <select name="category" class="form-select">
                        <option value="">Sve kategorije</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id_kategorija }}" @selected(request('category')==$cat->id_kategorija)>
                            {{ $cat->Naziv }}
                        </option>
                        @endforeach
                    </select>
                </div>
                --}}
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-outline-secondary flex-fill" type="submit">
                        <i class="bi bi-search me-1"></i> Filtriraj
                    </button>

                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-light border flex-fill">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- 📋 ENDLESS TABLE (no pagination) --}}
    <div class="card shadow-sm border-0">
        <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light sticky-top">
                    <tr>
                        <th style="width: 70px;">ID</th>
                        <th style="width: 80px;">Slika</th>
                        <th>Naziv</th>
                        <th>Šifra</th>
                        <th>Kategorija</th>
                        <th class="text-end">Cijena</th>
                        <th class="text-center">Zaliha</th>
                        <th class="text-end" style="width: 160px;">Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>#{{ $product->Proizvod_ID }}</td>

                            {{-- Thumbnail --}}
                            <td>
                                @if($product->Slika)
                                    <img src="{{ asset($product->Slika) }}" alt="{{ $product->Naziv }}" class="rounded"
                                        style="width: 56px; height: 56px; object-fit: cover;">
                                @else
                                    <div class="bg-light border rounded d-flex align-items-center justify-content-center"
                                        style="width: 56px; height: 56px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>

                            {{-- Name + short description --}}
                            <td>
                                <div class="fw-semibold">{{ $product->Naziv }}</div>
                                @if($product->KratkiOpis)
                                    <div class="small text-muted">
                                        {{ \Illuminate\Support\Str::limit($product->KratkiOpis, 60) }}
                                    </div>
                                @endif
                            </td>

                            {{-- Code --}}
                            <td>
                                <span class="badge bg-secondary-subtle text-dark border">
                                    {{ $product->sifra }}
                                </span>
                            </td>

                            {{-- Category name --}}
                            <td>
                                {{ $product->kategorija->ImeKategorija ?? '—' }}

                            </td>

                            {{-- Price --}}
                            <td class="text-end">
                                {{ number_format($product->Cijena, 2, ',', '.') }} €
                            </td>

                            {{-- Stock --}}
                            <td class="text-center">
                                @php $stock = $product->StanjeNaSkladistu; @endphp

                                @if($stock <= 0)
                                    <span class="badge bg-danger">Nema</span>
                                @elseif($stock <= 5)
                                    <span class="badge bg-warning text-dark">Niska ({{ $stock }})</span>
                                @else
                                    <span class="badge bg-success">Na zalihi ({{ $stock }})</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="text-end">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                    class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-pencil-square me-1"></i> Uredi
                                </a>

                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Jesi siguran da želiš obrisati ovaj proizvod?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash me-1"></i> Obriši
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                Nema proizvoda za zadane kriterije.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL: Novi proizvod --}}
<div class="modal fade" id="productCreateModal" tabindex="-1" aria-labelledby="productCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="productCreateLabel">
                        <i class="bi bi-plus-lg me-2"></i> Dodaj novi proizvod
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zatvori"></button>
                </div>

                <div class="modal-body">
                    {{-- Šifra --}}
                    <div class="mb-3">
                        <label class="form-label">Šifra</label>
                        <input type="text" name="sifra"
                               class="form-control @error('sifra') is-invalid @enderror"
                               value="{{ old('sifra') }}" required>
                        @error('sifra')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Naziv --}}
                    <div class="mb-3">
                        <label class="form-label">Naziv</label>
                        <input type="text" name="Naziv"
                               class="form-control @error('Naziv') is-invalid @enderror"
                               value="{{ old('Naziv') }}" required>
                        @error('Naziv')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kratki opis --}}
                    <div class="mb-3">
                        <label class="form-label">Kratki opis</label>
                        <textarea name="KratkiOpis"
                                  class="form-control @error('KratkiOpis') is-invalid @enderror"
                                  rows="2">{{ old('KratkiOpis') }}</textarea>
                        @error('KratkiOpis')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Opis --}}
                    <div class="mb-3">
                        <label class="form-label">Detaljan opis</label>
                        <textarea name="Opis"
                                  class="form-control @error('Opis') is-invalid @enderror"
                                  rows="4">{{ old('Opis') }}</textarea>
                        @error('Opis')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        {{-- Cijena --}}
                        <div class="col-md-4">
                            <label class="form-label">Cijena (€)</label>
                            <input type="number" step="0.01" min="0"
                                   name="Cijena"
                                   class="form-control @error('Cijena') is-invalid @enderror"
                                   value="{{ old('Cijena') }}" required>
                            @error('Cijena')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Zaliha --}}
                        <div class="col-md-4">
                            <label class="form-label">Stanje na skladištu</label>
                            <input type="number" min="0"
                                   name="StanjeNaSkladistu"
                                   class="form-control @error('StanjeNaSkladistu') is-invalid @enderror"
                                   value="{{ old('StanjeNaSkladistu', 0) }}" required>
                            @error('StanjeNaSkladistu')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kategorija --}}
                        <div class="col-md-4">
                            <label class="form-label">Kategorija</label>
                            <select name="kategorija"
                                    class="form-select @error('kategorija') is-invalid @enderror"
                                    required>
                                <option value="">Odaberi kategoriju...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id_kategorija }}"
                                        @selected(old('kategorija') == $cat->id_kategorija)>
                                        {{ $cat->ImeKategorija }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategorija')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        {{-- Tip proizvoda --}}
                        <div class="col-md-6">
                            <label class="form-label">Tip proizvoda</label>
                            <select name="tip_id"
                                    class="form-select @error('tip_id') is-invalid @enderror">
                                <option value="">Bez tipa</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id_tip }}"
                                        @selected(old('tip_id') == $type->id_tip)>
                                        {{ $type->naziv_tip }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tip_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Slika --}}
                        <div class="col-md-6">
                            <label class="form-label">Slika proizvoda</label>
                            <input type="file"
                                   name="Slika"
                                   class="form-control @error('Slika') is-invalid @enderror"
                                   accept="image/*">
                            @error('Slika')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>{{-- /.modal-body --}}

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Zatvori
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Spremi proizvod
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

