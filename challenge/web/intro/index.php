<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secret Flag Service - Coffee Shop Challenge</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card">
        <div class="card-body">
            <h1 class="mb-4">The Case of the Suspicious Coffee Shop</h1>

            <div class="alert alert-info">
                <h5>Mission Brief - <?php echo date('Y-m-d');  ?></h5>
                <p>Agent,</p>
                <p>The Secret Flag Service (SFS) has detected suspicious activities at a local coffee shop's web
                    application. Our intelligence suggests that the shop owner, known only as "HeadOfCoffee",
                    might be hiding sensitive information in their database and file system.</p>

                <p>Initial reconnaissance indicates poor security practices - SQL injection vulnerabilities and
                    unsafe file uploads have been detected. This could be the perfect cover for something more
                    sinister.</p>

                <p>Your mission, should you choose to accept it, is to infiltrate their systems and recover any
                    hidden flags. All our flags follow the pattern: <code>CTF{some-text}</code></p>
            </div>

            <h4 class="mt-4">Mission Objectives</h4>
            <ol>
                <li><strong>Database Infiltration</strong>: Access the admin account password by exploiting SQL
                    vulnerabilities
                </li>
                <li><strong>File System Reconnaissance</strong>: Extract the hidden flag from their server</li>
            </ol>

            <div class="mt-4">
                <a href="/index.php" class="btn btn-primary">Start Mission</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>