<?php
if (!isLoggedIn()) {
    header('Location: index.php?page=login');
    exit;
}

$message = isset($_GET['message']) && $_GET['message'] === 'order_success'
    ? '<div class="alert alert-success">Your order has been placed successfully!</div>'
    : '';

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<div class="container">
    <h2 class="mb-4">My Orders</h2>

    <?= $message ?>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            You haven't placed any orders yet.
            <a href="index.php?page=home" class="alert-link">Start shopping</a>
        </div>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <strong>Order #<?= $order['id'] ?></strong>
                        </div>
                        <div class="col text-center">
                            <?php
                            $status_class = match($order['status']) {
                                'pending' => 'warning',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                                default => 'secondary'
                            };
                            ?>
                            <span class="badge bg-<?= $status_class ?>">
                                <?= ucfirst(htmlspecialchars($order['status'])) ?>
                            </span>
                        </div>
                        <div class="col text-end">
                            <small class="text-muted">
                                <?= date('F j, Y, g:i a', strtotime($order['created_at'])) ?>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                    $order_id = $order['id'];
                    $items_query = "SELECT oi.*, p.name, p.description 
                                  FROM order_items oi 
                                  JOIN products p ON oi.product_id = p.id 
                                  WHERE oi.order_id = $order_id";
                    $items_result = mysqli_query($conn, $items_query);
                    $items = mysqli_fetch_all($items_result, MYSQLI_ASSOC);
                    ?>

                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Quantity</th>
                                <th class="text-end">Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>
                                        <h6 class="mb-0"><?= htmlspecialchars($item['name']) ?></h6>
                                        <small class="text-muted"><?= htmlspecialchars($item['description']) ?></small>
                                    </td>
                                    <td class="text-end">€<?= number_format($item['price_at_time'], 2) ?></td>
                                    <td class="text-end"><?= $item['quantity'] ?></td>
                                    <td class="text-end">€<?= number_format($item['price_at_time'] * $item['quantity'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td class="text-end"><strong>€<?= number_format($order['total_amount'], 2) ?></strong></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>