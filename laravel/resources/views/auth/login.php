<?php
include ("../includes/db_conn.php");
session_start();

$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = $_POST['email']; // could be username or email
    $password = $_POST['password'];
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // First check if it's a username or email
    $is_email = filter_var($input, FILTER_VALIDATE_EMAIL);

    // Initialize admin username variable
    $admin_username = null;
    $admin_found = false;

    if ($is_email) {
        // Try to find admin by email
        $getUsername = $conn->prepare("SELECT email FROM admin_users WHERE email = ?");
        $getUsername->bind_param("s", $input);
        $getUsername->execute();
        $getUsername->store_result();
        
        if ($getUsername->num_rows > 0) {
            $getUsername->bind_result($admin_username);
            $getUsername->fetch();
            $admin_found = true;
        }
        $getUsername->close();
    } else {
        // Check if username exists in admin table
        $checkUsername = $conn->prepare("SELECT username FROM admin_users WHERE username = ?");
        $checkUsername->bind_param("s", $input);
        $checkUsername->execute();
        $checkUsername->store_result();
        
        if ($checkUsername->num_rows > 0) {
            $admin_username = $input;
            $admin_found = true;
        }
        $checkUsername->close();
    }

    // Try admin login if admin was found
    if ($admin_found=true) {
        try {
            $stmt = $conn->prepare("CALL admin_login(?, ?, ?)");
            $stmt->bind_param("sss", $admin_username, $password, $ip_address);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $admin = $result->fetch_assoc();
                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['username'] = $admin['username'];
                $_SESSION['role'] = $admin['role'];
                $_SESSION['email'] = $admin['email'];

                header("Location: ../admin/admin_dashboard.php");
                exit();
            }
        } catch (mysqli_sql_exception $e) {
            // If procedure fails, continue to user login
            $message = "Invalid username/email or password";
            $toastClass = "bg-danger";
        }
    }

    // User login - only by email
    $stmt = $conn->prepare("SELECT Kupac_ID, Lozinka, Ime, Prezime FROM kupac WHERE Email = ?");
    $stmt->bind_param("s", $input);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $conn_password, $ime, $prezime);
        $stmt->fetch();

        if (password_verify($password, $conn_password)) {
            $_SESSION['Email'] = $input;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['Ime'] = $ime;
            $_SESSION['Prezime'] = $prezime;
            $_SESSION['role'] = "user";

            header("Location: ../index.php");
            exit();
        } else {
            $message = "Pogrešna lozinka";
            $toastClass = "bg-danger";
        }
    } else if (!$admin_found) {
        $message = "Korisničko ime/email nije pronađen";
        $toastClass = "bg-warning";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="https://cdn-icons-png.flaticon.com/512/295/295128.png">
    <title>Prijava | TechShop</title>
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --accent-color: #2e59d9;
            --text-color: #5a5c69;
        }
        
        body {
            background-color: var(--secondary-color);
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--text-color);
        }
        
        .login-container {
            max-width: 400px;
            margin: 5rem auto;
            animation: fadeIn 0.5s ease-in-out;
        }
        
        .login-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            color: white;
            text-align: center;
            padding: 1.5rem;
            border-bottom: none;
        }
        
        .card-body {
            padding: 2rem;
            background-color: white;
        }
        
        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 0.35rem;
            border: 1px solid #d1d3e2;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .btn-login {
            background-color: var(--primary-color);
            border: none;
            width: 100%;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            background-color: var(--accent-color);
            transform: translateY(-2px);
        }
        
        .login-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
            color: white;
        }
        
        .links a {
            color: var(--primary-color);
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .links a:hover {
            color: var(--accent-color);
            text-decoration: underline;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .input-group-text {
            background-color: #f8f9fc;
            border: 1px solid #d1d3e2;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-container">
            <div class="card login-card">
                <div class="card-header">
                    <i class="fas fa-user-circle login-icon"></i>
                    <h3 class="mb-0">Dobrodošli natrag!</h3>
                </div>
                <div class="card-body">
                    <?php if ($message): ?>
                    <div class="alert <?php echo $toastClass; ?> text-white alert-dismissible fade show" role="alert">
                        <?php echo $message; ?>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    
                    <form action="" method="post">
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">Email adresa ili korisničko ime</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" name="email" id="email" class="form-control" placeholder="unesite@email.com ili korisničko ime" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold">Lozinka</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Unesite lozinku" required>
                            </div>
                        </div>
                        
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Zapamti me</label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-login btn-block mb-4">
                            <i class="fas fa-sign-in-alt me-2"></i> Prijavi se
                        </button>
                        
                        <div class="text-center links">
                            <a href="./resetpassword.php" class="me-3">Zaboravili ste lozinku?</a>
                            <a href="./register.php">Registrirajte se</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Automatski prikazivanje toast poruke
        document.addEventListener('DOMContentLoaded', function() {
            var alertEl = document.querySelector('.alert');
            if (alertEl) {
                var alert = new bootstrap.Alert(alertEl);
                setTimeout(function() {
                    alert.close();
                }, 5000);
            }
        });
    </script>
</body>

</html>