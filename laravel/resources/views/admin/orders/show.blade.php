@extends('layouts.admin')

@section('content')
<h4 class="mb-3">Narudžba #{{ $order->Narudzba_ID }}</h4>

<div class="card shadow-sm mb-4">
    <div class="card-body">

        <h5 class="fw-bold mb-3">Podaci o kupcu</h5>

        <p class="mb-1"><strong>Ime i prezime:</strong>
            {{ $order->user->ImePrezime ?? '-' }}
        </p>

        <p class="mb-1"><strong>Email:</strong>
            {{ $order->user->email }}
        </p>

        <p class="mb-1"><strong>Način plaćanja:</strong>
            {{ $order->nacinPlacanja->Naziv ?? '-' }}
        </p>

        <p class="mb-0"><strong>Datum narudžbe:</strong>
            {{ \Carbon\Carbon::parse($order->Datum_narudzbe)->format('d.m.Y H:i') }}
        </p>

    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Proizvod</th>
                    <th class="text-center">Količina</th>
                    <th class="text-center">Cijena</th>
                    <th class="text-center">Ukupno</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->detalji as $item)
                    <tr>
                        <td>{{ $item->proizvod->Naziv ?? 'Proizvod obrisan' }}</td>
                        <td class="text-center">{{ $item->Kolicina }}</td>
                        <td class="text-center">{{ number_format($item->Cijena, 2) }} €</td>
                        <td class="text-center fw-bold">
                            {{ number_format($item->Kolicina * $item->Cijena, 2) }} €
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer text-end">
        <h5 class="fw-bold mb-0">
            Ukupno: {{ number_format($order->Ukupni_iznos, 2) }} €
        </h5>
    </div>
</div>

<div class="card shadow-sm p-4">
    <h5 class="fw-bold mb-3">Promjena statusa narudžbe</h5>

    <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="d-flex gap-3">
        @csrf
        @method('PUT')

        <select name="Status" class="form-select w-auto">
            @foreach($statuses as $status)
                <option value="{{ $status }}" @selected($order->Status === $status)>
                    {{ $status }}
                </option>
            @endforeach
        </select>

        <button class="btn btn-primary">Spremi</button>
    </form>

    <hr class="my-4">

    {{-- CANCEL BUTTON --}}
    @if($order->Status !== 'Otkazana')
        <form action="{{ route('admin.orders.cancel', $order) }}" method="POST" class="d-inline">
            @csrf
            @method('PUT')

            <button class="btn btn-danger" onclick="return confirm('Jeste li sigurni da želite otkazati narudžbu?');">
                Otkazaj narudžbu
            </button>
        </form>
    @endif

    {{-- CLOSE BUTTON --}}
    @if($order->Status !== 'Dovršena')
        <form action="{{ route('admin.orders.close', $order) }}" method="POST" class="d-inline">
            @csrf
            @method('PUT')

            <button class="btn btn-success">
                Zatvori narudžbu
            </button>
        </form>
    @endif
</div>

@endsection
