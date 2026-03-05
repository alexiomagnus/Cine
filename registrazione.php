<?php
require("connessione.php");

$errore = '';
$messaggio = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $nome = trim($_POST['nome'] ?? '');
    $cognome = trim($_POST['cognome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $ruolo = $_POST['ruolo'] ?? 1;

    if (!$username || !$password || !$nome || !$cognome || !$email) {
    $errore = "Compila tutti i campi";
    } else {
        try {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO utenti (username, password, nome, cognome, email, idProfilo)
                    VALUES (:username, :password, :nome, :cognome, :email, :ruolo)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':username' => $username,
                ':password' => $passwordHash,
                ':nome' => $nome,
                ':cognome' => $cognome,
                ':email' => $email,
                ':ruolo' => $ruolo
            ]);

            $messaggio = "Registrazione completata <a href='index.php'>Vai al login</a>";
        } catch (PDOException $e) {
            $errore = "Errore: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <title>Registrazione - Cine</title>
</head>
<body>
    <h1>Registrati</h1>

    <?php if ($errore) echo "<p style='color:red'>$errore</p>"; ?>
    <?php if ($messaggio) echo "<p style='color:green'>$messaggio</p>"; ?>

    <form method="POST">
        Username: <input type="text" name="username" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        Nome: <input type="text" name="nome" required><br><br>
        Cognome: <input type="text" name="cognome" required><br><br>
        Email: <input type="email" name="email" required><br><br>
        Ruolo: 
        <select name="ruolo">
            <option value="1">Utente</option>
            <option value="2">Amministratore</option>
        </select><br><br>
        <button type="submit">Registrati</button>
    </form>
    <p>Già registrato? <a href="index.php">Login</p>
</body>
</html>