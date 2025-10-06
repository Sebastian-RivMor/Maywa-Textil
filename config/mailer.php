<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

function build_mailer(): PHPMailer {
  $mail = new PHPMailer(true);
  $mail->isSMTP();
  $mail->Host       = 'smtp.gmail.com';
  $mail->SMTPAuth   = true;
  $mail->Username   = 'maywa.textil@gmail.com';
  $mail->Password   = 'auqs nsxw qtmn awnp'; // <-- cámbialo
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port       = 587;
  $mail->CharSet    = 'UTF-8';
  $mail->setFrom('maywa.textil@gmail.com', 'Maywa Textil');
  $mail->addReplyTo('maywa.textil@gmail.com', 'Maywa Textil');
  $mail->isHTML(true);
  // $mail->SMTPDebug = 2; // solo para depurar si no llega el correo
  return $mail;
}
