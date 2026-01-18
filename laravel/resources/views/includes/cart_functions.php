<?php
// cart_functions.php

function getCartKey() {
    if (isset($_SESSION['user_id'])) {
        return 'user_cart_' . $_SESSION['user_id'];
    }
    return 'guest_cart_' . session_id();
}

function loadCartItems($conn) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("
            SELECT p.Proizvod_ID, p.naziv, p.sifra, p.cijena, p.slika, k.kolicina
            FROM kosarica k
            JOIN proizvod p ON p.Proizvod_ID = k.proizvod_id
            WHERE k.korisnik_id = ?
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $cart = [];
        while ($row = $res->fetch_assoc()) {
            $cart[$row['Proizvod_ID']] = [
                'naziv' => $row['naziv'],
                'sifra' => $row['sifra'],
                'cijena' => floatval($row['cijena']),
                'slika' => $row['slika'],
                'kolicina' => $row['kolicina'],
            ];
        }
        $stmt->close();
        return $cart;
    } else {
        $guest_cart_key = getCartKey();
        return $_SESSION[$guest_cart_key] ?? [];
    }
}

function saveCartItems($conn, $cart) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        
        // Clear all items for this user first
        $stmt = $conn->prepare("DELETE FROM kosarica WHERE korisnik_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
        
        // Insert current items
        foreach ($cart as $product_id => $item) {
            $product_id = intval($product_id);
            if ($item['kolicina'] > 0) {
                $stmt = $conn->prepare("INSERT INTO kosarica (korisnik_id, proizvod_id, kolicina) VALUES (?, ?, ?)");
                $stmt->bind_param("iii", $user_id, $product_id, $item['kolicina']);
                $stmt->execute();
                $stmt->close();
            }
        }
    } else {
        $guest_cart_key = getCartKey();
        $_SESSION[$guest_cart_key] = $cart;
    }
}

function calculateTotals($cart) {
    $subtotal = 0.0;
    foreach ($cart as $item) {
        $subtotal += $item['cijena'] * $item['kolicina'];
    }
    $tax = $subtotal * 0.25; // 25% PDV
    $delivery = 2.89; // Fixed delivery fee
    $total = $subtotal + $tax + $delivery;
    
    return [
        'subtotal' => number_format($subtotal, 2, ',', '.'),
        'tax' => number_format($tax, 2, ',', '.'),
        'delivery' => number_format($delivery, 2, ',', '.'),
        'total' => number_format($total, 2, ',', '.'),
    ];
}