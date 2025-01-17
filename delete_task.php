<?php
session_start();
require 'db.php';

// Verifică dacă utilizatorul este autentificat
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Verifică dacă ID-ul sarcinii a fost transmis prin POST
if (isset($_POST['task_id'])) {
    $task_id = $_POST['task_id'];

    try {
        // Șterge sarcina din baza de date
        $stmt = $conn->prepare("DELETE FROM tasks WHERE id = :task_id AND user_id = :user_id");
        $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();

        // După ștergere, redirecționează utilizatorul înapoi pe pagina principală
        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        // În cazul unei erori, afișează un mesaj de eroare
        echo "Eroare la ștergerea sarcinii: " . $e->getMessage();
    }
} else {
    echo "ID-ul sarcinii nu a fost găsit!";
}
?>
