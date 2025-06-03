<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once './includes/config.php';
require_once './includes/functions.php';

// Handle logout
if (isset($_GET['page']) && $_GET['page'] === 'logout') {
    logout();
    header('Location: index.php?page=home');
    exit;
}

// Handle login processing
if (isset($_GET['page']) && $_GET['page'] === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        if (login($_POST['username'], $_POST['password'])) {
            header('Location: index.php?page=home');
            exit;
        }
    }
}

// Handle profile processing
if (isset($_GET['page']) && $_GET['page'] === 'profile' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isLoggedIn()) {
        header('Location: index.php?page=login');
        exit;
    }
    
    $success = false;
    
    // Handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
        if (isAdmin()) {
            $uploaded_file = handleFileUpload($_FILES['profile_image']);
            if ($uploaded_file) {
                updateProfile($_SESSION['user'], $_POST['email'] ?? getUserDetails($_SESSION['user'])['email'], $uploaded_file);
                $success = true;
                $message = 'profile_updated';
            } else {
                $message = 'upload_failed';
            }
        } else {
            $message = 'access_denied';
        }
    }
    // Handle email update
    elseif (isset($_POST['email'])) {
        $user = getUserDetails($_SESSION['user']);
        updateProfile($_SESSION['user'], $_POST['email'], $user['profile_image']);
        $success = true;
        $message = 'email_updated';
    }
    // Handle review update
    elseif (isset($_POST['review'])) {
        if (updateReview($_SESSION['user_id'], $_POST['review'])) {
            $success = true;
            $message = 'review_updated';
        } else {
            $message = 'review_failed';
        }
    }
    
    // Redirect to prevent form resubmission
    header('Location: index.php?page=profile&message=' . $message);
    exit;
}

// Handle checkout processing
if (isset($_GET['page']) && $_GET['page'] === 'checkout') {
    if (!isLoggedIn()) {
        header('Location: index.php?page=login');
        exit;
    }
    
    $cart_items = getCartItems();
    if (empty($cart_items)) {
        header('Location: index.php?page=cart');
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
        $user_id = $_SESSION['user_id'];
        $total = 0;
        foreach ($cart_items as $item) {
            $total += $item['price'];
        }

        // Vulnerable to SQL injection (for CTF purposes)
        $query = "INSERT INTO orders (user_id, total_amount, status, created_at) 
                  VALUES ($user_id, $total, 'pending', NOW())";

        if (mysqli_query($conn, $query)) {
            $order_id = mysqli_insert_id($conn);

            foreach ($cart_items as $item) {
                $product_id = $item['id'];
                $price = $item['price'];
                mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price_at_time) 
                                    VALUES ($order_id, $product_id, 1, $price)");
            }

            $_SESSION['cart'] = [];
            header('Location: index.php?page=orders&message=order_success');
            exit;
        }
    }
}

$action = $_GET['action'] ?? null;
if ($action) {
    switch($action) {
        case 'add_to_cart':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
                addToCart($_POST['product_id']);
                header('Location: index.php?page=cart');
                exit;
            }
            break;

        case 'remove_from_cart':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
                removeFromCart($_POST['product_id']);
                header('Location: index.php?page=cart');
                exit;
            }
            break;

        case 'clear_cart':
            clearCart();
            header('Location: index.php?page=cart');
            exit;
            break;
        case 'logout':
            logout();
            header('Location: index.php?page=home');
            exit;
            break;
    }
}

$page = $_GET['page'] ?? 'home';
$valid_pages = ['home', 'cart', 'login', 'logout', 'profile', 'orders', 'checkout', 'register'];
$page = in_array($page, $valid_pages) ? $page : 'home';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1 0 auto;
        }

        footer {
            flex-shrink: 0;
        }
    </style>
</head>
<body>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-coffee"></i> Coffee Shop
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=home">Menu</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=cart">
                        <i class="fas fa-shopping-cart"></i> Cart
                        <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                            <span class="badge bg-danger"><?= count($_SESSION['cart']) ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['user']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="index.php?page=profile">Profile</a></li>
                            <li><a class="dropdown-item" href="index.php?page=orders">Orders</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="index.php?page=logout">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=login">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<main class="py-4">
    <?php include "./pages/$page.php"; ?>
</main>

<!-- Footer -->
<footer class="bg-dark text-light mt-5">
    <div class="container py-4">
        <div class="row">
            <div class="col-md-6">
                <h5>Coffee Shop</h5>
                <p>The best coffee in town!</p>
            </div>
            <div class="col-md-6 text-md-end">
                <h5>Contact</h5>
                <p>Email: info@coffeeshop.local</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>