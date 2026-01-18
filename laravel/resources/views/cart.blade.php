@extends('layouts.app')

@section('title', 'Moja košarica — TechShop')

@section('content')
<section class="cart-section py-5 bg-light">
    <div class="container">
        <h2 class="fw-bold text-center mb-5 text-primary">
            <i class="bi bi-cart4 me-2"></i> Moja košarica
        </h2>

        @if(empty($cart))
            <div class="text-center py-5">
                <i class="bi bi-bag-x text-secondary" style="font-size: 4rem;"></i>
                <p class="text-muted mt-3 fs-5">Vaša košarica je trenutno prazna.</p>
                <a href="{{ route('proizvodi.index') }}" class="btn btn-primary mt-3 rounded-pill px-4 py-2 fw-semibold">
                    <i class="bi bi-shop me-2"></i> Nastavi kupovinu
                </a>
            </div>
        @else
            <div class="row justify-content-center">
                <div class="col-lg-10">

                    @if (session('success'))
                        <div class="alert alert-success text-center rounded-pill py-2 mb-4 shadow-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive shadow-lg rounded-4 bg-white p-4">
                        <table class="table align-middle mb-0">
                            <thead class="bg-primary text-white rounded-top">
                                <tr>
                                    <th scope="col">Proizvod</th>
                                    <th scope="col" class="text-center">Količina</th>
                                    <th scope="col" class="text-center">Cijena</th>
                                    <th scope="col" class="text-center">Ukupno</th>
                                    <th scope="col" class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $total = 0;
                                    $taxRate = 0.25;
                                    $delivery = 5.00;
                                @endphp

                                @foreach($cart as $id => $item)
                                    @php 
                                        // Detect structure
                                        if (isset($item['product'])) {
                                            $product = $item['product'];
                                            $name = $product->Naziv;
                                            $price = $product->Cijena;
                                            $image = $product->Slika;
                                            $quantity = $item['quantity'];
                                        } else {
                                            $name = $item['name'];
                                            $price = $item['price'];
                                            $image = $item['image'];
                                            $quantity = $item['quantity'];
                                        }

                                        $subtotalWithTax = $price * $quantity;
                                        $subtotalWithoutTax = $subtotalWithTax / (1 + $taxRate);
                                        $total += $subtotalWithTax;
                                    @endphp
                                    <tr class="cart-item-row">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="cart-img-wrapper me-3">
                                                    <img src="{{ asset($image) }}" alt="{{ $name }}" class="cart-img">
                                                </div>
                                                <div>
                                                    <h6 class="fw-semibold mb-1">{{ $name }}</h6>
                                                    <small class="text-muted">Šifra: {{ $id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center fw-semibold">
                                            {{ $quantity }}
                                        </td>
                                        <td class="text-center text-muted">
                                            <div class="d-flex flex-column">
                                                <small class="text-secondary">
                                                    Bez PDV-a: {{ number_format($price / 1.25, 2) }} €
                                                </small>
                                                <span>{{ number_format($price, 2) }} €</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex flex-column">
                                                <small class="text-secondary">
                                                    Bez PDV-a: {{ number_format($subtotalWithoutTax, 2) }} €
                                                </small>
                                                <span class="fw-bold text-primary">
                                                    {{ number_format($subtotalWithTax, 2) }} €
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('cart.remove', ['id' => $id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Summary --}}
                    <div class="card border-0 shadow-sm rounded-4 mt-5 p-4 bg-white">
                        @php
                            $taxAmount = $total - ($total / (1 + $taxRate));
                            $grandTotal = $total + $delivery;
                        @endphp

                        <div class="row">
                            <div class="col-md-6 text-md-start text-center mb-3 mb-md-0">
                                <a href="{{ route('proizvodi.index') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-semibold">
                                    <i class="bi bi-arrow-left me-2"></i> Nastavi kupovinu
                                </a>
                            </div>

                            <div class="col-md-6">
                                <div class="cart-summary text-md-end text-center">
                                    <p class="mb-1">Ukupno bez PDV-a: <strong>{{ number_format($total / 1.25, 2) }} €</strong></p>
                                    <p class="mb-1">PDV (25%): <strong>{{ number_format($taxAmount, 2) }} €</strong></p>
                                    <p class="mb-1">Dostava: <strong>{{ number_format($delivery, 2) }} €</strong></p>
                                    <h4 class="fw-bold mt-3">Ukupno za platiti: 
                                        <span class="text-primary">{{ number_format($grandTotal, 2) }} €</span>
                                    </h4>
                                    <a href="{{ route('checkout.index') }}" 
   class="btn btn-primary rounded-pill px-5 py-2 fw-semibold shadow-sm mt-3">
   <i class="bi bi-credit-card me-2"></i> Završi kupnju
</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @endif
    </div>
</section>

<style>
    .cart-section { min-height: 70vh; }

    .cart-img-wrapper {
        width: 70px; height: 70px;
        overflow: hidden; border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .cart-img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform 0.3s ease;
    }

    .cart-img-wrapper:hover .cart-img { transform: scale(1.08); }

    .cart-item-row { transition: background-color 0.2s ease; }
    .cart-item-row:hover { background-color: rgba(13,110,253,0.05); }

    .table td, .table th { vertical-align: middle; padding: 1rem; }
</style>
@endsection
