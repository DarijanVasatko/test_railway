
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Admin — TechShop')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<script async src="https://www.googletagmanager.com/gtag/js?id=G-EVQERXLM84"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-EVQERXLM84');
</script>
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container d-flex justify-content-between">
            
            <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i>TechShop Admin
            </a>

            <ul class="navbar-nav flex-row">

                <li class="nav-item me-3">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link text-white">
                        <i class="bi bi-grid-fill me-1"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item me-3">
                    <a href="{{ route('admin.products.index') }}" class="nav-link text-white">
                        <i class="bi bi-box-seam me-1"></i> Proizvodi
                    </a>
                </li>

                <li class="nav-item me-3">
                    <a href="{{ route('admin.orders.index') }}" class="nav-link text-white">
                        <i class="bi bi-receipt me-1"></i> Narudžbe
                    </a>
                </li>

                <li class="nav-item me-3">
                    <a href="{{ route('admin.users.index') }}" class="nav-link text-white">
                        <i class="bi bi-people me-1"></i> Korisnici
                    </a>
                </li>

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

    <main class="container py-4">
        @yield('content')
    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const categorySelect = document.getElementById('productCategorySelect');
    const typeSelect     = document.getElementById('productTypeSelect');

    if (!categorySelect || !typeSelect) return;

    const allTypeOptions = Array.from(typeSelect.options);

    function filterTypesByCategory() {
        const selectedCatId = categorySelect.value;

        const currentValue = typeSelect.value;

        typeSelect.innerHTML = '';

        const placeholder = allTypeOptions.find(opt => opt.value === '');
        if (placeholder) {
            typeSelect.appendChild(placeholder);
        }

        allTypeOptions.forEach(opt => {
            if (!opt.value) return; 

            const optCatId = opt.dataset.categoryId || '';

            if (!selectedCatId || optCatId === selectedCatId) {
                typeSelect.appendChild(opt);
            }
        });

        const optionStillExists = Array.from(typeSelect.options)
            .some(opt => opt.value === currentValue);

        if (optionStillExists) {
            typeSelect.value = currentValue;
        } else {
            typeSelect.value = '';
        }
    }

    categorySelect.addEventListener('change', filterTypesByCategory);


    filterTypesByCategory();
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {


    document.querySelectorAll('.editProductBtn').forEach(btn => {
        btn.addEventListener('click', function () {

            const id        = this.dataset.id;
            const sifra     = this.dataset.sifra;
            const naziv     = this.dataset.naziv;
            const kratki    = this.dataset.kratkiopis;
            const opis      = this.dataset.opis;
            const cijena    = this.dataset.cijena;
            const zaliha    = this.dataset.zaliha;
            const kategorija = this.dataset.kategorija;
            const tip       = this.dataset.tip;
            const slika     = this.dataset.slika;


            document.getElementById('edit_sifra').value = sifra;
            document.getElementById('edit_naziv').value = naziv;
            document.getElementById('edit_kratkiopis').value = kratki;
            document.getElementById('edit_opis').value = opis;
            document.getElementById('edit_cijena').value = cijena;
            document.getElementById('edit_zaliha').value = zaliha;
            document.getElementById('edit_kategorija').value = kategorija;

            document.getElementById('edit_tip').value = tip;


            document.getElementById('edit_preview').src =
                slika ? "{{ asset('storage') }}/" + slika : "{{ asset('img/no-image.png') }}";


            document.getElementById('editProductForm').action =
                `/admin/products/${id}`;
        });
    });

});
</script>
<script>
document.querySelectorAll('.deleteProductBtn').forEach(btn => {
    btn.addEventListener('click', function () {
        const id   = this.dataset.id;
        const name = this.dataset.name;

        document.getElementById('deleteProductName').innerText = name;
        document.getElementById('deleteProductForm').action =
            `/admin/products/${id}`;
    });
});
</script>

</body>
</html>
