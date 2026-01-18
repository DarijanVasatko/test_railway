<x-guest-layout>
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4 text-primary fw-bold">
                        <i class="bi bi-person-plus me-2"></i> Registracija u TechShop
                    </h3>

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
    <div class="col-md-6">
        <label for="ime" class="form-label">Ime</label>
        <input id="ime" type="text" class="form-control @error('ime') is-invalid @enderror"
               name="ime" value="{{ old('ime') }}" required autofocus>
        @error('ime')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="prezime" class="form-label">Prezime</label>
        <input id="prezime" type="text" class="form-control @error('prezime') is-invalid @enderror"
               name="prezime" value="{{ old('prezime') }}" required>
        @error('prezime')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
</div>


                        <div class="mb-3">
                            <label for="email" class="form-label">Email adresa</label>
                            <input id="email" class="form-control rounded-pill" type="email" name="email"
                                   value="{{ old('email') }}" required autocomplete="username">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Lozinka</label>
                            <input id="password" class="form-control rounded-pill" type="password"
                                   name="password" required autocomplete="new-password">
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Potvrdi lozinku</label>
                            <input id="password_confirmation" class="form-control rounded-pill" type="password"
                                   name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-semibold">
                            Registriraj se
                        </button>

                        <div class="text-center mt-3">
                            <span class="text-muted">Već imaš račun?</span>
                            <a href="{{ route('login') }}" class="text-decoration-none fw-semibold text-primary">
                                Prijavi se
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
