<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
    <div class="container-fluid justify-content-center py-2">

        <!-- Brand -->
        <a class="navbar-brand fw-bold text-white d-flex align-items-center me-4" href="{{ route('index.index') }}">
            <i class="bi bi-display me-2"></i> TechShop
        </a>

        <!-- Mobile toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar links -->
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav align-items-lg-center me-3">
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('index.index') }}">
                        <i class="bi bi-house-door me-1"></i> Početna
                    </a>
                </li>

                <!-- Mega Dropdown: Kategorije -->
      <li class="nav-item dropdown position-static kategorije-dropdown">
    <a href="{{ route('proizvodi.index') }}" 
       class="nav-link dropdown-toggle text-white fw-semibold"
       id="kategorijeDropdown"
       role="button"
       aria-expanded="false">
        <i class="bi bi-grid-3x3-gap me-1"></i> Kategorije
    </a>

    <!-- Mega Dropdown -->
    <div class="dropdown-menu w-100 border-0 shadow-lg mt-0 p-4 bg-white mega-dropdown" 
         aria-labelledby="kategorijeDropdown">

        <div class="container">
            <div class="row g-4">
                @foreach($kategorije as $kat)
                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="{{ url('/kategorija/'.$kat->id_kategorija) }}" 
                           class="text-decoration-none d-block p-3 rounded-3 hover-card">
                            <div class="d-flex align-items-center mb-2">
                                <div class="icon-wrapper bg-primary-subtle text-primary d-flex align-items-center justify-content-center rounded-circle me-2" 
                                     style="width:40px;height:40px;">
                                    <i class="bi bi-cpu"></i>
                                </div>
                                <h6 class="mb-0 fw-semibold">{{ $kat->ImeKategorija }}</h6>
                            </div>
                            <small class="text-muted">Istraži proizvode u kategoriji</small>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</li>



                    <div class="dropdown-menu w-100 border-0 shadow-lg mt-0 p-4 bg-white mega-dropdown" 
                         aria-labelledby="kategorijeDropdown">

                        <div class="container">
                            <div class="row g-4">
                                @foreach($kategorije as $kat)
                                    <div class="col-6 col-md-4 col-lg-3">
                                        <a href="{{ url('/kategorija/'.$kat->id_kategorija) }}" 
                                           class="text-decoration-none d-block p-3 rounded-3 hover-card">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="icon-wrapper bg-primary-subtle text-primary d-flex align-items-center justify-content-center rounded-circle me-2" 
                                                     style="width:40px;height:40px;">
                                                    <i class="bi bi-cpu"></i>
                                                </div>
                                                <h6 class="mb-0 fw-semibold">{{ $kat->ImeKategorija }}</h6>
                                            </div>
                                            <small class="text-muted">Istraži proizvode u kategoriji</small>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </li>
            </ul>

            <!-- Centered, wider search bar -->
            <form class="d-flex flex-grow-1 mx-lg-4 justify-content-center" action="{{ route('proizvodi.index') }}" method="GET" style="max-width: 700px;">
                <div class="input-group position-relative w-100">
                    <input type="text" name="search" class="form-control rounded-pill ps-4"
                           placeholder="Pretraži proizvode..." value="{{ request('search') }}">
                    <button class="btn btn-light rounded-pill position-absolute end-0 me-2" type="submit">
                        <i class="bi bi-search text-primary"></i>
                    </button>
                </div>
            </form>

            <!-- Right side icons -->
            <ul class="navbar-nav align-items-center ms-3 gap-3">
                <!-- Cart -->
                <li class="nav-item position-relative">
                    <a href="{{ route('cart.index') }}" class="nav-link position-relative text-white">
                        <i class="bi bi-cart3" style="font-size:1.4rem;"></i>
                        <span id="cart-count"
                              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ $cartCount > 0 ? '' : 'd-none' }}">
                            {{ $cartCount }}
                        </span>
                    </a>
                </li>

                <!-- Auth buttons -->
                @guest
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="btn btn-outline-light rounded-pill px-3">Prijava</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="btn text-white fw-semibold rounded-pill px-3"
                           style="background: linear-gradient(90deg, #ff7e5f, #fd3a69); border: none;">
                            Registracija
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white fw-semibold" href="#" id="userMenu" role="button"
                           data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> Bok, {{ Auth::user()->ime }} {{ Auth::user()->prezime }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li> <!-- popravi ovo je drop za profil -->
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Odjava</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<style>
/* Center navbar alignment */
.navbar .container-fluid {
    max-width: 1300px;
}
.navbar-collapse {
    flex-grow: 1;
    justify-content: center;
}
form.d-flex {
    flex-grow: 1;
}
.form-control {
    width: 100%;
}

/* Mega dropdown styling */
.mega-dropdown {
    left: 0;
    right: 0;
    border-top: 3px solid #0d6efd;
    animation: fadeIn 0.15s ease-in-out;
}
.hover-card {
    transition: all 0.2s ease;
}
.hover-card:hover {
    background-color: #f0f7ff;
    transform: translateY(-3px);
}
.icon-wrapper {
    background-color: rgba(13,110,253,0.1);
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(5px); }
    to { opacity: 1; transform: translateY(0); }
}

.mega-dropdown {
    left: 0;
    right: 0;
    border-top: 3px solid #0d6efd;
    animation: fadeIn 0.15s ease-in-out;
}
.hover-card {
    transition: all 0.2s ease;
}
.hover-card:hover {
    background-color: #f0f7ff;
    transform: translateY(-3px);
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(5px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const dropdown = document.querySelector(".kategorije-dropdown");
    const dropdownMenu = dropdown.querySelector(".dropdown-menu");
    const link = dropdown.querySelector("a.nav-link");

    // --- HOVER: show/hide mega menu ---
    if (window.innerWidth > 992) { // desktop only
        dropdown.addEventListener("mouseenter", () => {
            dropdown.classList.add("show");
            dropdownMenu.classList.add("show");
        });
        dropdown.addEventListener("mouseleave", () => {
            dropdown.classList.remove("show");
            dropdownMenu.classList.remove("show");
        });
    }

    // --- CLICK: go to proizvodi.index ---
    link.addEventListener("click", function (e) {
        // Prevent Bootstrap from toggling the dropdown on click
        e.stopPropagation();
        window.location.href = this.getAttribute("href");
    });
});
</script>

