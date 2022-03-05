<?php

use PHPMailer\PHPMailer\PHPMailer;

function sendEamil ($email, $nama, $Subject, $Body){
    require 'vendor/autoload.php';
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 3;                    
    $mail->isSMTP();                                          
    $mail->Host       = 'mail.kampoengdomba.com';                    
    $mail->SMTPAuth   = true;                                 
    $mail->Username   = 'no-replay@kampoengdomba.com';                     
    $mail->Password   = 'kampoeng*998877';                               
    $mail->SMTPSecure = 'SSL';            
    $mail->Port       = 465;
    $mail->setFrom('no-replay@kampoengdomba.com', 'Kampoeng Domba');
    $mail->addAddress($email, $nama);

    $mail->isHTML(true);
    $mail->CharSet = "utf-8";
    $mail->Subject = $Subject;
    $mail->Body    = $Body;
    $mail->send();
}