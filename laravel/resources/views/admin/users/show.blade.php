@extends('layouts.admin')

@section('title', 'Korisnik — ' . $user->full_name)

@section('content')

{{-- -------------------------------------------------------
     SAFE BADGE HELPER (prevents function redeclaration)
-------------------------------------------------------- --}}
@php
    if (!function_exists('userOrderStatusBadgeClass')) {
        function userOrderStatusBadgeClass($status) {
            return match ($status) {
                'Na čekanju'    => 'bg-warning text-dark',
                'U obradi'      => 'bg-info text-dark',
                'Poslano'       => 'bg-primary',
                'Dostavljeno',
                'Dovršena'      => 'bg-success',
                'Otkazana'      => 'bg-danger',
                default         => 'bg-secondary',
            };
        }
    }
@endphp

<h2 class="fw-bold mb-4">
    <i class="bi bi-person me-2"></i> {{ $user->full_name }}
</h2>

{{-- -------------------------------------------------------
     USER INFORMATION CARD
-------------------------------------------------------- --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Telefon:</strong> {{ $user->telefon ?? '—' }}</p>

        {{-- created_at is a Carbon instance --}}
        <p><strong>Registriran:</strong> {{ $user->created_at->format('d.m.Y H:i') }}</p>

        <p><strong>Admin:</strong> {{ $user->is_admin ? 'Da' : 'Ne' }}</p>
    </div>
</div>

{{-- -------------------------------------------------------
     USER ORDERS TABLE
-------------------------------------------------------- --}}
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
                @forelse($user->narudzbe as $order)
                    <tr>
                        <td>#{{ $order->Narudzba_ID }}</td>

                        {{-- Datum_narudzbe is a string -> parse to Carbon safely --}}
                        <td>{{ \Carbon\Carbon::parse($order->Datum_narudzbe)->format('d.m.Y H:i') }}</td>

                        <td>
                            <span class="badge {{ userOrderStatusBadgeClass($order->Status) }} px-3 py-2">
                                {{ $order->Status }}
                            </span>
                        </td>

                        <td class="text-end">
                            {{ number_format($order->Ukupni_iznos, 2) }} €
                        </td>

                        <td class="text-end">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Detalji
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            Korisnik još nema narudžbi.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>

@endsection
