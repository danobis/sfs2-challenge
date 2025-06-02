<?php
if (!isLoggedIn() && isset($_POST['checkout'])) {
    header('Location: index.php?page=login');
    exit;
}
$cart_items = getCartItems();
$total = 0;
?>

<div class="container">
    <h2 class="mb-4">Shopping Cart</h2>

    <?php if (empty($cart_items)): ?>
        <div class="alert alert-info">
            <i class="fas fa-shopping-cart me-2"></i>
            Your cart is empty.
            <a href="index.php?page=home" class="alert-link">Continue shopping</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-md-8">
                <?php foreach ($cart_items as $item): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <?php if ($item['image']): ?>
                                        <img src="uploads/<?= htmlspecialchars($item['image']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($item['name']) ?>">
                                    <?php else: ?>
                                        <div class="text-center">
                                            <i class="fas fa-coffee fa-2x text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-5">
                                    <h5 class="card-title mb-1"><?= htmlspecialchars($item['name']) ?></h5>
                                    <p class="card-text text-muted"><?= htmlspecialchars($item['description']) ?></p>
                                </div>
                                <div class="col-md-3 text-end">
                                    <p class="h5 mb-0">€<?= number_format($item['price'], 2) ?></p>
                                    <?php $total += $item['price']; ?>
                                </div>
                                <div class="col-md-2 text-end">
                                    <form method="POST" action="index.php?action=remove_from_cart">
                                        <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal</span>
                            <span class="h5">€<?= number_format($total, 2) ?></span>
                        </div>
                        <?php if (isLoggedIn()): ?>
                            <form method="POST" action="index.php?page=checkout">
                                <button type="submit" class="btn btn-success w-100 mb-2">
                                    <i class="fas fa-lock me-2"></i>Proceed to Checkout
                                </button>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Please <a href="index.php?page=login" class="alert-link">login</a> to checkout.
                            </div>
                        <?php endif; ?>
                        <a href="index.php?page=home" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>