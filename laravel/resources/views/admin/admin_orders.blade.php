@extends('layouts.admin')

@section('content')
<h4 class="mb-3">Narudžbe</h4>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
            <tr>
                <th>ID</th>
                <th>Kupac</th>
                <th>Ukupno</th>
                <th>Status</th>
                <th>Datum</th>
                <th class="text-end">Detalji</th>
            </tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>#{{ $order->Narudzba_ID }}</td>
                    <td>{{ $order->kupac->ImePrezime ?? $order->kupac->email ?? '-' }}</td>
                    <td>{{ number_format($order->Ukupni_iznos, 2) }} €</td>
                    <td>
                        <form action="{{ route('admin.orders.update', $order) }}"
                              method="POST" class="d-flex gap-2 align-items-center">
                            @csrf
                            @method('PUT')
                            <select name="Status" class="form-select form-select-sm">
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" @selected($order->Status === $status)>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-outline-primary">
                                Spremi
                            </button>
                        </form>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($order->Datum_narudzbe)->format('d.m.Y H:i') }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="btn btn-sm btn-outline-secondary">
                            Detalji
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        Nema narudžbi.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $orders->links() }}
    </div>
</div>
@endsection
