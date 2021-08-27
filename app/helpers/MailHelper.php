<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

/**
 * Send Emails with PHPMailer
 *
 * @param $sendTo
 * @param $replyTo
 * @param $subject
 * @param $htmlBody
 * @param $altBody
 * @return bool|string
 */
function sendEmail($sendTo, $replyTo, $subject, $htmlBody, $altBody) {
    $mail = new PHPMailer(true);

    $mail->SMTPDebug = SMTP::DEBUG_CLIENT;
    $mail->isSMTP();
    $mail->Host       = SMTP_SERVER;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USERNAME;
    $mail->Password   = SMTP_SECRET;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    try {
        $mail->setFrom('admin@nani-games.net', 'Nani-Games');
        $mail->addAddress($sendTo);
        $mail->addReplyTo($replyTo);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlBody;
        $mail->AltBody = $altBody;
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->send();
        return true;
    } catch (Exception $ex) {
        return $mail->ErrorInfo;
    }

}
