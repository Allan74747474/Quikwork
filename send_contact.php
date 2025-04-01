<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/scr/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST["message"]);

    $mail = new PHPMailer(true);

    try {
        // SMTP Settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ap6972180@gmail.com'; // Your Gmail
        $mail->Password   = 'dyxy htoe cita ntib'; // Use the 16-character App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Email Content
        $mail->setFrom($email, $name);
        $mail->addAddress('ap6972180@gmail.com'); // Your receiving email

        $mail->Subject = "New Contact Form Message from " . $name;
        $mail->Body    = "Name: $name\nEmail: $email\n\nMessage:\n$message";

        $mail->send();
        echo "<script>alert('Message sent successfully!'); window.location.href='contact.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Failed to send message. Mailer Error: {$mail->ErrorInfo}'); window.location.href='contact.php';</script>";
    }
}
?>

