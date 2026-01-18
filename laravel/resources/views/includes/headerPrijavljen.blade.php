

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        :root {
            --primary-color: #0d6efd;
            --user-color: #20c997;
            --accent-color: #fd7e14;
        }
        
        .navbar {
            background-color: rgba(13, 110, 253, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
           
        }
        
        
        .navbar.scrolled {
            padding-top: 5px;
            padding-bottom: 5px;
            box-shadow: 0 6px 25px rgba(0,0,0,0.15);
        }
        
        /* Stilovi za traku za pretraživanje */
        .search-container {
            position: relative;
            flex-grow: 1;
            max-width: calc(500px + 189.234px);
            margin: 0 15px;
        }
        
        .search-input {
            width: 100%;
            border-radius: 50px;
            border: none;
            padding: 10px 20px 10px 45px;
            background: rgba(255,255,255,0.9);
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .search-input:focus {
            outline: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            background: white;
        }
        
        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
        }
        
        /* Stilovi za korisnički izbornik */
        .user-dropdown {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        
        .user-dropdown:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--user-color), #1ba672);
            color: white;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-right: 10px;
            transition: all 0.3s ease;
        }
        
        .user-dropdown:hover .user-avatar {
            transform: scale(1.1);
            box-shadow: 0 0 0 3px rgba(32, 201, 151, 0.3);
        }
        
        /* Odlikovni znak za obavijesti */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.7rem;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        
        /* Stilovi za košaricu */
        .cart-btn {
            position: relative;
            margin-right: 15px;
        }
        
        .cart-count {
            transition: all 0.3s ease;
        }
        
        .cart-btn:hover .cart-count {
            transform: scale(1.2);
        }
        
        /* Osiguraj da dropdown bude vidljiv kada je aktivan */
        .dropdown-menu.show {
            display: block !important;
        }
        /* Add these to your existing styles */
.navbar-collapse {
    padding-top: 0;
    padding-bottom: 0;
}

.navbar-nav {
    margin-top: 0;
    margin-bottom: 0;
}

.nav-item {
    padding-top: 0.25rem;
    padding-bottom: 0.25rem;
}

/* Adjust the search container margins */
.search-container {
    margin: 0 10px;
}

/* Compact the user dropdown */
.user-dropdown {
    padding: 0.25rem 0.75rem;
}
@media (max-width: 991.98px) {
    .navbar-collapse {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }
    
    .nav-item {
        padding: 0.25rem 0;
    }
}
.navbar-nav.me-auto {
    margin-right: 0 !important;
}
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand animate__animated animate__fadeInLeft" href="{{ url('/') }}">
            <i class="bi bi-laptop"></i> TechShop
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="margin:0;">
                <li class="nav-item animate__animated animate__fadeInDown">
                    <a href="{{ url('/') }}" class="nav-link d-flex align-items-center gap-2" id="navbarCategories">
                        <i class="bi bi-house-door"></i> Početna
                    </a>
                </li>

                <li class="nav-item dropdown animate__animated animate__fadeInDown">
                    <button class="nav-link dropdown-toggle btn btn-link" id="navbarCategories" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-grid"></i> Kategorije
                    </button>

                    <ul class="dropdown-menu animate__animated animate__fadeIn">
                        @foreach ($categories as $category)
                            <li>
                                <a class="dropdown-item" href="{{ url('category', ['id' => $category->id_kategorija]) }}">
                                    {{ $category->ImeKategorija }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>

            <!-- Traka za pretraživanje -->
            <div class="search-container animate__animated animate__fadeInDown" style="width: 100%;">
                <form action="{{ url('pretraga') }}" method="get" id="searchForm">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" id="searchInput" name="q" class="search-input" placeholder="Pretraži proizvode..." aria-label="Search">
                </form>
            </div>

            <div class="d-flex align-items-center animate__animated animate__fadeInRight">
                <!-- Košarica -->
                <a href="{{ url('kosarica') }}" class="btn btn-outline-light btn-sm cart-btn me-3">
                    <i class="bi bi-cart3"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count">
                        {{ $cartCount ?? 0 }}
                    </span>
                </a>

                <!-- Korisnički izbornik -->
                <div class="dropdown">
                    @auth
                        <a class="user-dropdown d-flex align-items-center text-white text-decoration-none px-3 py-1" href="#" id="userDropdown" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                {{ strtoupper(substr(Auth::user()->email, 0, 1)) }}
                            </div>
                            <span>{{ explode('@', Auth::user()->email)[0] }}</span>
                            <i class="bi bi-chevron-down ms-2"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end animate__animated animate__fadeIn">
                            <li><h6 class="dropdown-header">Prijavljeni kao</h6></li>
                            <li><a class="dropdown-item" href="{{ url('profil') }}"><i class="bi bi-person-circle me-2"></i> Moj profil</a></li>
                            <li><a class="dropdown-item" href="{{ url('moje_narudzbe') }}"><i class="bi bi-bag-check me-2"></i> Moje narudžbe</a></li>
                            <li><a class="dropdown-item" href="{{ url('postavke') }}"><i class="bi bi-gear me-2"></i> Postavke</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="{{ route('logout') }}"><i class="bi bi-box-arrow-right me-2"></i> Odjava</a></li>
                        </ul>
                    @else
                        <a class="btn btn-outline-light btn-sm" href="{{ route('login') }}">Prijava</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    // Efekt skrolanja za navigacijsku traku
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Efekt fokusa za traku za pretraživanje
    const searchInput = document.querySelector('.search-input');
    searchInput.addEventListener('focus', function() {
        this.parentElement.classList.add('focused');
    });
    searchInput.addEventListener('blur', function() {
        this.parentElement.classList.remove('focused');
    });

    // Animacija za košaricu
    const cartBtn = document.querySelector('.cart-btn');
    if (cartBtn) {
        cartBtn.addEventListener('click', function() {
            const badge = this.querySelector('.cart-count');
            badge.classList.add('animate__animated', 'animate__tada');
            setTimeout(() => {
                badge.classList.remove('animate__animated', 'animate__tada');
            }, 1000);
        });
    }
</script>

</body>
</html>