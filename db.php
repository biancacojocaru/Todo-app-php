<?php
$host = 'localhost'; // Host-ul serverului MySQL
$user = 'biancacojocaru';      // Utilizatorul MySQL
$pass = 'qwererty14';      // Parola utilizatorului MySQL (sau lasă goală dacă nu ai setat una)
$db = 'todo_app'; // Numele bazei de date

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
