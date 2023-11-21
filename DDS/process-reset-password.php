<?php

$token = $_POST["token"];

$token_hash = hash("sha256", $token);

$myqli = require __DIR__ . "/database.php";

$sql = "SELECT * FROM user
        WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
  die("token não encontrado");
}

if (strtotime($user["reset_token_expires_at"]) < time()) {
  die("token expirado");
}

if (strlen($_POST["password"]) < 8) {
  die("Senha deve conter 8 caracteres no mínimo");
}

if (!preg_match("/[a-z]/i", $_POST["password"])) {
  die("Senha deve conter uma letra");
}

if (!preg_match("/[0-9]/", $_POST["password"])) {
  die("Senha deve conter um número");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
  die("Senhas não coincidem");
}

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$sql = "UPDATE user
        SET password_hash = ?,
            reset_token_hash = NULL,
            reset_token_expires_At = NULL
        WHERE id = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("ss", $password_hash, $user["id"]);

$stmt->execute();

echo "Senha redefinida. Já pode fazer login com sua nova senha!";