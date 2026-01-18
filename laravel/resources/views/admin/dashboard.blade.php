@extends('layouts.admin')

@section('title', 'Admin Dashboard — TechShop')

@section('content')
<h2 class="fw-bold mb-3">Pregled administracije</h2>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Proizvodi</h6>
                <h3 class="fw-bold">{{ $productsCount }}</h3>
                <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-sm">
                    Upravljanje proizvodima
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Narudžbe</h6>
                <h3 class="fw-bold">{{ $ordersCount }}</h3>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-success btn-sm">
                    Upravljanje narudžbama
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Korisnici</h6>
                <h3 class="fw-bold">{{ $usersCount }}</h3>
                <a href="{{ route('admin.users.index') }}" class="btn btn-info text-white btn-sm">
                    Pregled korisnika
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
