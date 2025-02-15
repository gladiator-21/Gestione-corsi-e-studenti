<?php
require_once 'config.php';

$admin_code = "63be86ca-ac6f-4228-bf5d-0c8b476b7616"; // Codice segreto per creare un admin

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $entered_code = $_POST['admin_code'];

    if (strlen($username) < 4) {
        $error = "⚠️ Il nome utente deve avere almeno 4 caratteri.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "⚠️ Inserisci un'email valida.";
    } elseif (strlen($password) < 6) {
        $error = "⚠️ La password deve avere almeno 6 caratteri.";
    } elseif ($password !== $confirm_password) {
        $error = "⚠️ Le password non corrispondono.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
            $stmt->execute([':username' => $username, ':email' => $email]);
            $existing_user = $stmt->fetch();

            if ($existing_user) {
                $error = "⚠️ Nome utente o email già esistente.";
            } else {
                $role = ($entered_code === $admin_code) ? 'admin' : 'user';

                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("INSERT INTO users (username, password, role, email, created_at, updated_at, status)
                                        VALUES (:username, :password, :role, :email, NOW(), NOW(), 'active')");
                $stmt->execute([
                    ':username' => $username,
                    ':password' => $hashed_password,
                    ':role' => $role,
                    ':email' => $email
                ]);

                header("Location: login.php?success=1");
                exit();
            }
        } catch (PDOException $e) {
            $error = "Errore durante la registrazione: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <link rel="stylesheet" href="../_style/bootstrap_simplex.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-container {
            max-width: 400px;
            margin: 80px auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2 class="text-center mb-4">📝 Registrazione</h2>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger text-center" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">👤 Nome Utente</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">📧 Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">🔒 Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">🔑 Conferma Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>

            <div class="mb-3">
                <label for="admin_code" class="form-label">🔹 Codice Amministratore (facoltativo)</label>
                <input type="text" class="form-control" id="admin_code" name="admin_code">
                <small class="form-text text-muted">Inserisci il codice per diventare amministratore.</small>
            </div>

            <div class="mb-3 text-center">
                <button type="submit" class="btn btn-primary w-100">Registrati</button>
            </div>
        </form>

        <p class="text-center mt-3">
            🔹 Hai già un account? <a href="login.php">Accedi</a>
        </p>
    </div>
</body>
</html>
