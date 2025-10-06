<?php
use PHPMailer\PHPMailer\PHPMailer;
require __DIR__ . '/../vendor/autoload.php';

function build_mailer(): PHPMailer {
  $mail = new PHPMailer(true);
  $mail->isSMTP();
  $mail->Host       = 'smtp.gmail.com';
  $mail->SMTPAuth   = true;
  $mail->Username   = 'maywa.textil@gmail.com';
  $mail->Password   = 'auqs nsxw qtmn awnp';
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port       = 587;

  $mail->setFrom('maywa.textil@gmail.com', 'Maywa Textil');
  $mail->CharSet = 'UTF-8';
  return $mail;
}