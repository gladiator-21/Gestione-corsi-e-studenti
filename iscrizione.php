<?php
session_start();
include 'config.php';
include 'functions.php';

// Recupera la lista dei corsi
$stmt = $pdo->query("SELECT * FROM courses");
$courses = $stmt->fetchAll();

// Recupera la lista degli studenti
$stmt = $pdo->query("SELECT student_id, name FROM students");
$students = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];

    if ($student_id == 0 || $course_id == 0) {
        $_SESSION['message'] = "Seleziona uno studente e un corso validi!";
        $_SESSION['message_type'] = "danger";
    } else {
        enrollStudent($pdo, $student_id, $course_id);
    }

    // Ricarica la pagina per mostrare il messaggio
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iscrizione ai Corsi</title>
    <link rel="stylesheet" href="../_style/bootstrap_simplex.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 30px;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        /* Stile per il megamenu */
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
        <div class="form-container">
            <h2 class="text-center">Iscrizione ai Corsi</h2>

            <!-- Messaggio di conferma -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?= $_SESSION['message_type']; ?> text-center">
                    <?= $_SESSION['message']; ?>
                </div>
                <?php unset($_SESSION['message']);
                unset($_SESSION['message_type']); ?>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="student_id" class="form-label">Seleziona Studente</label>
                    <select id="student_id" name="student_id" class="form-select" required>
                        <option value="0">Scegli</option>
                        <?php foreach ($students as $student): ?>
                            <option value="<?= $student['student_id'] ?>"><?= $student['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="course_id" class="form-label">Seleziona Corso</label>
                    <select id="course_id" name="course_id" class="form-select" required>
                        <option value="0">Scegli</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course['course_id'] ?>"><?= $course['course_name'] ?> (<?= $course['duration'] ?> ore)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success">Iscriviti</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>