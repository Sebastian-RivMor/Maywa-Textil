<?php
use PHPMailer\PHPMailer\PHPMailer;
require __DIR__ . '/../vendor/autoload.php';

function build_mailer(): PHPMailer {
  $mail = new PHPMailer(true);
  $mail->isSMTP();
  $mail->Host       = 'smtp.gmail.com';
  $mail->SMTPAuth   = true;
  $mail->Username   = 'satorugojo0123456789@gmail.com';
  $mail->Password   = 'czfg nszk dmxo bfxt'; // <- pega la de 16 caracteres
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     // 587
  $mail->Port       = 587;

  $mail->setFrom('satorugojo0123456789@gmail.com', 'Maywa Textil');
  $mail->CharSet = 'UTF-8';
  return $mail;
}
