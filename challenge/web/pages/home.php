<?php
$products = getProducts();
?>

<div class="container">
    <h2 class="mb-4">Our Coffee Selection</h2>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($products as $product): ?>
            <div class="col">
                <div class="card h-100">
                        <div class="bg-light text-center p-4">
                            <i class="fas fa-coffee fa-3x text-muted"></i>
                        </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0">€<?= number_format($product['price'], 2) ?></span>
                            <form method="POST" action="index.php?action=add_to_cart">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>