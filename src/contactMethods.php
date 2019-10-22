<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
if (!empty($_POST)) {


    

    


    // captcha management
    //this captcha code is a copypaste. replace it with an object.
    $postData = ['secret' => getenv("RECAPTCHA_SECRET_KEY"), 'response' => $_POST['g-recaptcha-response']];
    $recaptcha = http_build_query($postData);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $recaptcha);
  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
    $server_output = curl_exec ($ch);
  
    curl_close ($ch);

    $decoded = json_decode($server_output);
    // end of captcha








    
    $err = false;
    $msg = '';
    $email = '';

    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = PHPMAILER::ENCRYPTION_STARTTLS;
    $mail->SMTPAuth = true;
    $mail->Username = getenv("EMAIL");
    $mail->Password = getenv("EMAIL_PASSWORD");
    $mail->setFrom(getenv("EMAIL"), getenv("EMAIL_NAME"));
    $mail->addReplyTo(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING), filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
    $mail->addAddress(getenv("EMAIL"), getenv("EMAIL_NAME"));

    if (!empty($_POST['subject'])) {
        $mail->Subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
    } else {
        $mail->Subject = "No subject";
    }

    //phpmailer can detect if body is empty so i don't have to.....right?
    $mail->msgHTML(filter_input(INPUT_POST, 'textform', FILTER_SANITIZE_STRING));


        //if captcha returns false, the $mail->send method is not run
        if ($decoded->success === false || !$mail->send()) {
            $_SESSION['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $_SESSION['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
            $_SESSION['subject'] = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
            $_SESSION['textform'] = filter_input(INPUT_POST, 'textform', FILTER_SANITIZE_STRING);
            header('location: contact.php?failed');
        } else {
            header('location: contact.php?success');
        }

}

// use these two to set a confimation/error box
if (!empty($_GET)) {
    if (isset($_GET['success'])){
        $args['mailResult'] = 1;
    }
    if (isset($_GET['failed'])) {
        $args['mailResult'] = -1;
        $args['nameField'] = $_SESSION['name'];
        $args['emailField'] = $_SESSION['email'];
        $args['subjectField'] = $_SESSION['subject'];
        $args['textformField'] = $_SESSION['textform'];
    }
}
