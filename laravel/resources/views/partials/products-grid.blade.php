<div class="row g-4">
    @forelse($proizvodi as $proizvod)
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 product-card position-relative" 
                 style="transition: all 0.2s ease; cursor: pointer;">

                {{-- 🔗 Full clickable overlay (except the footer) --}}
                <a href="{{ route('proizvod.show', $proizvod->Proizvod_ID) }}" 
                   style="
                        position: absolute;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: calc(100% - 70px); /* leaves footer area free */
                        z-index: 5;
                        cursor: pointer;
                        background: transparent;
                   ">
                </a>

                {{-- 🖼️ Image --}}
                <div class="position-relative" 
                     style="height: 220px; overflow: hidden; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                    <img src="{{ asset($proizvod->Slika) }}" 
                         alt="{{ $proizvod->Naziv }}" 
                         class="w-100 h-100 object-fit-cover product-img"
                         style="transition: transform 0.25s ease;"
                         onmouseover="this.style.transform='scale(1.05)'"
                         onmouseout="this.style.transform='scale(1)'">
                </div>

                {{-- 🧾 Body --}}
                <div class="card-body text-center">
                    <h6 class="fw-bold mb-1">{{ $proizvod->Naziv }}</h6>
                    <p class="text-muted small mb-2">{{ $proizvod->kategorija->ImeKategorija ?? '' }}</p>
                    <h5 class="text-primary fw-bold mb-3">{{ number_format($proizvod->Cijena, 2) }} €</h5>
                </div>

                {{-- 🛒 Add to cart (not covered by overlay) --}}
                <div class="card-footer text-center bg-white border-0 pb-3" style="z-index: 10; position: relative;">
                    <form action="{{ route('cart.add', ['id' => $proizvod->Proizvod_ID]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">
                            <i class="bi bi-cart-plus me-1"></i> Dodaj u košaricu
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <p class="text-center text-muted">Nema proizvoda u ovoj kategoriji.</p>
    @endforelse
</div>

<style>
    .product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }
</style>
