<?php
session_start();
require("connessione.php");

$errore = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$username || !$password) {
        $errore = "compila tutti i campi";
    } else {
        try {
            $sqlUtente = "SELECT * FROM utenti WHERE username = :username";

            $stmt = $conn->prepare($sqlUtente);
            $stmt->execute([':username' => $username]);
            $utente = $stmt->fetch(); // Recupera i dati dell'utente (se esiste) e li salva nell'array

            if ($utente && password_verify($password, $utente['password'])) {
                session_regenerate_id(true); // Cambia l'ID della sessione attuale eliminando quello vecchio

                $_SESSION['username'] = $utente['username'];
                $_SESSION['id_utente'] = $utente['id'];

                $idSessione = session_id();

                $sqlSessione = "INSERT INTO sessioni (idSessione, idUtente, dataLogin)
                        VALUES (:id_s, :id_u, NOW())";
                
                $stmtSess = $conn->prepare($sqlSessione);
                $stmtSess->execute([
                    ':id_s' => $idSessione,
                    ':id_u' => $utente['id']
                ]);

                header("Location: area_riservata.php");
                exit();
            } else {
                $errore = "Username o password non validi";
            }
        } catch (PDOException $e) {
            $errore = "Errore: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>

    <?php if ($errore) echo "<p style='color:red'>$errore</p>";?>

    <form method="POST">
        Username: <input type="text" name="username" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <button type="submit">Invia</button>
    </form>
    <p>Sei nuovo? <a href="registrazione.php">Registrati</a></p>
</body>
</html>