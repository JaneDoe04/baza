
<?php

$to = "mojezoo2004@gmail.com";
$subject = "Temat wiadomości";
$message = "Treść wiadomości.";

$headers = "From: mojezoo2004@gmail.com\r\n";
$headers .= "Reply-To: mojezoo2004@gmail.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";

$mail_sent = mail($to, $subject, $message, $headers);

if ($mail_sent) {
    echo "Wiadomość została wysłana pomyślnie.";
} else {
    echo "Wystąpił błąd podczas wysyłania wiadomości.";
}

?>