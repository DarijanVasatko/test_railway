@extends('layouts.admin')

@section('title', 'Narudžba #' . $order->id)

@section('content')

<h2 class="fw-bold mb-4">
    <i class="bi bi-receipt me-2"></i> Narudžba #{{ $order->id }}
</h2>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">

        <form method="POST" action="{{ route('admin.orders.update', $order) }}"
              class="d-flex align-items-center gap-3">
            @csrf
            @method('PUT')

            <strong>Status:</strong>

            <select name="Status" class="form-select w-auto">
                @foreach (['U obradi','Poslano','Isporučeno','Narudžba završena'] as $s)
                    <option value="{{ $s }}" @selected($order->Status === $s)>{{ $s }}</option>
                @endforeach
            </select>

            <button class="btn btn-primary btn-sm">Spremi</button>
        </form>

        <hr>

        <p><strong>Kupac:</strong> {{ $order->kupac->ImePrezime ?? 'N/A' }}</p>
        <p><strong>Adresa:</strong> {{ $order->adresa_dostave }}</p>
        <p><strong>Način plaćanja:</strong> {{ $order->nacinPlacanja->naziv }}</p>
        <p><strong>Datum:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <h5 class="fw-bold">Stavke narudžbe</h5>
        <div class="table-responsive mt-3">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Proizvod</th>
                        <th class="text-center">Količina</th>
                        <th class="text-end">Cijena</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->detalji as $row)
                        <tr>
                            <td>{{ $row->proizvod->Naziv ?? 'Izbrisan proizvod' }}</td>
                            <td class="text-center">{{ $row->kolicina }}</td>
                            <td class="text-end">
                                {{ number_format($row->cijena * $row->kolicina, 2) }} €
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <h4 class="text-end mt-3">Ukupno:  
            <span class="text-primary fw-bold">
                {{ number_format($order->Ukupni_iznos, 2) }} €
            </span>
        </h4>
    </div>
</div>

@endsection
