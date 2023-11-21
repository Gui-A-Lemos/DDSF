<?php

$token = $_GET["token"];

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

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Redefinir Senha</title>
  <link rel="stylesheet" href="stylerp.css">
</head>

<body>

  <h1 class="titulo">Redefinir Senha</h1>

  <form class="formulario" method="post" action="process-reset-password.php">

    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

    <label for="password">Nova Senha</label>
    <input type="password" id="password" name="password">

    <label for="password_confirmation">Repita a Senha</label>
    <input type="password" id="password_confirmation" name="password_confirmation">

    <button>Enviar</button>
  </form>

</body>

</html>