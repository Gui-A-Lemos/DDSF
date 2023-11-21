<?php

$email = $_POST["email"];

$token = bin2hex(random_bytes(16));

$token_hash = hash("sha256", $token);

$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

$myqsli = require __DIR__ . "/database.php";

$sql = "UPDATE user
        SET reset_token_hash = ?,
            reset_token_expires_at = ?
        WHERE email = ?";

$stmt = $myqsli->prepare($sql);

$stmt->bind_param("sss", $token_hash, $expiry, $email);

$stmt->execute();

if ($mysqli->affected_rows) {

  $mail = require __DIR__ . "/mailer.php";

  $mail->setFrom("guilherme.andrade.lemos@gmail.com");
  $mail->addAddress($email);
  $mail->Subject = "Redefinir Senha";
  $mail->Body = <<<END

  Clique <a href="http://localhost/DDS/reset-password.php?token=$token">aqui</a> para redefinir sua senha.

  END;

  try {

    $mail->send();

  } catch (Exception $e) {

    echo "Email não pôde ser enviado. Erro: {$mail->ErrorInfo}";
  }

}

echo "Email enviado!";