<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secret Flag Service - Coffee Shop Challenge</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<style>
        .blur-content {
            filter: blur(5px);
            pointer-events: none;
            user-select: none;
        }
        .overlay-warning {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            width: 90%;
            max-width: 500px;
        }
    </style>
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
			
			<button class="btn btn-secondary mt-4" onclick="toggleHints()">Field Support</button>
            <div id="hints" class="mt-3" style="display:none">
                <div class="card bg-light">
                    <div class="card-body blur-content">
                        <h5>Intelligence Report</h5>
                        <ul>
                            <li>Login bypass might be possible</li>
                            <li>It's may be possible to query database tables via the review system.</li>
                            <li>Plain text passwords are bad, encoding is slightly better.</li>
                            <li>User profile images are stored directly on the server.</li>
                            <li>File type validation is suspiciously absent.</li>
							<li>php has a dangerous command called 'system'. Maybe its possible to post a html input form with that command</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="/index.php" class="btn btn-primary">Start Mission</a>
            </div>
        </div>
    </div>
</div>
<!-- Warning Overlays -->
<div id="hints-warning" class="overlay-warning alert alert-warning">
    <h4>Are you sure?</h4>
    <p>Using hints might make the challenge less rewarding. Do you want to continue?</p>
    <div class="d-flex justify-content-end gap-2">
        <button class="btn btn-secondary" onclick="hideWarning('hints-warning')">No, keep trying</button>
        <button class="btn btn-warning" onclick="showContent('hints')">Yes, show hints</button>
    </div>
</div>
<script>
    // Set current date when page loads
    window.onload = function() {
        const date = new Date();
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('currentDate').textContent = date.toLocaleDateString('en-US', options);
    }

    function toggleHints() {
        document.getElementById('hints-warning').style.display = 'block';
    }

    function hideWarning(id) {
        document.getElementById(id).style.display = 'none';
    }

    function showContent(id) {
        const content = document.getElementById(id);
        const warning = document.getElementById(id + '-warning');
        
        // Show the content
        content.style.display = 'block';
        
        // Remove blur after a short delay
        setTimeout(() => {
            content.querySelector('.blur-content').classList.remove('blur-content');
        }, 500);
        
        // Hide the warning
        warning.style.display = 'none';
    }
</script>
</body>
</html>