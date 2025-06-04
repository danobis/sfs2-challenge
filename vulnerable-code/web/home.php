<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
$products = getProducts();
?>
<h2>Our Coffee Selection</h2>
<div class="row">
    <?php foreach ($products as $product): ?>
        <div class="col-md-4 mb-4">
            <div class="card">
                <?php if ($product['image']): ?>
                    <img src="/uploads/<?= $product['image'] ?>" class="card-img-top">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
                    <p class="card-text">€<?= number_format($product['price'], 2) ?></p>
                    <form method="POST" action="/cart.php">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit" class="btn btn-primary">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>