<?php
// Booking form handler using PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/PHPMailer/src/Exception.php';
require __DIR__ . '/vendor/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/vendor/PHPMailer/src/SMTP.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get form data
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');
$gameScore = trim($_POST['gameScore'] ?? '');

// Validate required fields
if (empty($name) || empty($email) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

// Sanitize inputs
$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$email = filter_var($email, FILTER_SANITIZE_EMAIL);
$message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
$gameScore = htmlspecialchars($gameScore, ENT_QUOTES, 'UTF-8');

// Rate limiting
$rateLimitFile = '/tmp/booking_rate_' . md5($_SERVER['REMOTE_ADDR']);
if (file_exists($rateLimitFile) && (time() - filemtime($rateLimitFile)) < 60) {
    echo json_encode(['success' => true, 'message' => 'Thank you! We\'ll be in touch soon.']);
    exit;
}
touch($rateLimitFile);

// Load mail config
$config = require __DIR__ . '/mail-config.php';

// Send email using PHPMailer
$mail = new PHPMailer(true);
$success = false;

try {
    // SMTP settings
    $mail->isSMTP();
    $mail->Host       = $config['host'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $config['username'];
    $mail->Password   = $config['password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = $config['port'];

    // Recipients
    $mail->setFrom($config['from_email'], $config['from_name']);
    $mail->addAddress($config['to_email']);
    $mail->addReplyTo($email, $name);

    // Content
    $mail->isHTML(false);
    $subject = 'Maxxinistas Booking Inquiry';
    if (!empty($gameScore)) {
        $subject .= ' - Game Score: ' . $gameScore;
    }
    $mail->Subject = $subject;

    $body = "New booking inquiry from maxxinistas.com\n";
    $body .= "==========================================\n\n";
    $body .= "Name: " . $name . "\n";
    $body .= "Email: " . $email . "\n";
    if (!empty($gameScore)) {
        $body .= "Game Score: " . $gameScore . " points\n";
    }
    $body .= "\nMessage:\n";
    $body .= "----------------------------------------\n";
    $body .= $message . "\n";
    $body .= "----------------------------------------\n";
    $body .= "\n---\n";
    $body .= "Sent from: " . $_SERVER['REMOTE_ADDR'] . "\n";
    $body .= "Time: " . date('Y-m-d H:i:s') . "\n";

    $mail->Body = $body;

    $mail->send();
    $success = true;
} catch (Exception $e) {
    error_log("PHPMailer Error: " . $mail->ErrorInfo);
}

if ($success) {
    echo json_encode(['success' => true, 'message' => 'Thank you! We\'ll be in touch soon.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send message. Please try again later.']);
}
