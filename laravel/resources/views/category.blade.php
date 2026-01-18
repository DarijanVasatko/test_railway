@extends('layouts.app')

@section('title', $categoryId ? 'TechShop - ' . ($kategorije->find($categoryId)->ImeKategorija ?? '') : 'TechShop - Svi proizvodi')

@section('content')
    <div class="container py-5" id="products-page" data-ajax-url="{{ route('proizvodi.search') }}"
        data-category-id="{{ $categoryId ?? '' }}">

        <!-- Heading -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h2 class="fw-bold mb-0 text-primary">
                {{ $categoryId ? 'Proizvodi iz kategorije: ' . ($kategorije->find($categoryId)->ImeKategorija ?? '') : 'Svi proizvodi' }}
            </h2>

            <div class="d-flex gap-2">
                <a href="{{ route('index.index') }}" class="btn btn-outline-secondary rounded-pill">
                    <i class="bi bi-house me-1"></i> Početna
                </a>
                <a href="{{ $categoryId ? route('proizvodi.kategorija', $categoryId) : route('proizvodi.index') }}"
                    class="btn btn-outline-primary rounded-pill">
                    <i class="bi bi-arrow-clockwise me-1"></i> Osvježi
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar kategorije -->
            <div class="col-lg-3 mb-4">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-3 text-center text-primary">Kategorije</h5>
                        <ul class="list-group list-group-flush">
                            <li
                                class="list-group-item border-0 {{ $categoryId ? '' : 'bg-primary text-white fw-bold rounded' }}">
                                <a href="{{ route('proizvodi.index', request()->only(['search', 'sort'])) }}"
                                    class="text-decoration-none {{ $categoryId ? 'text-dark' : 'text-white' }} d-block">
                                    Sve kategorije
                                </a>
                            </li>
                            @foreach($kategorije as $kat)
                                <li
                                    class="list-group-item border-0 {{ $categoryId == $kat->id_kategorija ? 'bg-primary text-white fw-bold rounded' : '' }}">
                                    <a href="{{ route('proizvodi.kategorija', array_merge(['id' => $kat->id_kategorija], request()->only(['search', 'sort']))) }}"
                                        class="text-decoration-none {{ $categoryId == $kat->id_kategorija ? 'text-white' : 'text-dark' }} d-block">
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
                <!-- Search & Sort -->
                <form id="filters-form" class="row g-2 mb-4 align-items-center" onsubmit="return false;">
                    <div class="col-md-6">
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control rounded-pill shadow-sm px-4" placeholder="🔍 Pretraži proizvode..."
                            id="search-input" autocomplete="off">
                    </div>
                    <div class="col-md-4">
                        <select name="sort" class="form-select rounded-pill shadow-sm" id="sort-select">
                            <option value="">Sortiraj</option>
                            <option value="name_asc" @selected(request('sort') == 'name_asc')>Naziv (A–Z)</option>
                            <option value="name_desc" @selected(request('sort') == 'name_desc')>Naziv (Z–A)</option>
                            <option value="price_asc" @selected(request('sort') == 'price_asc')>Cijena (najniža)</option>
                            <option value="price_desc" @selected(request('sort') == 'price_desc')>Cijena (najviša)</option>
                            <option value="newest" @selected(request('sort') == 'newest')>Najnoviji</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="button" id="reset-btn" class="btn btn-outline-secondary rounded-pill fw-semibold">
                            Reset
                        </button>
                    </div>
                </form>

                <!-- AJAX results -->
                <div id="products-container">
                    @include('partials.products-grid', ['proizvodi' => $proizvodi])
                </div>

                <div id="pagination-container" class="d-flex justify-content-center mt-4">
                    @include('partials.products-pagination', ['proizvodi' => $proizvodi])
                </div>
            </div>
        </div>
    </div>

    <style>
        .product-card {
            transition: all 0.25s ease-in-out;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .product-img {
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-img {
            transform: scale(1.05);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        
    </style>

    <script>
        (function () {
            const pageEl = document.getElementById('products-page');
            const ajaxUrl = pageEl.dataset.ajaxUrl;
            const categoryId = pageEl.dataset.categoryId || '';
            const productsContainer = document.getElementById('products-container');
            const paginationContainer = document.getElementById('pagination-container');
            const searchInput = document.getElementById('search-input');
            const sortSelect = document.getElementById('sort-select');
            const resetBtn = document.getElementById('reset-btn');

            // Debounce helper
            let t = null;
            const debounce = (fn, delay = 250) => {
                return (...args) => {
                    clearTimeout(t);
                    t = setTimeout(() => fn.apply(this, args), delay);
                };
            };

            // Build query string
            function params(page = 1) {
                const search = searchInput.value || '';
                const sort = sortSelect.value || '';
                const p = new URLSearchParams();
                if (search) p.set('search', search);
                if (sort) p.set('sort', sort);
                if (categoryId) p.set('categoryId', categoryId);
                if (page && page !== 1) p.set('page', page);
                return p.toString();
            }

            // Fetch and render
            function load(page = 1) {
                const url = ajaxUrl + (ajaxUrl.includes('?') ? '&' : '?') + params(page);

                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(r => r.json())
                    .then(({ html, pagination }) => {
                        productsContainer.innerHTML = html;
                        paginationContainer.innerHTML = pagination;
                        wirePaginationLinks(); // rebind after replace
                    })
                    .catch(() => { /* optionally show a toast */ });
            }

        .then(({ html, pagination }) => {
                productsContainer.innerHTML = html;
                paginationContainer.innerHTML = pagination;
                wirePaginationLinks();

                // keep sidebar highlight
                document.querySelectorAll('#profile-menu a, .list-group-item').forEach(li => li.classList.remove('bg-primary', 'text-white'));
                const activeItem = document.querySelector(`a[href*="kategorija/${categoryId}"]`);
                if (activeItem) activeItem.closest('.list-group-item').classList.add('bg-primary', 'text-white');
            })

            // Wire pagination <a> to AJAX
            function wirePaginationLinks() {
                const links = paginationContainer.querySelectorAll('a.page-link');
                links.forEach(a => {
                    a.addEventListener('click', (e) => {
                        e.preventDefault();
                        const url = new URL(a.href);
                        const page = url.searchParams.get('page') || 1;
                        load(Number(page));
                    });
                });
            }

            // Events: instant search & instant sort
            searchInput.addEventListener('input', debounce(() => load(1), 250));
            sortSelect.addEventListener('change', () => load(1));
            resetBtn.addEventListener('click', () => {
                searchInput.value = '';
                sortSelect.value = '';
                load(1);
            });

            // Initial bind
            wirePaginationLinks();
        })();
    </script>

    
@endsection

