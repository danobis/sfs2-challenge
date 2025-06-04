<?php
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title mb-4">Register</h2>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Registration temporarily disabled</strong>
                        <p class="mb-0 mt-2">Due to a recent security audit, new registrations are currently disabled.
                            Please contact the administrator for a new account.</p>
                    </div>

                    <form>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" disabled>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" disabled>
                            <i class="fas fa-user-plus me-2"></i>Register
                        </button>
                    </form>

                    <div class="mt-3 text-center">
                        <p class="text-muted">Already have an account?
                            <a href="index.php?page=login">Login here</a>
                        </p>
                    </div>

                    <!-- Hidden comment for CTF -->
                    <!-- Registration disabled after security incident #1337. Using existing accounts only. -->
                </div>
            </div>
        </div>
    </div>
</div>