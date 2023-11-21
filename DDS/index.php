<?php

session_start();

if (isset($_SESSION["user_id"])) {

  $mysqli = require __DIR__ . "/database.php";

  $sql = "SELECT * FROM user
          WHERE id = {$_SESSION["user_id"]}";

  $result = $mysqli->query($sql);

  $user = $result->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Index</title>
  <link rel="stylesheet" href="styleindex.css">
</head>

<body>

  <h1>Início</h1>

  <?php if (isset($user)): ?>

    <p class="logado">Olá,
      <?= htmlspecialchars($user["name"]) ?>!
    </p>

    <p class="sair"><a href="logout.php">Sair</a></p>

  <?php else: ?>

    <p class="cadastro"><a href="login.php">Logar</a> ou <a href="signup.html">Cadastrar-se</a></p>

  <?php endif; ?>

</body>

</html>