<?php
if (file_exists(__DIR__ . '/../.installed')) {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Installation</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">System Installation</h3>
            </div>
            <div class="card-body">
                <div id="message"></div>
                <button id="install-btn" class="btn btn-primary">Install System</button>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('install-btn').addEventListener('click', function() {
        this.disabled = true;
        this.innerHTML = 'Installing...';
        
        fetch('initialize_db.php')
            .then(response => response.json())
            .then(data => {
                const messageDiv = document.getElementById('message');
                if (data.success) {
                    messageDiv.innerHTML = `
                        <div class="alert alert-success">
                            ${data.message}<br>
                            Redirecting to login page...
                        </div>`;
                    setTimeout(() => window.location.href = '../index.php', 2000);
                } else {
                    messageDiv.innerHTML = `
                        <div class="alert alert-danger">${data.message}</div>`;
                    this.disabled = false;
                    this.innerHTML = 'Retry Installation';
                }
            })
            .catch(error => {
                document.getElementById('message').innerHTML = `
                    <div class="alert alert-danger">Installation failed: ${error}</div>`;
                this.disabled = false;
                this.innerHTML = 'Retry Installation';
            });
    });
    </script>
</body>
</html>