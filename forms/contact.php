<?php
$receiving_email_address = 'chamkang237@icloud.com';

if (file_exists($php_email_form = '../assets/vendor/php-email-form/php-email-form.php')) {
    include($php_email_form);
} else {
    die('Unable to load the "PHP Email Form" Library!');
}

$contact = new PHP_Email_Form;
$contact->ajax = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate required field
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message'])) {
        die('Please fill in all required fields.');
    }

    $contact->to = $receiving_email_address;
    $contact->from_name = $_POST['name'];
    $contact->from_email = $_POST['email'];
    $contact->subject = $_POST['subject'];

    // Uncomment below code to use SMTP
    $contact->smtp = array(
        'host' => 'smtp.gmail.com',  // Correct SMTP host
        'username' => 'johnchamnibabadez@gmail.com',  // Your Gmail address
        'password' => 'your_app_password',  // Your App Password
        'port' => '587'
    );

    $contact->add_message($_POST['name'], 'From');
    $contact->add_message($_POST['email'], 'Email');
    $contact->add_message($_POST['message'], 'Message', 10);

    // Send the email and check for errors
    if ($contact->send()) {
        echo 'Email sent successfully!';
    } else {
        echo 'Email sending failed: ' . $contact->error;
    }
} else {
    die('Invalid request method.');
}
?>