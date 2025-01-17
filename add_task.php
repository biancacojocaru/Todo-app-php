<?php
session_start();
require 'db.php';

// Verifica daca utilizatorul este autentificat
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Adauga sarcina in baza de date
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task = $conn->real_escape_string($_POST['task']);
    $user_id = $_SESSION['user_id'];

    $conn->query("INSERT INTO tasks (task, user_id) VALUES ('$task', '$user_id')");

    // Redirectioneaza inapoi la pagina principala dupa adaugarea sarcinii
    header('Location: index.php');
    exit();
}
?>
