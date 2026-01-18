<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechShop - Najbolja tehnologija u Hrvatskoj</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-color: #0d6efd;
            --user-color: #20c997;
            --accent-color: #fd7e14;
        }

        .navbar {
            background-color: rgba(13, 110, 253, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            padding: 15px;
        }

        .navbar.scrolled {
            padding-top: 5px;
            padding-bottom: 5px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
        }

        .search-container {
            position: relative;
            flex-grow: 1;
            max-width: 600px;
            margin: 0 15px;
        }

        .search-input {
            width: 100%;
            border-radius: 50px;
            border: none;
            padding: 10px 20px 10px 45px;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
        }

        .btn-login {
            background-color: rgba(255, 255, 255, 0.1);
            border: 2px solid white;
            color: white;
            font-weight: 600;
            border-radius: 50px;
            padding: 8px 25px;
            margin-right: 12px;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: white;
            color: #0d6efd;
            transform: translateY(-2px);
        }

        #navbarCategories {
            z-index: 1050;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}"><i class="bi bi-laptop"></i> TechShop</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="{{ url('/') }}" class="nav-link"><i class="bi bi-house-door"></i> Početna</a>
                    </li>
                    <li class="nav-item dropdown">
                        <button class="nav-link dropdown-toggle btn btn-link" id="navbarCategories" role="button"
                            data-bs-toggle="dropdown">
                            <i class="bi bi-grid"></i> Kategorije
                        </button>
                        <ul class="dropdown-menu">
                            @foreach ($categories as $category)
                                <li>
                                    <a class="dropdown-item"
                                        href="{{ url('categoryNePrijavljen1', ['id' => $category->id_kategorija]) }}">
                                        {{ $category->ImeKategorija }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>

                <div class="search-container">
                    <form action="{{ url('pretraga') }}" method="get">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" name="q" class="search-input" placeholder="Pretraži proizvode..."
                            aria-label="Search">
                    </form>
                </div>

                <div class="d-flex align-items-center">
                    <a href="{{ url('kosarica') }}" class="btn btn-outline-light btn-sm cart-btn me-3">
                        <i class="bi bi-cart3"></i>
                        <span
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count">
                            {{ $cartCount ?? 0 }}
                        </span>
                    </a>

                    @auth
                        <div class="dropdown">
                            <a class="user-dropdown d-flex align-items-center text-white text-decoration-none px-3 py-1"
                                href="#" data-bs-toggle="dropdown">
                                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->email, 0, 1)) }}</div>
                                <span>{{ explode('@', Auth::user()->email)[0] }}</span>
                                <i class="bi bi-chevron-down ms-2"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ url('profil') }}">Moj profil</a></li>
                                <li><a class="dropdown-item" href="{{ url('moje_narudzbe') }}">Moje narudžbe</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger" href="{{ route('logout') }}">Odjava</a></li>
                            </ul>
                        </div>
                    @else
                        <a class="btn btn-outline-light btn-sm" href="{{ route('login') }}">Prijava</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <script>
        window.addEventListener('scroll', function () {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) navbar.classList.add('scrolled');
            else navbar.classList.remove('scrolled');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>