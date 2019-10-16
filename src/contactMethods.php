<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
if (!empty($_POST)) {
    $err = false;
    $msg = '';

    //these are pulled from the example code and will stay here until i know what they do
    $email = '';

    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = PHPMAILER::ENCRYPTION_STARTTLS;
    $mail->SMTPAuth = true;
    $mail->Username = 'petran.phpm@gmail.com';
    //ask me if you need the password
    $mail->Password = null;
    $mail->setFrom('petran.phpm@gmail.com', 'Petros Papadopoulos');
    $mail->addReplyTo(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING), filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
    $mail->addAddress('petran.phpm@gmail.com', 'Petros Papadopoulos');

    if (!empty($_POST['subject'])) {
        $mail->Subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
    } else {
        $mail->Subject = "No subject";
    }

    //phpmailer can detect if body is empty so i don't have to.....right?
    $mail->msgHTML(filter_input(INPUT_POST, 'textform', FILTER_SANITIZE_STRING));

    if (empty($mail->Password)) {
        die("There is no password by default. check src/contactMethods.php.");
    }
    if (!$mail->send()) {
        header('location: contact.php?failed');
    } else {
        header('location: contact.php?success');
    }
}

// use these two to set a confimation/error box
if (!empty($_GET)) {
    if (isset($_GET['success'])){
        $args['mailResult'] = true;
    }
    if (isset($_GET['failed'])) {
        $args['mailResult'] = false;
        //todo: find how to save these between redirects
        # $args['nameField'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        # $args['emailField'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        # $args['subjectField'] = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
        # $args['textformField'] = filter_input(INPUT_POST, 'textform', FILTER_SANITIZE_STRING);
    }
}