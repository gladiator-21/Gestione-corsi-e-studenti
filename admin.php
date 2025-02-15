<?php
session_start();
include 'config.php';

// Lista di giorni festivi in Italia (puoi aggiornarla)
$holidays = ['2024-01-01', '2024-04-25', '2024-05-01', '2024-06-02', '2024-08-15', '2024-12-25', '2024-12-26'];

//Funzione per aggiungere un corso
if (isset($_POST['add_course'])) {
    $course_name = $_POST['course_name'];
    $duration = $_POST['duration'];
    $prerequisite = $_POST['prerequisite'] ?: null; // Se vuoto, imposta NULL

    $stmt = $pdo->prepare("INSERT INTO courses (course_name, duration, prerequisite) VALUES (?, ?, ?)");
    $stmt->execute([$course_name, $duration, $prerequisite]);

    $_SESSION['message'] = "Corso aggiunto con successo!";
    $_SESSION['message_type'] = "success";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

//Funzione per eliminare un corso
if (isset($_POST['delete_course'])) {
    $course_id = $_POST['course_id'];
    $stmt = $pdo->prepare("DELETE FROM courses WHERE course_id = ?");
    $stmt->execute([$course_id]);

    $_SESSION['message'] = "Corso eliminato con successo!";
    $_SESSION['message_type'] = "danger";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

//Funzione per aggiornare un corso
if (isset($_POST['update_course'])) {
    $course_id = $_POST['course_id'];
    $course_name = $_POST['course_name'];
    $duration = $_POST['duration'];
    $prerequisite = $_POST['prerequisite'] ?: null;

    $stmt = $pdo->prepare("UPDATE courses SET course_name = ?, duration = ?, prerequisite = ? WHERE course_id = ?");
    $stmt->execute([$course_name, $duration, $prerequisite, $course_id]);

    $stmt = $pdo->prepare("UPDATE students 
                           SET total_hours = total_hours + ? 
                           WHERE student_id IN 
                           (SELECT student_id FROM enrollments WHERE course_id = ?)");
    $stmt->execute([$duration, $course_id]);

    $_SESSION['message'] = "Corso aggiornato con successo e ore studenti aggiornate!";
    $_SESSION['message_type'] = "success";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

//Recupera la lista dei corsi
$stmt = $pdo->query("SELECT c1.course_id, c1.course_name, c1.duration, c2.course_name AS prerequisite_name 
                      FROM courses c1 
                      LEFT JOIN courses c2 ON c1.prerequisite = c2.course_id");
$courses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Corsi - Amministratore</title>
    <link rel="stylesheet" href="../_style/bootstrap_simplex.css">
    <style>
        body {
            background-color: #f4f4f9;
        }

        .container {
            margin-top: 30px;
        }

        .form-container {
            margin-bottom: 30px;
        }

        .submit-btn {
            background-color: #28a745;
            color: white;
        }

        .submit-btn:hover {
            background-color: #218838;
        }

        table th,
        table td {
            text-align: center;
            vertical-align: middle;
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
        <h1 class="text-center">Gestione Corsi - Amministratore</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['message_type']; ?> text-center">
                <?= $_SESSION['message']; ?>
            </div>
            <?php unset($_SESSION['message']);
            unset($_SESSION['message_type']); ?>
        <?php endif; ?>

        <div class="form-container">
            <h2>Aggiungi Nuovo Corso</h2>
            <form method="POST">
                <div class="mb-3">
                    <label for="course_name" class="form-label">Nome Corso</label>
                    <input type="text" class="form-control" id="course_name" name="course_name" required>
                </div>
                <div class="mb-3">
                    <label for="duration" class="form-label">Durata Corso</label>
                    <input type="text" class="form-control" id="duration" name="duration" required>
                </div>
                <div class="mb-3">
                    <label for="prerequisite" class="form-label">Corso Propedeutico (se presente)</label>
                    <input type="text" class="form-control" id="prerequisite" name="prerequisite">
                </div>
                <button type="submit" name="add_course" class="btn submit-btn">Aggiungi Corso</button>
            </form>
        </div>

        <h2>Elenco Corsi</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Course ID</th>
                    <th>Nome Corso</th>
                    <th>Durata</th>
                    <th>Corso Propedeutico</th>
                    <th>Azione</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td><?= $course['course_id'] ?></td>
                        <td><?= htmlspecialchars($course['course_name']) ?></td>
                        <td><?= $course['duration'] ?> ore</td>
                        <td><?= $course['prerequisite_name'] ?: 'Nessuno' ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editCourse(<?= $course['course_id'] ?>, '<?= htmlspecialchars($course['course_name']) ?>', <?= $course['duration'] ?>, '<?= $course['prerequisite_name'] ?: '' ?>')">
                                Modifica
                            </button>

                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="course_id" value="<?= $course['course_id'] ?>">
                                <button type="submit" name="delete_course" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questo corso?')">
                                    Elimina
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div id="editForm" class="form-container" style="display: none;">
            <h2>Modifica Corso</h2>
            <form method="POST">
                <input type="hidden" id="edit_course_id" name="course_id">
                <div class="mb-3">
                    <label for="edit_course_name" class="form-label">Nome Corso</label>
                    <input type="text" class="form-control" id="edit_course_name" name="course_name" required>
                </div>
                <div class="mb-3">
                    <label for="edit_duration" class="form-label">Durata Corso</label>
                    <input type="text" class="form-control" id="edit_duration" name="duration" required>
                </div>
                <div class="mb-3">
                    <label for="edit_prerequisite" class="form-label">Corso Propedeutico (se presente)</label>
                    <input type="text" class="form-control" id="edit_prerequisite" name="prerequisite">
                </div>
                <button type="submit" name="update_course" class="btn submit-btn">Salva Modifiche</button>
            </form>
        </div>
    </div>

    <script>
        function editCourse(course_id, course_name, duration, prerequisite) {
            document.getElementById('edit_course_id').value = course_id;
            document.getElementById('edit_course_name').value = course_name;
            document.getElementById('edit_duration').value = duration;
            document.getElementById('edit_prerequisite').value = prerequisite;
            document.getElementById('editForm').style.display = 'block';
            window.scrollTo(0, document.getElementById('editForm').offsetTop);
        }
    </script>

</body>

</html>