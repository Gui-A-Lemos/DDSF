<?php

if (empty($_POST["name"])) {
    die("Insira um nome");
}

if ( ! filter_Var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Insira um email válido");
}

if (strlen($_POST["password"]) < 8) {
    die("Senha deve conter 8 caracteres no mínimo");
}

if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
    die("Senha deve conter uma letra");
}

if ( ! preg_match("/[0-9]/", $_POST["password"])) {
  die("Senha deve conter um número");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Senhas não coincidem");
}

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$mysqli = require __DIR__ . "/database.php";

$sql = "INSERT INTO user (name, email, password_hash)
        VALUES (?, ?, ?)";

$stmt = $mysqli->stmt_init();

$stmt->prepare($sql);

if ( ! $stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("sss",
                  $_POST["name"],
                  $_POST["email"],
                  $password_hash);

if ($stmt->execute()) {

    header("Location: signup-success.html");
    exit;

} else {
  
    if ($mysqli->errno === 1062) {
      die("Email já cadastrado");
  } else {
      die($mysqli->error . " " . $mysqli->errno);
  }
}
