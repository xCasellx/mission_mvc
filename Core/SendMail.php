<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

include $_SERVER['DOCUMENT_ROOT']."/Libs/PHPMailer/PHPMailer.php";
include $_SERVER['DOCUMENT_ROOT']."/Libs/PHPMailer/SMTP.php";
include $_SERVER['DOCUMENT_ROOT']."/Libs/PHPMailer/Exception.php";


class SendMail
{
    private  $mail;

    function __construct()
    {
        $this->mail = new PHPMailer();
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->Port = 587;
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'testalph55@gmail.com';
        $this->mail->Password = 'QqKSY7DSG';
        $this->mail->setFrom('testalph55@gmail.com', 'Casell');
    }

    public function sendEmailHash($email, $text, $type, $link, $subject)
    {
        $hash = password_hash(microtime() .$email . time(),PASSWORD_BCRYPT).$type;
        $link = 'http://'.$_SERVER['HTTP_HOST']."/$link?hash=".$hash;
        $this->mail->addAddress($email);
        $this->mail->Subject = $subject;
        $this->mail->msgHTML("$text <b>link:</b>$link ;");
        $this->mail->send();
        return $hash;
    }

}