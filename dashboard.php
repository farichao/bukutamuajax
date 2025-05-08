<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php?error=Silakan login dulu!");
    exit();
}

if (!empty($_SESSION['welcome_message'])) {
    echo "<script>alert('" . addslashes($_SESSION['welcome_message']) . "');</script>";
    unset($_SESSION['welcome_message']);
}

include 'koneksidb.php';

// Inisialisasi pesan
$success_message = '';
$error_message = '';

// Proses simpan data jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $comment = trim($_POST['comment'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($name && $comment && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare("INSERT INTO tamu (name, comment, email) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sss", $name, $comment, $email);
            if ($stmt->execute()) {
                $success_message = "Data berhasil disimpan!";
            } else {
                $error_message = "Gagal menyimpan data.";
            }
            $stmt->close();
        } else {
            $error_message = "Gagal menyiapkan statement.";
        }
    } else {
        $error_message = "Harap isi semua data dengan benar.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style3.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <a href="logout.php" class="logout-btn">Logout</a>

        <div class="box">
            <h2>Guest Book</h2>
            <form method="post" action="">
                <input type="text" name="name" placeholder="Name" required>
                <textarea name="comment" placeholder="Comment" required></textarea>
                <input type="email" name="email" placeholder="Email" required>
                <input type="submit" value="Send">
            </form>

            <?php if ($success_message): ?>
                <p style="color: green;"><?= htmlspecialchars($success_message) ?></p>
            <?php elseif ($error_message): ?>
                <p style="color: red;"><?= htmlspecialchars($error_message) ?></p>
            <?php endif; ?>
        </div>

        <div class="box">
            <h2>History Input</h2>
            <div id="comments">
                <?php
                $result = $conn->query("SELECT * FROM tamu ORDER BY created_at DESC");

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='comment-box'>";
                        echo "Waktu: " . htmlspecialchars($row['created_at']) . "<br>";
                        echo "Nama: " . htmlspecialchars($row['name']) . "<br>";
                        echo "Komentar: " . htmlspecialchars($row['comment']) . "<br>";
                        echo "Email: " . htmlspecialchars($row['email']);
                        echo "</div><hr>";
                    }
                } else {
                    echo "<p>Belum ada komentar.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
