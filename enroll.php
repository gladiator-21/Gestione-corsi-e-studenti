<?php
include 'config.php';

function enrollStudent($student_id, $course_id) {
    global $pdo;

    // Verifica se lo studente ha già completato 30 ore
    $stmt = $pdo->prepare("SELECT total_hours FROM students WHERE student_id = :student_id");
    $stmt->execute(['student_id' => $student_id]);
    $student = $stmt->fetch();
    
    if ($student['total_hours'] >= 30) {
        echo "Lo studente ha già completato le ore necessarie.";
        return;
    }

    // Verifica se il corso ha una durata inferiore a 10 ore
    $stmt = $pdo->prepare("SELECT duration FROM courses WHERE course_id = :course_id");
    $stmt->execute(['course_id' => $course_id]);
    $course = $stmt->fetch();

    if ($course['duration'] > 10) {
        echo "Il corso ha una durata superiore a 10 ore.";
        return;
    }

    // Iscrizione al corso
    $stmt = $pdo->prepare("INSERT INTO enrollments (student_id, course_id) VALUES (:student_id, :course_id)");
    $stmt->execute(['student_id' => $student_id, 'course_id' => $course_id]);

    echo "Iscrizione avvenuta con successo!";
}
?>
