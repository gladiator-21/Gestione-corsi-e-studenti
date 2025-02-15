    CREATE TABLE students (
        student_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        total_hours INT DEFAULT 0
    );

    CREATE TABLE courses (
        course_id INT AUTO_INCREMENT PRIMARY KEY,
        course_name VARCHAR(255) NOT NULL,
        duration INT NOT NULL,
        prerequisite INT DEFAULT 0
    );

    CREATE TABLE teachers (
        teacher_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        course_id INT NOT NULL,
        FOREIGN KEY (course_id) REFERENCES courses(course_id)
    );

    CREATE TABLE enrollments (
        enrollment_id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        course_id INT NOT NULL,
        FOREIGN KEY (student_id) REFERENCES students(student_id),
        FOREIGN KEY (course_id) REFERENCES courses(course_id)
    );

    CREATE TABLE sessions (
        session_id INT AUTO_INCREMENT PRIMARY KEY,
        course_id INT NOT NULL,
        teacher_id INT NOT NULL,
        date DATE NOT NULL,
        hours INT NOT NULL,
        FOREIGN KEY (course_id) REFERENCES courses(course_id),
        FOREIGN KEY (teacher_id) REFERENCES teachers(teacher_id)
    );


    CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,         -- Identificativo univoco per ogni utente
    username VARCHAR(255) NOT NULL UNIQUE,      -- Nome utente, unico
    password VARCHAR(255) NOT NULL,             -- Password, memorizzata come hash
    role ENUM('user', 'admin') DEFAULT 'user', -- Ruolo dell'utente (user o admin)
    email VARCHAR(255) UNIQUE,                  -- Email, unica per ogni utente
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Data di creazione dell'account
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Data di aggiornamento
    last_login TIMESTAMP NULL,                  -- Data dell'ultimo accesso
    status ENUM('active', 'inactive') DEFAULT 'active' -- Stato dell'utente (attivo o inattivo)
);




    INSERT INTO courses (course_name, duration, prerequisite) VALUES
    ('Introduzione alla programmazione', 4, 0),
    ('Programmazione in Python - Livello base', 6, 1),
    ('Sviluppo Web con HTML e CSS', 5, 0),
    ('Introduzione a JavaScript', 3, 3),
    ('Creazione di database con MySQL', 6, 0),
    ('SQL avanzato per analisi dati', 7, 5),
    ('Programmazione in Java - Livello base', 6, 1),
    ('Introduzione all\'intelligenza artificiale', 8, 2),
    ('Introduzione alla cybersecurity', 5, 0),
    ('Gestione di reti locali', 4, 0),
    ('Fondamenti di biotecnologie', 6, 0),
    ('Elementi di robotica educativa', 5, 0),
    ('Analisi di dati scientifici con Excel', 4, 0),
    ('Introduzione alla fisica quantistica', 7, 14),
    ('Simulazioni di esperimenti di chimica', 8, 15),
    ('Fondamenti di algebra lineare', 6, 16),
    ('Introduzione al calcolo differenziale', 5, 17),
    ('Matematica per l\'analisi dati', 7, 18),
    ('Principi di contabilità generale', 4, 0),
    ('Creazione di business plan', 6, 19),
    ('Introduzione al marketing digitale', 5, 0),
    ('Tecniche di negoziazione', 3, 0),
    ('Inglese per il mondo del lavoro', 4, 0),
    ('Conversazione in inglese tecnico', 5, 23),
    ('Introduzione al cinese mandarino', 3, 0),
    ('Preparazione a certificazioni linguistiche B1', 6, 0),
    ('Public speaking per studenti', 4, 0),
    ('Gestione del tempo e dello stress', 3, 0),
    ('Tecniche di studio efficaci', 5, 0);

    INSERT INTO students (name, total_hours) VALUES
    ('Alice Rossi', 0),
    ('Marco Bianchi', 0),
    ('Giulia Verdi', 0),
    ('Luca Neri', 0),
    ('Elena Russo', 0),
    ('Davide Conti', 0),
    ('Sara Galli', 0),
    ('Francesco Moretti', 0),
    ('Valentina De Luca', 0),
    ('Matteo Ferri', 0),
    ('Chiara Fontana', 0),
    ('Andrea Rizzi', 0),
    ('Federica Leone', 0),
    ('Stefano Colombo', 0),
    ('Martina Gentile', 0);

    INSERT INTO teachers (name, course_id) VALUES
    ('Mario Rossi', 1),
    ('Giulia Bianchi', 2),
    ('Alessandro Verdi', 3),
    ('Francesca Neri', 4),
    ('Luca Conti', 5),
    ('Elena Russo', 6),
    ('Davide Galli', 7),
    ('Sara Moretti', 8),
    ('Francesco De Luca', 9),
    ('Valentina Ferri', 10),
    ('Matteo Fontana', 11),
    ('Chiara Rizzi', 12),
    ('Andrea Leone', 13),
    ('Federica Colombo', 14),
    ('Stefano Gentile', 15),
    ('Martina Esposito', 16),
    ('Roberto Ricci', 17),
    ('Angela Romano', 18),
    ('Paolo De Santis', 19),
    ('Laura Marchetti', 20),
    ('Simone Barbieri', 21),
    ('Claudia Lombardi', 22),
    ('Giorgio Fabbri', 23),
    ('Elisa Pellegrini', 24),
    ('Daniele Morelli', 25),
    ('Beatrice Caruso', 26),
    ('Antonio Ferrara', 27),
    ('Silvia Benedetti', 28),
    ('Emanuele Marini', 29);



INSERT INTO users (username, password, role, email, created_at, updated_at, status)
VALUES
    ('admin', '63be86ca-ac6f-4228-bf5d-0c8b476b7616', 'admin', 'admin@example.com', NOW(), NOW(), 'active'),
    ('user1', 'password123', 'user', 'user1@example.com', NOW(), NOW(), 'active'),
    ('user2', 'password456', 'user', 'user2@example.com', NOW(), NOW(), 'active'),
    ('user3', 'password789', 'user', 'user3@example.com', NOW(), NOW(), 'inactive');
