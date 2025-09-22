<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 // Collect form data
    $name = htmlspecialchars($_POST['name']);
    $country =htmlspecialchars($_POST['country']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = htmlspecialchars($_POST['email']);
    $trip = htmlspecialchars($_POST['trip']);
    $groupSize = htmlspecialchars($_POST['groupSize']);
    $travelDate = htmlspecialchars($_POST['travelDate']);
    $arrivalDate = htmlspecialchars($_POST['arrivalDate']);
    $departureDate = htmlspecialchars($_POST['departureDate']);
    $flightDetails = htmlspecialchars($_POST['flightDetails']);
    $duration = htmlspecialchars($_POST['duration']);
    $emergencyName = htmlspecialchars($_POST['emergencyName']);
    $emergencyPhone = htmlspecialchars ($_POST['emergencyPhone']);
    $insurance = htmlspecialchars($_POST['insurance']);
    $message = htmlspecialchars($_POST['message']);
    $legalAcknowledgement = htmlspecialchars($_POST['legal_acknowledgement']) ? 'Agreed' : 'Not agreed';

    // Email settings
    $recipients = ["info@aboveandbeyondtreks.com", "anbtrek@gmail.com"]; // Send to both email addresses
    $subject = "New Contact Form Message from $name";  // Subject line with sender's name
    
    // 3. Prepare email
       $body = "You have received a new booking request:\r\n\r\n";
    $body .= "Name: $name\r\nCountry: $country\r\nPhone: $phone\r\nEmail: $email\r\n";
    $body .= "Trip Name: $trip\r\nGroup Size: $groupSize\r\nTrip Start Date: $travelDate\r\n";
    $body .= "Arrival Date: $arrivalDate\r\nDeparture Date: $departureDate\r\n";
    $body .= "Flight Details: $flightDetails\r\nDuration: $duration days\r\n";
    $body .= "Emergency Contact: $emergencyName ($emergencyPhone)\r\n";
    $body .= "Travel Medical Insurance: $insurance\r\nLegal Acknowledgement: $legalAcknowledgement\r\n\r\n";
    $body .= "Additional Notes:\r\n$message\r\n";

    // 4. SMTP Settings
    $smtpHost = "th1.thulo.com"; // SMTP server (replace with your SMTP details)
    $smtpPort = 587; // SMTP port
    $smtpUsername = "info@aboveandbeyondtreks.com"; // Your SMTP email
    $smtpPassword = "rubisH86nepaL"; // SMTP password
    $smtpFrom = "info@aboveandbeyondtreks.com"; // From email

    // 5. Create SMTP connection
    $smtpConnection = fsockopen($smtpHost, $smtpPort, $errno, $errstr, 30);
    if (!$smtpConnection) {
        echo json_encode(["status" => "error", "message" => "Error connecting to SMTP server: $errstr ($errno)"]);
        exit;
    }

    // 6. Read the SMTP greeting
    fgets($smtpConnection, 512);

    // 7. Send EHLO command
    fputs($smtpConnection, "EHLO $smtpHost\r\n");
    fgets($smtpConnection, 512);

    // 8. Send AUTH LOGIN (Base64 encoding required for credentials)
    fputs($smtpConnection, "AUTH LOGIN\r\n");
    fgets($smtpConnection, 512);
    fputs($smtpConnection, base64_encode($smtpUsername) . "\r\n");
    fgets($smtpConnection, 512);
    fputs($smtpConnection, base64_encode($smtpPassword) . "\r\n");
    fgets($smtpConnection, 512);

    // 9. Set the MAIL FROM address
    fputs($smtpConnection, "MAIL FROM: <$smtpFrom>\r\n");
    fgets($smtpConnection, 512);

    // 10. Send RCPT TO for each recipient
    foreach ($recipients as $recipient) {
        fputs($smtpConnection, "RCPT TO: <$recipient>\r\n");
        fgets($smtpConnection, 512);
    }

    // 11. Start sending message data
    fputs($smtpConnection, "DATA\r\n");
    fgets($smtpConnection, 512);

    // Set email headers and body
    $headers = "From: $smtpFrom\r\n";
    $headers .= "Reply-To: $smtpFrom\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";  // Ensure plain text format
    $emailMessage = "$headers\r\n$body";  // Combine headers and body
   
    // 13. Send the email with the proper line breaks (\r\n) for SMTP
    fputs($smtpConnection, $emailMessage . "\r\n.\r\n");
    fgets($smtpConnection, 512);

    // 14. End the SMTP session
    fputs($smtpConnection, "QUIT\r\n");
    fgets($smtpConnection, 512);

    // 15. Close the connection
    fclose($smtpConnection);

    // Return success message
    echo "Booking request has been sent successfully.";
}

// reCAPTCHA secret key (replace with your actual key)
$secretKey = "6LeuaCkrAAAAACisXzjbyduAulnXXus82-FRzTRa";

    if (isset($_POST['g-recaptcha-response'])) {
        $recaptchaResponse = $_POST['g-recaptcha-response'];
        $verifyUrl = "https://www.google.com/recaptcha/api/siteverify";

        $response = file_get_contents($verifyUrl . "?secret=" . $secretKey . "&response=" . $recaptchaResponse);
        $responseData = json_decode($response);

        if (!$responseData->success) {
            echo json_encode(["status" => "error", "message" => "Captcha verification failed."]);
            exit;
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Captcha is required."]);
        exit;
    }

?>
