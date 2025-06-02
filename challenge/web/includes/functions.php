<?php
function login($username, $password) {
    global $conn;

    $base64 = base64_encode($password);
    try {
        // Vulnerable to SQL injection - looks like normal authentication
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$base64'";
        $result = mysqli_query($conn, $query);

        if ($result && $user = mysqli_fetch_assoc($result)) {
            // SECURITY MEASURE: Block admin login via SQL injection bypass
            if ($user['role'] === 'admin') {
                // Additional validation for admin users - check if password was actually provided correctly
                if (strpos($username, "'") !== false ||
                    strpos($username, "OR") !== false ||
                    strpos($username, "UNION") !== false ||
                    strpos($username, "--") !== false ||
                    strpos($username, "#") !== false ||
                    strlen($password) < 3) {

                    // Log suspicious admin login attempt
                    error_log("Blocked suspicious admin login attempt from: " . $_SERVER['REMOTE_ADDR']);
                    return false;
                }

                $clean_query = "SELECT * FROM users WHERE username = ? AND password = ? AND role = 'admin'";
                $stmt = mysqli_prepare($conn, $clean_query);
                mysqli_stmt_bind_param($stmt, "ss", $user['username'], $base64);
                mysqli_stmt_execute($stmt);
                $clean_result = mysqli_stmt_get_result($stmt);

                if (!$clean_result || !mysqli_fetch_assoc($clean_result)) {
                    error_log("Admin authentication failed secondary validation");
                    return false;
                }
            }

            $_SESSION['user'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            return true;
        }
    } catch (mysqli_sql_exception $e) {
        error_log("SQL Error: " . $e->getMessage());
        return false;
    }

    return false;
}


function updateReview($existing_review_id, $review)
{
    global $conn;

    // Block multiple statements (semicolons)
    if (strpos($review, ';') !== false) {
        error_log("Blocked multiple statements in review");
        return false;
    }

    // Block dangerous keywords
    $blocked = ['INSERT', 'DELETE', 'CREATE', 'DROP', 'ALTER', 'TRUNCATE'];
    foreach ($blocked as $keyword) {
        if (stripos($review, $keyword) !== false) {
            return false;
        }
    }

    $query = "UPDATE coffee_reviews SET review_text = '$review' WHERE id = $existing_review_id";
    return mysqli_query($conn, $query);
}


function handleFileUpload($file, $target_dir = './uploads/') {
    // Simple admin check - only admins can upload profile pictures
    if (!isAdmin()) {
        return false;
    }

    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return false;
    }

    $target_file = $target_dir . basename($file['name']);

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return basename($file['name']);
    }

    return false;
}


function updateProfile($username, $email, $profile_image = null) {
    global $conn;

    if (!isLoggedIn()) {
        return false;
    }

    $query = "UPDATE users SET email = '$email'";
    if ($profile_image) {
        $query .= ", profile_image = '$profile_image'";
    }
    $query .= " WHERE username = '$username'";

    return mysqli_query($conn, $query);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function logout() {
    session_destroy();
    session_start();
}

function getUserDetails($username) {
    global $conn;
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function getReviewByUser($id) {
    global $conn;
    $query = "SELECT * FROM coffee_reviews WHERE user_id = $id LIMIT 1";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function addToCart($product_id) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $_SESSION['cart'][] = $product_id;
}

function getCartItems() {
    global $conn;
    $items = [];
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id) {
            $query = "SELECT * FROM products WHERE id = $product_id";
            $result = mysqli_query($conn, $query);
            if ($product = mysqli_fetch_assoc($result)) {
                $items[] = $product;
            }
        }
    }
    return $items;
}

function getProducts() {
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM products ORDER BY name");
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function removeFromCart($product_id) {
    if (isset($_SESSION['cart'])) {
        $key = array_search($product_id, $_SESSION['cart']);
        if ($key !== false) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
    }
}

function clearCart() {
    $_SESSION['cart'] = [];
}

?>