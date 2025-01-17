<?php
session_start();
require 'db.php';

// Verifică dacă utilizatorul este autentificat
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Verifică dacă formularul a fost trimis pentru a adăuga o sarcină
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task'])) {
    $task = $_POST['task'];
    $deadline = isset($_POST['deadline']) && !empty($_POST['deadline']) ? $_POST['deadline'] : null;
    $user_id = $_SESSION['user_id'];

    // Inserare sarcină în baza de date
    try {
        $stmt = $conn->prepare("INSERT INTO tasks (user_id, task, deadline) VALUES (:user_id, :task, :deadline)");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':task', $task, PDO::PARAM_STR);
        $stmt->bindParam(':deadline', $deadline, PDO::PARAM_STR);
        $stmt->execute();

        header('Location: index.php?success=1');
        exit();
    } catch (PDOException $e) {
        echo "<p>Eroare la adăugarea sarcinii: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

// Obține sarcinile din baza de date pentru utilizatorul curent
$user_id = $_SESSION['user_id'];
$result = $conn->prepare("SELECT * FROM tasks WHERE user_id = :user_id ORDER BY created_at DESC");
$result->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$result->execute();
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Pagina Principală</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .deadline-on-time {
            color: green;
            font-weight: bold;
        }
        .deadline-passed {
            color: red;
            font-weight: bold;
        }
        .delete-btn {
            margin-left: 10px;
            color: white;
            background-color: red;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
    <h1>Bun venit, <?= htmlspecialchars($_SESSION['username']); ?>!</h1>
    <a href="logout.php">Deconectare</a>

    <h2>Adaugă o sarcină nouă:</h2>
    <form method="POST" action="index.php">
        <input type="text" name="task" placeholder="Descrierea sarcinii" required>
        <input type="datetime-local" name="deadline" required>
        <button type="submit">Adaugă sarcina</button>
    </form>

    <h2>Lista ta de sarcini:</h2>
    <ul>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <?php
                // Compară deadline-ul cu timpul curent
                $current_time = new DateTime();
                $task_deadline = !empty($row['deadline']) ? new DateTime($row['deadline']) : null;

                $deadline_class = '';
                $deadline_text = 'Fără deadline';

                if ($task_deadline) {
                    if ($current_time > $task_deadline) {
                        $deadline_class = 'deadline-passed';
                        $deadline_text = 'Termen depășit';
                    } else {
                        $deadline_class = 'deadline-on-time';
                        $deadline_text = 'În termen';
                    }
                }
            ?>
            <li>
                <?= htmlspecialchars($row['task']); ?>
                <span class="<?= $deadline_class; ?>">
                    <?= $deadline_text; ?>
                    <?= $task_deadline ? '(' . $task_deadline->format('d-m-Y H:i') . ')' : ''; ?>
                </span>
                <!-- Butonul de ștergere -->
                <form method="POST" action="delete_task.php" style="display: inline;">
                    <input type="hidden" name="task_id" value="<?= $row['id']; ?>">
                    <button type="submit" class="delete-btn">Șterge</button>
                </form>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
