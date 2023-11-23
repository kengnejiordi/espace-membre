<?php
// On inclut les fichiers nécessaires pour utiliser la classe PHPMailer

require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

var_dump(class_exists('PHPMailer\PHPMailer\PHPMailer'));
#use PHPMailer\PHPMailer\SMTP;
#use PHPMailer\PHPMailer\Exception;
// On crée un nouvel objet de type PHPMailer
$mail = new PHPMailer();

// On indique qu'on va utiliser le protocole SMTP pour envoyer le mail
$mail->isSMTP();

// On définit le serveur SMTP à utiliser, ici celui de Gmail
$mail->Host = 'smtp.gmail.com';

// On active l'authentification SMTP avec le nom d'utilisateur et le mot de passe du compte Gmail
$mail->SMTPAuth = true;
$mail->Username = 'disquesdurs1@gmail.com';
$mail->Password = 'rlrpufwrzpabxzyp';

// On choisit le type de chiffrement à utiliser, ici TLS
$mail->SMTPSecure = 'tls';

// On définit le port à utiliser, ici 587
$mail->Port = 587;

// On définit l'adresse d'expédition du mail et le nom de l'expéditeur
$mail->setFrom('disquesdurs1@gmail.com', 'Jiordi Kengne');

// On ajoute l'adresse de réception du mail
$mail->addAddress('disquesdurs1@gmail.com');

// On indique que le contenu du mail sera au format HTML
$mail->isHTML();

// On définit le sujet du mail
$mail->Subject = 'Cet email est un test';

// On définit le corps du mail
$mail->Body = 'Afin de valider votre adresse email, merci de cliquer sur le lien suivant';

// On envoie le mail et on vérifie s'il y a des erreurs
if (!$mail->send()) {
    // Si le mail n'a pas été envoyé, on affiche un message d'erreur
    echo 'Mail non envoyé ! ';
    echo 'Erreurs:' . $mail->ErrorInfo;
} else {
    // Si le mail a été envoyé, on affiche un message de confirmation
    echo 'Votre Mail a bien été envoyé';
}
