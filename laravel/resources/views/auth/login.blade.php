<x-guest-layout>
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4 text-primary fw-bold">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Prijava u TechShop
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

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email adresa</label>
                            <input id="email" class="form-control rounded-pill" type="email" name="email"
                                   value="{{ old('email') }}" required autofocus autocomplete="username">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Lozinka</label>
                            <input id="password" class="form-control rounded-pill" type="password"
                                   name="password" required autocomplete="current-password">
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                                <label class="form-check-label" for="remember_me">Zapamti me</label>
                            </div>

                            @if (Route::has('password.request'))
                                <a class="text-decoration-none text-primary small" href="{{ route('password.request') }}">
                                    Zaboravljena lozinka?
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-semibold">
                            Prijavi se
                        </button>

                        <div class="text-center mt-3">
                            <span class="text-muted">Nemaš račun?</span>
                            <a href="{{ route('register') }}" class="text-decoration-none fw-semibold text-primary">
                                Registriraj se
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
