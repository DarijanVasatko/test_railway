{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Admin — TechShop')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Optional custom CSS (commented out if you don’t use it) --}}
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
</head>

<body class="bg-light">

    {{-- ADMIN NAVBAR --}}
    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container d-flex justify-content-between">
            
            <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i>TechShop Admin
            </a>

            <ul class="navbar-nav flex-row">
                {{-- Dashboard --}}
                <li class="nav-item me-3">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link text-white">
                        <i class="bi bi-grid-fill me-1"></i> Dashboard
                    </a>
                </li>

                {{-- Products --}}
                <li class="nav-item me-3">
                    <a href="{{ route('admin.products.index') }}" class="nav-link text-white">
                        <i class="bi bi-box-seam me-1"></i> Proizvodi
                    </a>
                </li>

                {{-- Orders --}}
                <li class="nav-item me-3">
                    <a href="{{ route('admin.orders.index') }}" class="nav-link text-white">
                        <i class="bi bi-receipt me-1"></i> Narudžbe
                    </a>
                </li>

                {{-- Users --}}
                <li class="nav-item me-3">
                    <a href="{{ route('admin.users.index') }}" class="nav-link text-white">
                        <i class="bi bi-people me-1"></i> Korisnici
                    </a>
                </li>

                {{-- Logout --}}
                <li class="nav-item">
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-danger">
                            <i class="bi bi-box-arrow-right me-1"></i> Odjava
                        </button>
                    </form>
                </li>
            </ul>

        </div>
    </nav>

    {{-- PAGE CONTENT --}}
    <main class="container py-4">
        @yield('content')
    </main>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
    <script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.querySelector('input[name="q"]');
    const categorySelect = document.querySelector('select[name="category"]');
    const form = searchInput?.closest('form');

    let timer;

    function autoSearch() {
        clearTimeout(timer);
        timer = setTimeout(() => {
            form?.submit();
        }, 350);  // delay in ms
    }

    if (searchInput) {
        searchInput.addEventListener('keyup', autoSearch);
    }

    if (categorySelect) {
        categorySelect.addEventListener('change', () => form.submit());
    }

});
</script>

</body>
</html>
