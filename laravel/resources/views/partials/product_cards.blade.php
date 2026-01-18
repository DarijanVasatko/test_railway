@foreach($proizvodi as $proizvod)
    <div class="col-md-4 col-sm-6 mb-4">
        <div class="card border-0 shadow-sm h-100" style="cursor:pointer;"
             onclick="window.location='{{ route('proizvod.show', $proizvod->Proizvod_ID) }}'">
            <div class="position-relative" style="height: 220px; overflow: hidden; border-radius: 1rem 1rem 0 0;">
                <img src="{{ $product->slika_url }}" alt="{{ $proizvod->Naziv }}"
                     class="w-100 h-100 object-fit-cover" style="transition: transform .3s ease;"
                     onmouseover="this.style.transform='scale(1.05)'"
                     onmouseout="this.style.transform='scale(1)'">
            </div>
            <div class="card-body text-center">
                <h6 class="fw-bold mb-1">{{ $proizvod->Naziv }}</h6>
                <p class="text-muted small">{{ $proizvod->kategorija->ImeKategorija ?? '' }}</p>
                <h5 class="text-primary fw-bold">{{ number_format($proizvod->Cijena, 2) }} €</h5>
            </div>
            <div class="card-footer text-center bg-white border-0">
                <form action="{{ route('cart.add', ['id' => $proizvod->Proizvod_ID]) }}" method="POST"
                      class="js-add-to-cart d-inline-block" data-product-name="{{ $proizvod->Naziv }}"
                      onclick="event.stopPropagation();">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 js-add-to-cart-btn">
                        <i class="bi bi-cart-plus me-1"></i> Dodaj u košaricu
                    </button>
                </form>
            </div>
        </div>
    </div>
@endforeach

@if($proizvodi->isEmpty())
    <p class="text-center text-muted">Nema pronađenih proizvoda.</p>
@endif
