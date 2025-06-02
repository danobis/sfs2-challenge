<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    addToCart($_POST['product_id']);
    header('Location: ?page=cart');
    exit;
}


$cart_items = getCartItems();
$total = 0;

?>
<div class="container mt-4">
    <h2>Shopping Cart</h2>

    <?php if (empty($cart_items)): ?>
        <div class="alert alert-info">Your cart is empty.</div>
    <?php else: ?>
        <div class="row">
            <div class="col-md-8">
                <?php foreach ($cart_items as $item): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5 class="card-title"><?= htmlspecialchars($item['name']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($item['description']) ?></p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <p class="h5">€<?= number_format($item['price'], 2) ?></p>
                                    <?php $total += $item['price']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Order Summary</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total:</span>
                            <span class="h5">€<?= number_format($total, 2) ?></span>
                        </div>
                        <form method="POST" action="?page=checkout">
                            <?php if (!isset($_SESSION['user'])): ?>
                                <div class="mb-3">
                                    <label class="form-label">Email for order tracking</label>
                                    <input type="email" name="guest_email" class="form-control" required>
                                </div>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-success w-100">
                                Proceed to Checkout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>