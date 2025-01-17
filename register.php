<?php
session_start() or trigger_error("", E_USER_ERROR);
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verifică dacă utilizatorul deja există
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $error = "Acest nume de utilizator este deja folosit!";
    } else {
        // Hash-ul parolei
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Inserare în baza de date
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->execute();

        $success = "Utilizatorul a fost creat cu succes!";
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Înregistrare</title>
    <link rel="stylesheet" href="stylesauth.css">
</head>
<body>
    <h1>Înregistrare</h1>
    <form action="" method="POST">
        <input type="text" name="username" placeholder="Nume utilizator" required>
        <input type="password" name="password" placeholder="Parolă" required>
        <button type="submit">Înregistrează-te</button>
    </form>
    <?php 
        if (isset($error)) echo "<p style='color: red;'>$error</p>"; 
        if (isset($success)) echo "<p style='color: green;'>$success</p>"; 
    ?>
</body>
</html>
