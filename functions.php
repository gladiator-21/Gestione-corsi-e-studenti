<?php
function connectDb()
{
    $host = 'lab.isit100.fe.it';    // Cambia con il tuo host
    $dbname = 'info5n2425_imran';    // Cambia con il nome del tuo database
    $username = 'info5n2425'; // Cambia con il tuo username
    $password = 'Zavaj=87]'; // Cambia con la tua password

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Errore di connessione: " . $e->getMessage());
    }
    if (!$pdo) {
        die("Errore di connessione al database.");
    }
}

// Funzione per il controllo del login
function checkLogin()
{    
    if (!isset($_SESSION['user'])) {  // Se non esiste una sessione di login
        header("Location: login.php");  // Reindirizza alla pagina di login
        exit();
    }
}


// Funzione per la registrazione dell'utente
function registerUser($pdo, $username, $password, $confirm_password)
{
    if ($password !== $confirm_password) {
        return "Le password non corrispondono.";
    }

    // Controlla se il nome utente esiste già
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->rowCount() > 0) {
        return "Nome utente già esistente.";
    }

    // Cifra la password prima di salvarla
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Salva l'utente nel database
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $hashed_password]);

    return "Registrazione avvenuta con successo!";
}

// Funzione per il login dell'utente
function loginUser($pdo, $username, $password)
{
    // Verifica se l'utente esiste
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['username'];
        return true;
    }

    return false;
}

// Funzione per iscrivere uno studente a un corso
function enrollStudent($pdo, $student_id, $course_id)
{
    // Controlla se lo studente è già iscritto al corso
    $stmt = $pdo->prepare("SELECT * FROM enrollments WHERE student_id = ? AND course_id = ?");
    $stmt->execute([$student_id, $course_id]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['message'] = "Lo studente è già iscritto a questo corso.";
        $_SESSION['message_type'] = "danger";
        return;
    }

    // Iscrive lo studente al corso
    $stmt = $pdo->prepare("INSERT INTO enrollments (student_id, course_id) VALUES (?, ?)");
    $stmt->execute([$student_id, $course_id]);

    $_SESSION['message'] = "Iscrizione avvenuta con successo!";
    $_SESSION['message_type'] = "success";
}

// Funzione per recuperare gli studenti
function getStudents($pdo)
{
    $stmt = $pdo->query("SELECT student_id, name FROM students");
    return $stmt->fetchAll();
}

// Funzione per recuperare i corsi
function getCourses($pdo)
{
    $stmt = $pdo->query("SELECT course_id, course_name, duration FROM courses");
    return $stmt->fetchAll();
}


function checkAdmin()
{
    if (!isset($_SESSION['user'])) {
        die("Errore: accesso negato, utente non loggato.");
    }

    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        die("Errore: permessi insufficienti.");
    }


    // Controlla se l'utente è loggato
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['message'] = "Devi essere loggato per accedere a questa pagina!";
        $_SESSION['message_type'] = "danger";
        header("Location: login.php"); // Ritorna alla pagina di login se non è loggato
        exit();
    }

    // Controlla se l'utente è un amministratore
    if ($_SESSION['role'] !== 'admin') {
        $_SESSION['message'] = "Accesso negato! Solo gli amministratori possono accedere a questa pagina.";
        $_SESSION['message_type'] = "danger";
        header("Location: index.php"); // Ritorna alla home se non è un amministratore
        exit();
    }
}
