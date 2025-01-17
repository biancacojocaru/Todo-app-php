<?php
require 'db.php';

try {
    // Datele utilizatorului nou
    $username = 'newuser';
    $password = 'newpassword'; // Parola utilizatorului nou

    // Hash-ul parolei
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Inserare în baza de date
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
    $stmt->execute();

    echo "Utilizatorul a fost creat cu succes!";
} catch (PDOException $e) {
    die("Eroare la crearea utilizatorului: " . $e->getMessage());
}
?>
