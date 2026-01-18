<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'TechShop')</title>

    <!-- CSRF token for AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            background-color: #f8f9fa;
        }

        .toast-top-center {
            position: fixed;
            top: 70px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1080;
            pointer-events: none;
        }

        .toast {
            pointer-events: auto;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
        }

        .toast-header {
            border-bottom: none;
        }

        .toast.fade {
            transition: opacity .25s ease, transform .25s ease;
        }

        .toast.hiding {
            opacity: 0;
            transform: translateY(-6px);
        }
    </style>
</head>
<body>
    <div id="app">
        @include('partials.navbar')

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Toast container (center-top) -->
    <div class="toast-top-center">
        <div id="globalToast" class="toast align-items-center text-white bg-primary fade" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-dark text-white">
                <i class="bi bi-info-circle me-2"></i>
                <strong class="me-auto">TechShop</strong>
                <small class="text-white-50">sad</small>
                <button type="button" class="btn-close btn-close-white ms-2 mb-1" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">Info poruka.</div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AJAX Add to Cart Script -->
    <script>
        (function () {
            const csrf = document.querySelector('meta[name="csrf-token"]').content;
            let inFlight = new WeakSet();

            function updateCartBadge(count) {
                const badge = document.getElementById('cart-count');
                if (!badge) return;
                badge.textContent = count;
                badge.classList.toggle('d-none', count <= 0);
            }

            function showToast(message, type = 'info') {
                const toastEl = document.getElementById('globalToast');
                if (!toastEl) return;
                const body = toastEl.querySelector('.toast-body');
                const icon = toastEl.querySelector('.toast-header i');

                const map = {
                    success: { color: 'bg-success', icon: 'bi-check-circle' },
                    error: { color: 'bg-danger', icon: 'bi-exclamation-triangle' },
                    info: { color: 'bg-primary', icon: 'bi-info-circle' }
                };

                toastEl.classList.remove('bg-success', 'bg-danger', 'bg-primary');
                toastEl.classList.add(map[type].color);
                icon.className = `bi ${map[type].icon} me-2`;
                body.textContent = message;

                const toast = new bootstrap.Toast(toastEl, { delay: 3500 });
                toast.show();
            }

            document.addEventListener('submit', async (e) => {
                const form = e.target;
                if (!form.classList.contains('js-add-to-cart')) return;
                e.preventDefault();

                if (inFlight.has(form)) return;
                inFlight.add(form);

                const fd = new FormData(form);
                if (!fd.has('quantity')) fd.set('quantity', '1');

                const button = form.querySelector('.js-add-to-cart-btn');
                const originalHTML = button ? button.innerHTML : '';
                if (button) {
                    button.disabled = true;
                    button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Dodavanje...';
                }

                try {
                    const res = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: fd
                    });
                    const data = await res.json();

                    if (!res.ok) throw new Error(data.message || 'Greška');

                    updateCartBadge(data.count ?? 0);
                    showToast(data.message || 'Proizvod dodan u košaricu!', 'success');
                } catch (err) {
                    showToast('Nešto je pošlo po zlu.', 'error');
                } finally {
                    if (button) {
                        button.disabled = false;
                        button.innerHTML = originalHTML;
                    }
                    setTimeout(() => inFlight.delete(form), 250);
                }
            });
        })();
    </script>
    <script>
document.addEventListener("DOMContentLoaded", function () {
    const dropdown = document.querySelector(".nav-item.dropdown");
    const dropdownMenu = dropdown.querySelector(".dropdown-menu");

    if (window.innerWidth > 992) { // Desktop only
        dropdown.addEventListener("mouseenter", () => {
            dropdown.classList.add("show");
            dropdownMenu.classList.add("show");
        });
        dropdown.addEventListener("mouseleave", () => {
            dropdown.classList.remove("show");
            dropdownMenu.classList.remove("show");
        });
    }
});
</script>
<!-- ⚙️ Adaptive smooth scroll -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("product-row");
    if (!container) return;

    // Clone content for seamless infinite scroll
    container.innerHTML += container.innerHTML;

    // 🧠 Adaptive speed based on screen size
    const getSpeed = () => window.innerWidth < 768 ? 0.6 : 0.3;

    let scrollSpeed = getSpeed();
    let isPaused = false;

    function smoothScroll() {
        if (!isPaused) {
            container.scrollLeft += scrollSpeed;
            if (container.scrollLeft >= container.scrollWidth / 2) {
                container.scrollLeft = 0;
            }
        }
        requestAnimationFrame(smoothScroll);
    }

    // Pause on hover
    container.addEventListener("mouseenter", () => isPaused = true);
    container.addEventListener("mouseleave", () => isPaused = false);

    // Update scroll speed on resize
    window.addEventListener("resize", () => {
        scrollSpeed = getSpeed();
    });

    smoothScroll();
});
</script>
</body>
</html>
