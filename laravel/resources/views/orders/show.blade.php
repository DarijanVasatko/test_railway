@extends('layouts.app')

@section('title', 'Detalji narudžbe — TechShop')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold text-center text-primary mb-5">
        <i class="bi bi-receipt me-2"></i> Detalji narudžbe #{{ $order->id }}
    </h2>

    <div class="card shadow-sm rounded-4 border-0">
        <div class="card-body">
            <p><strong>Status:</strong> <span class="badge bg-info">{{ $order->status }}</span></p>
            <p><strong>Adresa dostave:</strong> {{ $order->adresa_dostave }}</p>
            <p><strong>Način plaćanja:</strong> {{ optional($order->nacinPlacanja)->naziv ?? 'N/A' }}</p>
            <p><strong>Datum:</strong> {{ optional($order->created_at)->format('d.m.Y H:i') ?? '-' }}</p>
        </div>
    </div>

    <div class="table-responsive shadow-sm rounded-4 bg-white p-3 mt-4">
        <table class="table align-middle">
            <thead class="bg-primary text-white">
                <tr>
                    <th>Proizvod</th>
                    <th class="text-center">Količina</th>
                    <th class="text-end">Cijena</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->detalji as $detail)
                    <tr>
                        <td>{{ $detail->proizvod->Naziv ?? 'Proizvod obrisan' }}</td>
                        <td class="text-center">{{ $detail->kolicina }}</td>
                        <td class="text-end">{{ number_format($detail->cijena * $detail->kolicina, 2) }} €</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="text-end mt-4">
        <h4>Ukupno: <span class="text-primary">{{ number_format($order->ukupna_cijena, 2) }} €</span></h4>
    </div>
</div>
@endsection
