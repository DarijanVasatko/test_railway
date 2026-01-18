<?php
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    // Localhost credentials
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "web_trgovina";
} else {
    
    $host = "localhost"; 
    $user = "u201042929_web_trgovina";
    $pass = "XD/KnFz2?M0h";
    $dbname = "u201042929_web_trgovina";
}


$conn = mysqli_connect($host, $user, $pass, $dbname);


if (!$conn) {
    die("Greška pri spajanju na bazu: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");




// Funkcije za košaricu
if (!function_exists('getCartCount')) {
    function getCartCount($user_id, $connection) {
        if ($user_id) {
            $query = "SELECT COUNT(*) as count FROM kosarica WHERE korisnik_id = ?";
            $stmt = $connection->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc()['count'];
        } else {
            $guest_cart_key = 'guest_cart_' . session_id();
            return isset($_SESSION[$guest_cart_key]) ? count($_SESSION[$guest_cart_key]) : 0;
        }
    }
}

if (!function_exists('clearUserCart')) {
    function clearUserCart($user_id, $connection) {
        $query = "DELETE FROM kosarica WHERE korisnik_id = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }
}


?>

