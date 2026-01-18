<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
html, body {
    height: 100%;
}
body {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}
.footer-fixed {
    margin-top: auto;
    width: 100%;
}
.social-icons {
    display: flex;
    gap: 15px;
    margin-top: 15px;
}

.social-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    background: rgba(255,255,255,0.1);
    font-size: 18px;
}

.social-icon:hover {
    transform: translateY(-3px);
    text-decoration: none;
}

/* Pojedinačne boje ikona na hover */
.social-icon:hover.fa-facebook-f { background: #3b5998; }
.social-icon:hover.fa-instagram { background: #e4405f; }
.social-icon:hover.fa-x-twitter { background: #000000; }
.social-icon:hover.fa-youtube { background: #cd201f; }
</style>
</head>

  <footer class="bg-dark text-white pt-5 pb-4 footer-fixed">
    <div class="container">
        <div class="row">
            <div class="col-md-3 mb-4">
                <h5>TechShop</h5>
                <p>Vodeći hrvatski online dućan za tehnologiju i gadgete.</p>
                <div class="social-icons">
    <!-- Facebook -->
    <a href="https://facebook.com/yourpage" class="text-white me-3 social-icon" target="_blank">
        <i class="fab fa-facebook-f"></i>
    </a>
    <!-- Instagram -->
    <a href="https://instagram.com/yourprofile" class="text-white me-3 social-icon" target="_blank">
        <i class="fab fa-instagram"></i>
    </a>
    <!-- Twitter/X -->
    <a href="https://x.com/yourhandle" class="text-white me-3 social-icon" target="_blank">
        <i class="fa-brands fa-x-twitter"></i>
    </a>
    <!-- YouTube -->
    <a href="https://youtube.com/yourchannel" class="text-white social-icon" target="_blank">
        <i class="fab fa-youtube"></i>
    </a>
</div>
            </div>
            <div class="col-md-3 mb-4">
                <h5>Informacije</h5>
                <ul class="list-unstyled">
                    <li><a href="o-nama.php" class="text-white">O nama</a></li>
                    <li><a href="#" class="text-white">Naš tim</a></li>
                    <li><a href="#" class="text-white">Blog</a></li>
                    <li><a href="#" class="text-white">Karijere</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h5>Korisnički servis</h5>
                <ul class="list-unstyled">
                    <li><a href="kontakt.php" class="text-white">Kontakt</a></li>
                    <li><a href="#" class="text-white">Česta pitanja</a></li>
                    <li><a href="#" class="text-white">Dostava</a></li>
                    <li><a href="#" class="text-white">Reklamacije</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h5>Kontaktirajte nas</h5>
                <ul class="list-unstyled">
                    <li><i class="fas fa-map-marker-alt me-2"></i> Ulica kralja Zvonimira 58, Zagreb</li>
                    <li><i class="fas fa-phone me-2"></i> +385 1 4567 890</li>
                    <li><i class="fas fa-envelope me-2"></i> info@techshop.hr</li>
                </ul>
            </div>
        </div>
        <hr class="my-4">
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0">&copy; <?= date('Y') ?> TechShop. Sva prava pridržana.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <img src="../dodatne_slike/googlepay-logo.png" alt="GooglePay" width="65" height="45" class="me-2">
                <img src="..//dodatne_slike/visa.png" alt="Visa" width="65" height="45" class="me-2">
                <img src="..//dodatne_slike/mastercard.png" alt="Mastercard" width="65" height="45" class="me-2">
                <img src="..//dodatne_slike/diners.png" alt="Diners" width="65" height="45">
            </div>
        </div>
    </div>
</footer>
  <script src="https://kit.fontawesome.com/a076d05399.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</html>