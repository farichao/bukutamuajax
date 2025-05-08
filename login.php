<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins&display=swap" rel="stylesheet">
    <script>
        function login(event) {
            event.preventDefault(); // Mencegah form submit default

            const username = document.querySelector('input[name="username"]').value;
            const password = document.querySelector('input[name="password"]').value;

            fetch('login_process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = 'dashboard.php';
                } else {
                    document.getElementById('message').textContent = data.message;
                }
            })
            .catch(error => {
                document.getElementById('message').textContent = "Terjadi kesalahan pada server.";
                console.error("Error:", error);
            });
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Login</h2>
    <form onsubmit="login(event)">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Login">
    </form>

    <p id="message" class="message"></p>
</div>

</body>
</html>
