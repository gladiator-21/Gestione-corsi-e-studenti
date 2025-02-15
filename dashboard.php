<?php
session_start();
include 'functions.php'; // Controlla se l'utente è loggato
checkLogin(); // Controlla se l'utente è autenticato

// Verifica se l'utente ha il ruolo di amministratore
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] == 'admin'; // Aggiungi un controllo sul ruolo
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../_style/bootstrap_simplex.css">
    <style>
        .panel-admin {
            margin-top: 50px;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .panel-admin h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .panel-admin .btn {
            width: 100%;
            margin: 10px 0;
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
        <h1 class="text-center mt-5">Benvenuto, <?= $_SESSION['user']; ?>!</h1>

        <!-- Pannello Amministrativo -->
        <div class="panel-admin">
            <h2>Pannello Amministrativo</h2>
            <div class="d-grid gap-2">
                <a href="iscrizione.php" class="btn btn-primary">Gestisci Iscrizioni</a>

                <a href="logout.php" class="btn btn-danger">Esci</a>

                <?php if ($isAdmin): ?>
                    <a href="admin.php" class="btn btn-warning">Gestisci Corsi</a>
                <?php else: ?>
                    <button class="btn btn-warning" onclick="alert('Non hai i permessi per accedere a questa pagina.');">Gestisci Corsi</button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Aggiungi un alert in caso di accesso non autorizzato
        function showAlert() {
            alert('Non hai i permessi per accedere a questa pagina.');
        }
    </script>
</body>

</html>