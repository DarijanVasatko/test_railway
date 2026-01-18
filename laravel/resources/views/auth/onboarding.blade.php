@extends('layouts.app_stepform')

@section('title', 'Dovršite profil — TechShop')

@section('content')
    <div class="container py-5">
        <div class="card shadow-lg rounded-4 mx-auto" style="max-width: 700px;">
            <div class="card-body p-5">
                <h3 class="fw-bold text-center text-primary mb-4">
                    <i class="bi bi-person-lines-fill me-2"></i> Dovršite svoj profil
                </h3>
                <p class="text-muted text-center mb-4">Molimo vas da unesete osnovne podatke za dostavu i kontakt.</p>

                <form method="POST" action="{{ route('onboarding.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ime</label>
                            <input type="text" name="ime" value="{{ old('ime', Auth::user()->ime) }}"
                                class="form-control rounded-pill" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Prezime</label>
                            <input type="text" name="prezime" value="{{ old('prezime', Auth::user()->prezime) }}"
                                class="form-control rounded-pill" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Telefon</label>
                            <input type="text" name="telefon" value="{{ old('telefon', Auth::user()->telefon) }}"
                                class="form-control rounded-pill">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Adresa</label>
                            <input type="text" name="adresa" class="form-control rounded-pill" required>
                        </div>
                        <div class="col-md-6 position-relative">
                            <label class="form-label fw-semibold">Grad</label>
                            <input type="text" name="grad" id="grad" class="form-control rounded-pill"
                                placeholder="Upiši grad..." autocomplete="off" required>
                            <ul id="town-suggestions" class="list-group position-absolute w-100 mt-1 shadow-sm d-none"
                                style="z-index: 1000;"></ul>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Poštanski broj</label>
                            <input type="text" name="postanski_broj" class="form-control rounded-pill" required>
                        </div>

                        {{-- Država dropdown --}}
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Država</label>
                            <input list="country-list" name="drzava" class="form-control rounded-pill"
                                placeholder="Upiši ili odaberi..." required>
                            <datalist id="country-list">
                                @foreach($countries as $country)
                                    <option value="{{ $country->name }}">
                                @endforeach
                            </datalist>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-semibold">
                            <i class="bi bi-check-circle me-1"></i> Spremi i nastavi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', () => {
    const gradInput = document.getElementById('grad');
    const drzavaInput = document.querySelector('[name="drzava"]');
    const suggestions = document.getElementById('town-suggestions');
    let timeout = null;

    gradInput.addEventListener('input', function() {
        const query = this.value.trim();
        const country = drzavaInput.value.trim();
        clearTimeout(timeout);

        if (query.length < 1 || !country) {
            suggestions.classList.add('d-none');
            return;
        }

        timeout = setTimeout(() => {
            fetch(`/towns/search?q=${encodeURIComponent(query)}&country=${encodeURIComponent(country)}`)
                .then(res => res.json())
                .then(data => {
                    suggestions.innerHTML = '';
                    if (data.length === 0) {
                        suggestions.classList.add('d-none');
                        return;
                    }
                    data.forEach(town => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item list-group-item-action';
                        li.textContent = town;
                        li.onclick = () => {
                            gradInput.value = town;
                            suggestions.classList.add('d-none');
                        };
                        suggestions.appendChild(li);
                    });
                    suggestions.classList.remove('d-none');
                });
        }, 300);
    });

    document.addEventListener('click', (e) => {
        if (!suggestions.contains(e.target) && e.target !== gradInput) {
            suggestions.classList.add('d-none');
        }
    });
});
</script>
