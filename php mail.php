<?php

    ini_set( 'display_errors', 1 );

    error_reporting( E_ALL );

    $from = "nous";

    $to = "adressedestinataire";

    $subject = "Vous avez reçu une notif !";

    $message = "Vous avez reçu une notification";

    $headers = "From:" . $from;

    mail($to,$subject,$message, $headers);

    echo "L'email a été envoyé.";
?>
