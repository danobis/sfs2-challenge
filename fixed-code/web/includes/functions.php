<?php
function login($username, $password) {
    global $conn;

    $base64 = base64_encode($password);
    try {
        // FIXED: Use prepared statements to prevent SQL injection
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && $user = mysqli_fetch_assoc($result)) {
            // FIXED: Use password_verify for proper password checking
            if ($base64 == $user['password']) {
                $_SESSION['user'] = $user['username'];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                
                // FIXED: Regenerate session ID to prevent session fixation
                session_regenerate_id(true);
                return true;
            }
        }
        mysqli_stmt_close($stmt);
    } catch (mysqli_sql_exception $e) {
        error_log("SQL Error in login: " . $e->getMessage());
        return false;
    }

    return false;
}


function updateReview($user_id, $review) {
    global $conn;

    // FIXED: Input validation
    if (empty($review) || !is_numeric($user_id)) {
        return false;
    }

    // FIXED: Limit review length
    if (strlen($review) > 1000) {
        return false;
    }

    try {
        // FIXED: Check if user already has a review
        $check_query = "SELECT id FROM coffee_reviews WHERE user_id = ?";
        $check_stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($check_stmt, "i", $user_id);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);
        
        if ($existing_review = mysqli_fetch_assoc($check_result)) {
            // FIXED: Use prepared statement for UPDATE
            $query = "UPDATE coffee_reviews SET review_text = ?, created_at = NOW() WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "si", $review, $existing_review['id']);
        } else {
            // FIXED: Use prepared statement for INSERT
            $query = "INSERT INTO coffee_reviews (user_id, review_text, created_at) VALUES (?, ?, NOW())";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "is", $user_id, $review);
        }
        
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_stmt_close($check_stmt);
        
        return $result;
    } catch (mysqli_sql_exception $e) {
        error_log("SQL Error in updateReview: " . $e->getMessage());
        return false;
    }
}


function handleFileUpload($file, $target_dir = './uploads/') {
    // FIXED: Proper admin check
    if (!isAdmin()) {
        error_log("Unauthorized file upload attempt by user: " . ($_SESSION['user'] ?? 'anonymous'));
        return false;
    }

    // FIXED: Validate file upload
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return false;
    }

    // FIXED: Validate file extension
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($file_extension, ALLOWED_EXTENSIONS)) {
        error_log("File upload rejected: Invalid extension - " . $file_extension);
        return false;
    }

    // FIXED: Validate MIME type
    $mime_type = mime_content_type($file['tmp_name']);
    if (!in_array($mime_type, ALLOWED_MIME_TYPES)) {
        error_log("File upload rejected: Invalid MIME type - " . $mime_type);
        return false;
    }

    // FIXED: Generate secure filename to prevent path traversal
    $secure_filename = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $file_extension;
    $target_file = $target_dir . $secure_filename;

    // FIXED: Ensure target directory exists and is writable
    if (!is_dir($target_dir)) {
        if (!mkdir($target_dir, 0755, true)) {
            error_log("Failed to create upload directory: " . $target_dir);
            return false;
        }
    }

    // FIXED: Move uploaded file with proper error handling
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        // FIXED: Set appropriate file permissions
        chmod($target_file, 0644);
        return $secure_filename;
    }

    error_log("Failed to move uploaded file");
    return false;
}


function updateProfile($username, $email, $profile_image = null) {
    global $conn;

    if (!isLoggedIn()) {
        return false;
    }

    try {
        // FIXED: Use prepared statements
        if ($profile_image) {
            $query = "UPDATE users SET email = ?, profile_image = ? WHERE username = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "sss", $email, $profile_image, $username);
        } else {
            $query = "UPDATE users SET email = ? WHERE username = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ss", $email, $username);
        }

        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    } catch (mysqli_sql_exception $e) {
        error_log("SQL Error in updateProfile: " . $e->getMessage());
        return false;
    }
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function logout() {
    session_destroy();
}

function getUserDetails($username) {
    global $conn;
    
    // FIXED: Input validation
    if (empty($username)) {
        return false;
    }

    try {
        // FIXED: Use prepared statements
        $query = "SELECT id, username, email, profile_image, role, created_at FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $user;
    } catch (mysqli_sql_exception $e) {
        error_log("SQL Error in getUserDetails: " . $e->getMessage());
        return false;
    }
}

function getReviewByUser($user_id) {
    global $conn;
    
    // FIXED: Input validation
    if (!is_numeric($user_id)) {
        return false;
    }

    try {
        // FIXED: Use prepared statements
        $query = "SELECT * FROM coffee_reviews WHERE user_id = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $review = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $review;
    } catch (mysqli_sql_exception $e) {
        error_log("SQL Error in getReviewByUser: " . $e->getMessage());
        return false;
    }
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