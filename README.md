# 📘 Gestione Studenti e Corsi

Un'applicazione web per la gestione di studenti e corsi in istituti scolastici e centri di formazione.

## 🚀 Caratteristiche Principali
- Gestione completa di studenti, corsi e insegnanti.
- Interfaccia utente semplice e intuitiva.
- Sicurezza con autenticazione basata su sessioni PHP.
- Gestione avanzata di iscrizioni e corsi.

## 🛠️ Tecnologie Utilizzate
- Frontend: HTML, CSS, JavaScript, Bootstrap 5
- Backend: PHP 8.2
- Database: MySQL 8.0
- Framework: Laravel 10

## 🖥️ Installazione
1. Clonare il repository:
   ```bash
   git clone https://github.com/tuo-utente/gestione-studenti-corsi.git
   cd gestione-studenti-corsi
   ```
2. Installare le dipendenze:
   ```bash
   composer install
   ```
3. Configurare il file `.env` con i dati del database:
   ```bash
   DB_HOST=ipsvr
   DB_DATABASE=dbname
   DB_USERNAME=uname
   DB_PASSWORD=pswd
   ```
4. Eseguire le migrazioni:
   ```bash
   php artisan migrate
   ```
5. Avviare il server:
   ```bash
   php artisan serve
   ```

## 🧪 Testing
- Unit Test: `php artisan test`
- Verifica manuale: Accesso, inserimento e gestione dati.

## 📖 Utilizzo
1. Accedi come utente o amministratore.
2. Gestisci studenti e corsi tramite l'interfaccia.
3. Visualizza e aggiorna i dati in tempo reale.

## 🛠️ Manutenzione
- Per aggiornamenti:
  ```bash
  git pull origin main
  php artisan migrate --force
  ```

## ⚖️ Licenza
Rilasciato sotto licenza MIT.

## 👨‍💻 Autori
- Autore principale: Imran Numan Ali

Per segnalazioni di bug o suggerimenti, aprire una issue su GitHub.

---
🎯 *Semplifica la gestione accademica con un click!*
