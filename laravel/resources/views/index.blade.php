@extends('layouts.app')

@section('title', 'TechShop - Najbolja tehnologija u Hrvatskoj')

@section('content')

<!-- 🦾 Hero Section -->
<section class="hero-section bg-dark text-white text-center py-5">
    <div class="container py-5">
        <h1 class="display-4 fw-bold mb-3">Dobrodošli u TechShop</h1>
        <p class="lead mb-4">Najbolja tehnologija po povoljnim cijenama. Istraži, usporedi i pronađi savršen uređaj.</p>
        <a href="{{ route('proizvodi.index') }}" class="btn btn-primary btn-lg rounded-pill px-4">
            <i class="bi bi-shop me-2"></i> Pregledaj proizvode
        </a>
    </div>
</section>

<!-- 💡 Popularne kategorije -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Popularne kategorije</h2>
        <div class="row g-4">
            @foreach($kategorije->take(4) as $kat)
                <div class="col-6 col-md-3">
                    <div class="card h-100 border-0 shadow-sm hover-card text-center rounded-4">
                        <div class="card-body py-4">
                            <div class="icon-wrapper bg-primary-subtle text-primary mx-auto mb-3 
                                        d-flex align-items-center justify-content-center rounded-circle"
                                 style="width:60px; height:60px;">
                                <i class="bi bi-grid-3x3-gap" style="font-size:1.6rem;"></i>
                            </div>
                            <h6 class="fw-semibold">{{ $kat->ImeKategorija }}</h6>
                            <a href="{{ url('/kategorija/'.$kat->id_kategorija) }}" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Izdvojeni proizvodi -->
<section id="featured-products" class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Izdvojeni proizvodi</h2>

        <!-- Scrollable row -->
        <div id="product-row" class="d-flex gap-4 overflow-hidden pb-3"
             style="white-space: nowrap; scroll-behavior: smooth;">
            @foreach ($proizvodi as $product)
                <div class="card product-card flex-shrink-0 border-0 shadow-sm"
                     style="width: 250px; border-radius: 1rem; overflow: hidden;">

                    <!-- Image as link -->
                    <a href="{{ route('proizvod.show', $product->Proizvod_ID) }}" class="text-decoration-none">
                        <div class="position-relative" style="height: 200px; overflow: hidden;">
                            <img src="{{ asset($product->Slika) }}" 
                                 alt="{{ $product->Naziv }}"
                                 class="w-100 h-100 object-fit-cover"
                                 style="transition: transform 0.4s ease;">
                        </div>
                    </a>

                    <!-- Product Info -->
                    <div class="card-body text-center">
                        <h6 class="fw-bold mb-1">{{ $product->Naziv }}</h6>
                        <p class="text-muted small mb-2">{{ Str::limit($product->Opis, 60) }}</p>
                        <h5 class="text-primary fw-bold mb-3">{{ number_format($product->Cijena, 2) }} €</h5>
                    </div>

                    <!-- Add to Cart -->
                    <div class="card-footer text-center bg-white border-0 pb-3">
                        <form action="{{ route('cart.add', ['id' => $product->Proizvod_ID]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">
                                <i class="bi bi-cart-plus me-1"></i> Dodaj u košaricu
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- “View all” button -->
        <div class="text-center mt-5">
            <a href="{{ route('proizvodi.index') }}" class="btn btn-outline-primary btn-lg rounded-pill px-4">
                Pogledaj sve proizvode i kategorije
            </a>
        </div>
    </div>
</section>



<style>
/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #000000ff, #000000ff);
    color: #fff;
}

/* Category cards */
.hover-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.15);
}

/* Product cards */
.product-card:hover img {
    transform: scale(1.07);
}
.product-card {
    transition: transform 0.25s ease;
}
.product-card:hover {
    transform: translateY(-4px);
}

/* Hide scrollbar */
#product-row::-webkit-scrollbar { display: none; }
#product-row { -ms-overflow-style: none; scrollbar-width: none; }

/* Typography & visuals */
h2 { font-weight: 700; }
.icon-wrapper { background-color: rgba(13,110,253,0.1); }
</style>

@endsection
