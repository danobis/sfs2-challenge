<?php
if (!isLoggedIn()) {
    header('Location: index.php?page=login');
    exit;
}

$cart_items = getCartItems();
if (empty($cart_items)) {
    header('Location: index.php?page=cart');
    exit;
}

$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
    $user_id = $_SESSION['user_id'];

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
?>

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title mb-4">Checkout</h2>

                    <h5 class="mb-3">Order Summary</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-end">Price</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($cart_items as $item): ?>
                                <tr>
                                    <td>
                                        <h6 class="mb-0"><?= htmlspecialchars($item['name']) ?></h6>
                                        <small class="text-muted"><?= htmlspecialchars($item['description']) ?></small>
                                    </td>
                                    <td class="text-end">€<?= number_format($item['price'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td class="text-end"><strong>Total:</strong></td>
                                <td class="text-end"><strong>€<?= number_format($total, 2) ?></strong></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Shipping Information</h5>
                    <p class="card-text text-muted">
                        Your order will be delivered to:
                    </p>
                    <?php
                    $user = getUserDetails($_SESSION['user']);
                    ?>
                    <div class="mb-3">
                        <strong><?= htmlspecialchars($_SESSION['user']) ?></strong><br>
                        <?= htmlspecialchars($user['email']) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Payment Summary</h5>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal:</span>
                        <span>€<?= number_format($total, 2) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Shipping:</span>
                        <span>Free</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <strong>Total:</strong>
                        <strong>€<?= number_format($total, 2) ?></strong>
                    </div>

                    <form method="POST">
                        <input type="hidden" name="confirm_order" value="1">
                        <button type="submit" class="btn btn-success w-100 mb-2">
                            <i class="fas fa-check me-2"></i>Confirm Order
                        </button>
                        <a href="index.php?page=cart" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-arrow-left me-2"></i>Back to Cart
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>