<?php
$to = "nazarm741852@gmail.com";
$subject = "test";
$message = "Message as a test";
$headers = "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "From:от Назара Михайлеца Сергеевича";
if (mail($to, $subject, $message, $headers)){
    echo "Mail is sent";
}
else{
    echo "Happened error";
}
?>