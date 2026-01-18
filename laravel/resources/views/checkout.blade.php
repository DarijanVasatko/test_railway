@extends('layouts.app')

@section('title', 'Završi kupnju — TechShop')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold text-center text-primary mb-5">
        <i class="bi bi-credit-card me-2"></i> Završetak kupnje
    </h2>

    <form action="{{ route('checkout.store') }}" method="POST" class="shadow-lg rounded-4 bg-white p-4">
        @csrf

        {{-- 1️⃣ Dostava --}}
        <h5 class="fw-bold mb-3">Adresa dostave</h5>

        @if($addresses->isEmpty())
            <p class="text-muted">Nema spremljenih adresa. <a href="{{ route('profile.edit') }}">Dodaj adresu</a>.</p>
            <input type="text" name="adresa_dostave" class="form-control rounded-pill mb-4" placeholder="Unesite adresu ručno" required>
        @else
            <select name="adresa_dostave" class="form-select rounded-pill mb-4" required>
                @foreach($addresses as $address)
                    <option value="{{ $address->adresa }}, {{ $address->grad }}, {{ $address->drzava }}">
                        {{ $address->adresa }}, {{ $address->grad }} ({{ $address->drzava }})
                    </option>
                @endforeach
            </select>
        @endif

        {{-- 2️⃣ Način plaćanja --}}
        <div class="mb-4">
        <h5 class="fw-bold mb-3">Način plaćanja</h5>
        <div class="row">
        @foreach($paymentMethods as $method)
            <div class="col-md-4 mb-3">
                <div class="form-check border rounded-4 p-3 shadow-sm h-100 d-flex align-items-center gap-2 hover-card">
                    <input class="form-check-input mt-0" type="radio" name="nacin_placanja_id"
                           id="placanje_{{ $method->NacinPlacanja_ID }}" 
                           value="{{ $method->NacinPlacanja_ID }}" required>
                    <label class="form-check-label fw-semibold flex-grow-1 d-flex align-items-center gap-2"
                           for="placanje_{{ $method->NacinPlacanja_ID }}">
                        @switch($method->Opis)
                            @case('Revolut Pay')
                                <img src="{{ asset('uploads/icons/revolut.png') }}" alt="Revolut" style="height:20px;">
                                @break
                            @case('KeksPay')
                                <img src="{{ asset('uploads/icons/kekspay.png') }}" alt="KeksPay" style="height:20px;">
                                @break
                            @case('Skrill')
                                <img src="{{ asset('uploads/icons/skrill.png') }}" alt="Skrill" style="height:20px;">
                                @break
                            @case('PayPal')
                                <img src="{{ asset('uploads/icons/paypal.png') }}" alt="PayPal" style="height:20px;">
                                @break
                            @case('Kartično plaćanje')
                                <i class="bi bi-credit-card text-primary fs-5"></i>
                                @break
                            @case('Plaćanje pouzećem')
                                <i class="bi bi-cash-stack text-success fs-5"></i>
                                @break
                            @case('Google Pay')
                                <img src="{{ asset('uploads/icons/googlepay.png') }}" alt="Google Pay" style="height:20px;">
                                @break
                            @case('Apple Pay')
                                <img src="{{ asset('uploads/icons/applepay.png') }}" alt="Apple Pay" style="height:20px;">
                                @break
                            @case('Bankovni prijenos')
                                <i class="bi bi-bank fs-5 text-primary"></i>
                                @break
                            @default
                                <i class="bi bi-wallet2 text-secondary fs-5"></i>
                        @endswitch
                        {{ $method->Opis }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    .hover-card {
        transition: all 0.2s ease;
    }
    .hover-card:hover {
        background-color: #f0f7ff;
        transform: translateY(-3px);
        box-shadow: 0 0 10px rgba(13,110,253,0.2);
    }
</style>


        {{-- 3️⃣ Pregled košarice --}}
        <h5 class="fw-bold mb-3 mt-4">Pregled proizvoda</h5>
        <div class="table-responsive mb-4">
            <table class="table align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>Proizvod</th>
                        <th class="text-center">Količina</th>
                        <th class="text-end">Cijena</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                        @php
                            $product = $item->proizvod ?? (object) $item['product'];
                            $qty = $item->kolicina ?? $item['quantity'];
                            $price = $product->Cijena ?? $item['price'];
                        @endphp
                        <tr>
                            <td>{{ $product->Naziv }}</td>
                            <td class="text-center">{{ $qty }}</td>
                            <td class="text-end">{{ number_format($price * $qty, 2) }} €</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- 4️⃣ Ukupno --}}
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="fw-bold">Ukupno: <span class="text-primary">{{ number_format($total, 2) }} €</span></h4>
            <button type="submit" class="btn btn-success rounded-pill px-5 fw-semibold">
                <i class="bi bi-check-circle me-1"></i> Potvrdi narudžbu
            </button>
        </div>
    </form>
</div>

<style>
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .form-check {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .form-check:hover {
        background: #f8f9fa;
        transform: translateY(-2px);
    }
</style>
@endsection
