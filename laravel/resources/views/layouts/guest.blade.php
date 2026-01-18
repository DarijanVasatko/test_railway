<!doctype html>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TechShop - Prijava</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
        }

        .auth-card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 6px 25px rgba(0,0,0,0.1);
        }

        .btn-techshop {
            background-color: #0d6efd;
            color: white;
        }

        .btn-techshop:hover {
            background-color: #0b5ed7;
        }

        a {
            color: #0d6efd;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .card.auth-card input.form-control {
    border-radius: 0.5rem;
}

.card.auth-card input.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13,110,253,0.25);
}
 body {
        background: linear-gradient(135deg, #0d6efd, #6610f2);
        background-attachment: fixed;
    }
    .card {
        background: #fff;
        border-radius: 1rem;
        transition: 0.3s ease;
    }
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <div class="container py-5">
        {{ $slot }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
