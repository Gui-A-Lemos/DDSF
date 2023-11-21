<?php

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $mysqli = require __DIR__ . "/database.php";

  $sql = sprintf("SELECT * FROM user
                    WHERE email = '%s'",
    $mysqli->real_escape_string($_POST["email"])
  );

  $result = $mysqli->query($sql);

  $user = $result->fetch_assoc();

  if ($user) {

    if (password_verify($_POST["password"], $user["password_hash"])) {

      session_start();

      session_regenerate_id();

      $_SESSION["user_id"] = $user["id"];

      header("Location: index.php");
      exit;
    }
  }

  $is_invalid = true;

}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="stylelogin.css">
</head>

<body>

  <h1 class="titulo">Bem Vindo!</h1>

  <?php if ($is_invalid): ?>
    <em>Login Inválido</em>
  <?php endif; ?>

  <form method="post" class="formulario">
    <h2 class="subtitulo">Login</h2>

    <label class='Formt' for="email">Email</label>
    <input class='Formi' placeholder="Insira seu Email" type="email" id="email" name="email"
      value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">

    <label class='Formt' for="password">Senha</label>
    <input class='Formi' placeholder="Insira sua Senha" type="password" id="password" name="password">

    <button class="btnlogin">Login</button><br>

    <a href="signup.html">Ainda não tem cadastro?</a>
    <br>
    <a class="esenha" href="forgot-password.php">Esqueceu a Senha?</a>
  </form>




</body>

</html>