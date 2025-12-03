<?php
// Basic sanitization and validation for posted form fields
$fname = trim(filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING));
$lname = trim(filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING));
$email_id = trim(filter_input(INPUT_POST, 'email_id', FILTER_SANITIZE_EMAIL));
$mobile = trim(filter_input(INPUT_POST, 'mobile', FILTER_SANITIZE_STRING));
$message = trim(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING));

// Additional validation
$errors = [];
if (!$fname) $errors[] = 'First name required';
if (!$lname) $errors[] = 'Last name required';
if ($email_id && !filter_var($email_id, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email';
if ($mobile && !preg_match('/^[0-9+\s\-()]{7,20}$/', $mobile)) $errors[] = 'Invalid mobile number';
if (!$message) $errors[] = 'Message required';

if (!empty($errors)) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'errors' => $errors]);
    exit;
}

// Prevent header injection
$clean_email = str_replace(["\r", "\n"], '', $email_id);

// Prepare safe values for email body
$body = "Name: " . htmlspecialchars($fname . ' ' . $lname, ENT_QUOTES, 'UTF-8') . "\n";
$body .= "Email: " . htmlspecialchars($clean_email, ENT_QUOTES, 'UTF-8') . "\n";
$body .= "Mobile: " . htmlspecialchars($mobile, ENT_QUOTES, 'UTF-8') . "\n\n";
$body .= "Message:\n" . htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

// Send email (adjust to your mail setup)
$to = 'kidzkollegepreschool@gmail.com';
$subject = 'Website enquiry from ' . htmlspecialchars($fname, ENT_QUOTES, 'UTF-8');
$headers = "From: " . ($clean_email ? $clean_email : 'no-reply@yourdomain.com') . "\r\n";
$headers .= "Reply-To: " . ($clean_email ? $clean_email : 'no-reply@yourdomain.com') . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

if (mail($to, $subject, $body, $headers)) {
    echo json_encode(['status' => 'ok']);
} else {
    echo json_encode(['status' => 'error', 'errors' => ['Mail send failed']]);
}
?>
