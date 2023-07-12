<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


// Set the timezone
date_default_timezone_set('Asia/Tashkent');

// Get the current date and time
$currentDateTime = date('Y-m-d H:i:s');

// MySQL database connection details
$servername = "localhost";
$username = "pharmat1_orto";
$password = "pharmat1_orto";
$database = "pharmat1_orto";

// Export the MySQL database
$backupFile = 'backup/backup.sql';
$command = "mysqldump --user=$username --password=$password --host=$servername $database > $backupFile";
system($command, $output);

// Compress the backup file
$compressedFile = 'backup/backup.zip';
$zip = new ZipArchive;
if ($zip->open($compressedFile, ZipArchive::CREATE) === true) {
    $zip->addFile($backupFile, basename($backupFile));
    $zip->close();
}

// Email configuration
$senderEmail = 'f.yuldashev.tuit@gmail.com';
$recipientEmail = 'f.yuldashev.tuit@gmail.com';
// $recipientEmail = 'sharipovjasur@gmail.com';;hljkguyrysjh ko
$subject = 'Database Backup';
$body = 'ORTOSAVDO SQL BAZASI sana:'.$currentDateTime;


// Create a new PHPMailer instance
$mail = new PHPMailer;

// SMTP configuration for Gmail
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'f.yuldashev.tuit@gmail.com'; // Replace with your Gmail email address
$mail->Password = 'jgzezpofypfbzhuf'; // Replace with the app password generated for your PHP script
$mail->Port = 587;
$mail->SMTPSecure = 'tls';


// Set the email details
$mail->setFrom($senderEmail, 'Fazliddin');
$mail->addAddress($recipientEmail);

$mail->Subject = $subject;
$mail->Body = $body;

// Attach the compressed backup file
$mail->addAttachment($compressedFile, 'backup.zip');

// Send the email
if (!$mail->send()) {
    echo 'Error sending email: ' . $mail->ErrorInfo;
} else {
    echo 'Email sent successfully!';
}

// Delete the temporary files
// unlink($backupFile);
// unlink($compressedFile);
?>






