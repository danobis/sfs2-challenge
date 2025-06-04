<?php
$user = getUserDetails($_SESSION['user']);
$review = getReviewByUser($_SESSION['user_id']);
$message = '';

// Handle messages from redirects
if (isset($_GET['message'])) {
    switch($_GET['message']) {
        case 'profile_updated':
            $message = '<div class="alert alert-success"><i class="fas fa-check me-2"></i>Profile picture updated successfully!</div>';
            break;
        case 'email_updated':
            $message = '<div class="alert alert-success"><i class="fas fa-check me-2"></i>Email address updated successfully!</div>';
            break;
        case 'review_updated':
            $message = '<div class="alert alert-success"><i class="fas fa-check me-2"></i>Thank you for your review!</div>';
            break;
        case 'upload_failed':
            $message = '<div class="alert alert-danger"><i class="fas fa-times me-2"></i>Failed to upload image. Please try again.</div>';
            break;
        case 'access_denied':
            $message = '<div class="alert alert-danger"><i class="fas fa-lock me-2"></i>Access denied! Only administrators can upload files.</div>';
            break;
        case 'review_failed':
            $message = '<div class="alert alert-danger"><i class="fas fa-times me-2"></i>Failed to save review. Please try again.</div>';
            break;
		case 'unknown_error':
			$message = '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle me-2"></i>Something went wrong. Please try again.</div>';
			break;
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <!-- Profile Card -->
            <div class="card">
                <div class="card-body text-center">
                    <?php if ($user['profile_image']): ?>
                        <img src="uploads/<?= htmlspecialchars($user['profile_image']) ?>"
                             class="rounded-circle mb-3" width="120" height="120"
                             style="object-fit: cover;" alt="Profile Picture">
                    <?php else: ?>
                        <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                             style="width: 120px; height: 120px;">
                            <i class="fas fa-user fa-3x text-muted"></i>
                        </div>
                    <?php endif; ?>

                    <h5 class="card-title"><?= htmlspecialchars($user['username']) ?></h5>
                    <p class="text-muted"><?= htmlspecialchars($user['email']) ?></p>

                    <?php if (isAdmin()): ?>
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-crown me-1"></i>Premium Member
                        </span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Regular Customer</span>
                    <?php endif; ?>

                    <hr>
                    <small class="text-muted">
                        Member since <?= date('F Y', strtotime($user['created_at'])) ?>
                    </small>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title">Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <a href="index.php?page=orders" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-receipt me-2"></i>My Orders
                        </a>
                        <a href="index.php?page=home" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-shopping-cart me-2"></i>Shop Coffee
                        </a>
                        <a href="index.php?page=logout" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-sign-out-alt me-2"></i>Sign Out
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <?= $message ?>

            <!-- Account Settings -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Account Settings</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" disabled>
                                    <small class="text-muted">Username cannot be changed</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           value="<?= htmlspecialchars($user['email']) ?>">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                    </form>
                </div>
            </div>

            <!-- Profile Picture Upload -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-camera me-2"></i>Profile Picture</h5>
                </div>
                <div class="card-body">
                    <?php if (isAdmin()): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-crown me-2"></i>
                            <strong>Administrator Access</strong> - File upload functionality enabled.
                        </div>
                        <p class="text-muted mb-3">Upload a new profile picture to personalize your account.</p>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <input type="file" class="form-control" name="profile_image"
                                       accept="image/jpeg,image/jpg,image/png,image/gif"
                                       maxlength="5242880">
                                <small class="text-muted">Maximum file size: 5MB. All file types accepted for administrators.</small>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-upload me-2"></i>Upload Picture
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-lock me-2"></i>
                            <strong>Access Restricted</strong>
                        </div>
                        <div class="text-center py-4">
                            <i class="fas fa-user-shield fa-2x text-muted mb-3"></i>
                            <p class="text-muted">File upload functionality is restricted to administrators only.</p>
                            <small class="text-muted">Contact your system administrator to request upload privileges.</small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Customer Review -->
            <!-- TODO: Review data gets inserted into coffee_reviews table, review_text column - @john_the_dev remember to remove this comment before production!!! -->
			<!-- "UPDATE coffee_reviews SET review_text = '$review' WHERE id = $existing_review_id"; -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-star me-2"></i>Share Your Experience</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Tell other coffee lovers about your experience with our products and service.</p>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="review" class="form-label">Your Review</label>
                            <textarea class="form-control" id="review" name="review" rows="4"
                                      placeholder="What did you think of our coffee? How was your experience?"><?= htmlspecialchars($review['review_text'] ?? '') ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-star me-2"></i>
                            <?= $review ? 'Update Review' : 'Submit Review' ?>
                        </button>
                    </form>

                    <?php if ($review): ?>
                        <hr>
                        <small class="text-muted">
                            Last updated: <?= date('F j, Y', strtotime($review['created_at'])) ?>
                        </small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>