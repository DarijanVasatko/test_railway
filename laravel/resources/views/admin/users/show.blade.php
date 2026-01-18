@extends('layouts.admin')

@section('title', 'Korisnik — ' . $user->full_name)

@section('content')

<h2 class="fw-bold mb-4">
    <i class="bi bi-person me-2"></i> {{ $user->full_name }}
</h2>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Telefon:</strong> {{ $user->telefon ?? '—' }}</p>
        <p><strong>Registriran:</strong> {{ $user->created_at->format('d.m.Y H:i') }}</p>
    </div>
</div>

<h4 class="fw-bold mb-3">Narudžbe korisnika</h4>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Datum</th>
                    <th>Status</th>
                    <th class="text-end">Ukupno</th>
                    <th class="text-end">Akcija</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user->narudzbe as $order)
                    <tr>
                        <td>#{{ $order->Narudzba_ID }}</td>
                        <td>{{ $order->Datum_narudzbe }}</td>
                        <td>{{ $order->Status }}</td>
                        <td class="text-end">{{ number_format($order->Ukupni_iznos, 2) }} €</td>
                        <td class="text-end">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="btn btn-sm btn-outline-primary">
                                Detalji
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
