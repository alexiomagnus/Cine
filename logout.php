<?php
session_start();
require("connessione.php");

$idSessione = session_id();

try {
    $sql = "UPDATE sessioni SET dataLogout = NOW() WHERE idSessione = :id_s";

    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_s'=>$idSessione]);
} catch (PDOException $e) { }

session_destroy();
header("Location: index.php");
exit();
?>