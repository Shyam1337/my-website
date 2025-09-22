<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = htmlspecialchars($_POST['name']);  // Sanitize to prevent XSS
    $country = htmlspecialchars($_POST['country']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Email settings
    $recipients = ["info@aboveandbeyondtreks.com", "anbtrek@gmail.com"]; // Send to both email addresses
    $subject = "New Contact Form Message from $name";  // Subject line with sender's name
    
    // Build the email body with proper line breaks using \r\n for SMTP protocol
    $body = "You have received a new message:\r\n\r\n";
    $body .= "Name: $name\r\n";
    $body .= "Country: $country\r\n";
    $body .= "Email: $email\r\n";
    $body .= "Phone: $phone\r\n";
    $body .= "\r\nMessage:\r\n$message\r\n";

    // SMTP server settings
    $smtpHost = "th1.thulo.com"; // SMTP server (from your server suggestion)
    $smtpPort = 587; // SSL port (from your server suggestion)
    $smtpUsername = "info@aboveandbeyondtreks.com"; // Your email
    $smtpPassword = "rubisH86nepaL"; // SMTP password
    $smtpFrom = "info@aboveandbeyondtreks.com"; // From email

    // Create the SMTP connection
    $smtpConnection = fsockopen($smtpHost, $smtpPort, $errno, $errstr, 30);

    if (!$smtpConnection) {
        echo "Error connecting to SMTP server: $errstr ($errno)";
        exit;
    }

    // Read SMTP greeting
    fgets($smtpConnection, 512);

    // Send EHLO command
    fputs($smtpConnection, "EHLO $smtpHost\r\n");
    fgets($smtpConnection, 512);

    // Send AUTH LOGIN command (Base64 encoding required for credentials)
    fputs($smtpConnection, "AUTH LOGIN\r\n");
    fgets($smtpConnection, 512);
    fputs($smtpConnection, base64_encode($smtpUsername) . "\r\n");
    fgets($smtpConnection, 512);
    fputs($smtpConnection, base64_encode($smtpPassword) . "\r\n");
    fgets($smtpConnection, 512);

    // Set From header
    fputs($smtpConnection, "MAIL FROM: <$smtpFrom>\r\n");
    fgets($smtpConnection, 512);

    // Send RCPT TO for each recipient
    foreach ($recipients as $recipient) {
        fputs($smtpConnection, "RCPT TO: <$recipient>\r\n");
        fgets($smtpConnection, 512);
    }

    // Start message data
    fputs($smtpConnection, "DATA\r\n");
    fgets($smtpConnection, 512);

    // Set email headers and body
    $headers = "From: $smtpFrom\r\n";
    $headers .= "Reply-To: $smtpFrom\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";  // Ensure plain text format
    $emailMessage = "$headers\r\n$body";  // Combine headers and body

    // Send the email message with correct line breaks (\r\n) for SMTP
    fputs($smtpConnection, $emailMessage . "\r\n.\r\n");
    fgets($smtpConnection, 512);

    // End the SMTP session
    fputs($smtpConnection, "QUIT\r\n");
    fgets($smtpConnection, 512);

    // Close the connection
    fclose($smtpConnection);

    // Return success message
    echo "Message has been sent successfully.";
}

// reCAPTCHA verification code (unchanged)
$secretKey = "6LdPNQErAAAAAIev6d3pgz_69cRIWByfHE6hm-n1";

if (isset($_POST['g-recaptcha-response'])) {
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $verifyUrl = "https://www.google.com/recaptcha/api/siteverify";

    // Make a POST request to verify the reCAPTCHA response
    $response = file_get_contents($verifyUrl . "?secret=" . $secretKey . "&response=" . $recaptchaResponse);
    $responseData = json_decode($response);

    if (!$responseData->success) {
        // If verification fails, return an error
        echo json_encode(["status" => "error", "message" => "Captcha verification failed."]);
        exit;
    }
} else {
    // If no reCAPTCHA response, return an error
    echo json_encode(["status" => "error", "message" => "Captcha is required."]);
    exit;
}

?>
