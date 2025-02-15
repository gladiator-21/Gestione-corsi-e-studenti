<?php
session_start();
include 'functions.php';

$loggedIn = isset($_SESSION['user']); // Controlla se l'utente è loggato

$pdo = connectDb();
if (!$pdo) {
    die("Errore di connessione al database.");
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Studenti e Corsi</title>
    <link rel="stylesheet" href="../_style/bootstrap_simplex.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .navbar {
            background-color: #007bff;
            padding: 10px;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: inline-block;
        }
        .navbar a:hover {
            background-color: #0056b3;
            border-radius: 5px;
        }
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 160px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        .dropdown-content a {
            color: black;
            padding: 10px;
            text-decoration: none;
            display: block;
        }
        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>
<body>

<!-- Megamenu -->
<nav class="navbar">
    <a href="index.php">🏠 Home</a>
    <a href="dashboard.php">📊 Dashboard</a>
    
    <div class="dropdown">
        <a href="#">🔐 Account ▼</a>
        <div class="dropdown-content">
            <?php if ($loggedIn): ?>
                <a href="logout.php">🚪 Logout</a>
            <?php else: ?>
                <a href="login.php">🔑 Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="text-center">Gestione Studenti e Corsi</h2>

    <!-- Form per visualizzare i dettagli di uno studente -->
    <form method="POST">
        <h3>🔎 Dati Studente</h3>
        <label for="student_id">Seleziona uno studente:</label>
        <select name="student_id" class="form-select" required>
            <option value="">-- Seleziona --</option>
            <?php
            $stmt = $pdo->query("SELECT student_id, name FROM students");
            $students = $stmt->fetchAll();
            foreach ($students as $student) {
                echo "<option value='{$student['student_id']}'>{$student['name']}</option>";
            }
            ?>
        </select>
        <button type="submit" name="student_details" class="btn btn-info mt-2">Visualizza Dati</button>
    </form>

    <!-- Form per visualizzare i dati di un corso -->
    <form method="POST">
        <h3>📘 Dati Corso</h3>
        <label for="course_id">Seleziona un corso:</label>
        <select name="course_id" class="form-select" required>
            <option value="">-- Seleziona --</option>
            <?php
            $stmt = $pdo->query("SELECT course_id, course_name FROM courses");
            $courses = $stmt->fetchAll();
            foreach ($courses as $course) {
                echo "<option value='{$course['course_id']}'>{$course['course_name']}</option>";
            }
            ?>
        </select>
        <button type="submit" name="course_details" class="btn btn-warning mt-2">Visualizza Info Corso</button>
    </form>

    <!-- Area di output per i risultati -->
    <div class="mt-3">
        <?php
        if (isset($_POST['student_details'])) {
            $student_id = $_POST['student_id'];
            $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
            $stmt->execute([$student_id]);
            $student = $stmt->fetch();

            if ($student) {
                echo "<h4>Dati dello studente</h4>";
                echo "📌 <strong>Nome:</strong> " . htmlspecialchars($student['name']) . "<br>";
                echo "👤 <strong>Ore compiute:</strong> " . htmlspecialchars($student['total_hours']) . "<br>";

                // Recupera i corsi a cui è iscritto lo studente
                $stmt = $pdo->prepare("
                    SELECT courses.course_name 
                    FROM enrollments 
                    JOIN courses ON enrollments.course_id = courses.course_id 
                    WHERE enrollments.student_id = ?
                ");
                $stmt->execute([$student_id]);
                $courses = $stmt->fetchAll();

                if ($courses) {
                    echo "<h5>📚 Corsi a cui è iscritto:</h5><ul>";
                    foreach ($courses as $course) {
                        echo "<li>" . htmlspecialchars($course['course_name']) . "</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>📌 Lo studente non è iscritto a nessun corso.</p>";
                }
            } else {
                echo "<p class='text-danger'>❌ Studente non trovato.</p>";
            }
        }

        if (isset($_POST['course_details'])) {
            $course_id = $_POST['course_id'];
            $stmt = $pdo->prepare("SELECT * FROM courses WHERE course_id = ?");
            $stmt->execute([$course_id]);
            $course = $stmt->fetch();

            $stmt = $pdo->prepare("SELECT COUNT(*) as enrolled FROM enrollments WHERE course_id = ?");
            $stmt->execute([$course_id]);
            $count = $stmt->fetchColumn();

            if ($course) {
                echo "<h4>📘 Info Corso</h4>";
                echo "📌 <strong>ID Corso:</strong> " . htmlspecialchars($course['course_id']) . "<br>";
                echo "📚 <strong>Nome:</strong> " . htmlspecialchars($course['course_name']) . "<br>";
                echo "⏳ <strong>Durata:</strong> " . htmlspecialchars($course['duration']) . " ore<br>";
                echo "👥 <strong>Numero di iscritti:</strong> " . $count . "<br>";
            } else {
                echo "<p class='text-danger'>❌ Corso non trovato.</p>";
            }
        }
        ?>
    </div>
</div>

</body>
</html>
