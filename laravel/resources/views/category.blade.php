@extends('layouts.app')

@php
    // Always resolve categoryId from route (bulletproof)
    $categoryId = $categoryId ?? (int) request()->route('id');
@endphp

@section('title',
    $categoryId
        ? 'TechShop - ' . ($kategorije->firstWhere('id_kategorija', $categoryId)->ImeKategorija ?? '')
        : 'TechShop - Svi proizvodi'
)

@section('content')
<div class="container py-5" id="products-page"
     data-ajax-url="{{ route('proizvodi.search') }}"
     data-category-id="{{ $categoryId }}">

    <div class="row">
        <!-- Sidebar kategorije -->
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3 text-center text-primary">Kategorije</h5>

                    <ul class="list-group list-group-flush">

                        {{-- Sve kategorije --}}
                        <li class="list-group-item border-0 p-0">
                            <a href="{{ route('proizvodi.index', request()->only(['search','sort'])) }}"
                               class="d-block px-3 py-2 rounded text-decoration-none
                               {{ empty($categoryId) ? 'bg-primary text-white fw-bold' : 'text-dark' }}">
                                Sve kategorije
                            </a>
                        </li>

                        {{-- Pojedinačne kategorije --}}
                        @foreach($kategorije as $kat)
                            @php
                                $active = (int)$categoryId === (int)$kat->id_kategorija;
                            @endphp

                            <li class="list-group-item border-0 p-0">
                                <a href="{{ route('proizvodi.kategorija', array_merge(['id' => $kat->id_kategorija], request()->only(['search','sort']))) }}"
                                   class="d-block px-3 py-2 rounded text-decoration-none
                                   {{ $active ? 'bg-primary text-white fw-bold' : 'text-dark' }}">
                                    {{ $kat->ImeKategorija }}
                                </a>
                            </li>
                        @endforeach

                    </ul>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="col-lg-9">
            <!-- Search -->
            <form class="row g-2 mb-4 align-items-center" onsubmit="return false;">
                <div class="col-md-6">
                    <input type="text"
                           id="search-input"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control rounded-pill shadow-sm px-4"
                           placeholder="Pretraži proizvode..."
                           autocomplete="off">
                </div>

                <div class="col-md-2 d-grid">
                    <button type="button" id="reset-btn"
                            class="btn btn-outline-secondary rounded-pill fw-semibold">
                        Reset
                    </button>
                </div>
            </form>

            <!-- Products -->
            <div id="products-container">
                @include('partials.products-grid', ['proizvodi' => $proizvodi])
            </div>

            <!-- Pagination -->
            <div id="pagination-container" class="d-flex justify-content-center mt-4">
                @include('partials.products-pagination', ['proizvodi' => $proizvodi])
            </div>
        </div>
    </div>
</div>

{{-- Styles --}}
<style>
.product-card {
    transition: all 0.25s ease-in-out;
}
.product-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}
.product-img {
    transition: transform 0.3s ease;
}
.product-card:hover .product-img {
    transform: scale(1.05);
}
.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 .25rem rgba(13,110,253,.25);
}
</style>

{{-- AJAX --}}
<script>
(function () {
    const pageEl = document.getElementById('products-page');
    if (!pageEl) return;

    const ajaxUrl = pageEl.dataset.ajaxUrl;
    const categoryId = pageEl.dataset.categoryId;

    const productsContainer = document.getElementById('products-container');
    const paginationContainer = document.getElementById('pagination-container');
    const searchInput = document.getElementById('search-input');
    const resetBtn = document.getElementById('reset-btn');

    let t;
    const debounce = (fn, delay = 250) => {
        return (...args) => {
            clearTimeout(t);
            t = setTimeout(() => fn.apply(this, args), delay);
        };
    };

    function buildParams(page = 1) {
        const p = new URLSearchParams();
        if (searchInput.value) p.set('search', searchInput.value);
        if (categoryId) p.set('categoryId', categoryId);
        if (page !== 1) p.set('page', page);
        return p.toString();
    }

    function wirePagination() {
        paginationContainer?.querySelectorAll('a.page-link').forEach(a => {
            a.onclick = e => {
                e.preventDefault();
                const page = new URL(a.href).searchParams.get('page') || 1;
                load(page);
            };
        });
    }

    function load(page = 1) {
        fetch(ajaxUrl + '?' + buildParams(page), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(({ html, pagination }) => {
            productsContainer.innerHTML = html;
            paginationContainer.innerHTML = pagination;
            wirePagination();
        });
    }

    searchInput?.addEventListener('input', debounce(() => load(1), 300));
    resetBtn?.addEventListener('click', () => {
        searchInput.value = '';
        load(1);
    });

    wirePagination();
})();
</script>
@endsection
