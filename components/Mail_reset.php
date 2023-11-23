<?php
global $bdd;

use PHPMailer\PHPMailer\PHPMailer;

require '../vendor/autoload.php';

require '../include/header.php';

function token_random_string($length = 20): string
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $token = '';

    for ($i = 0; $i < $length; $i++) {
        $token .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $token;
}


if (isset($_POST['inscription'])) {
    // Your existing registration code
    header('Location: inscription.php');

} elseif (isset($_POST['reset_password'])) {
    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $message = 'Votre email n\'est pas valide';
    } else {
        require_once '../include/start_bdd.php';

        $req = $bdd->prepare('SELECT id, username FROM membres.table_membres WHERE email = :email');
        $req->bindValue(':email', $_POST['email']);
        $req->execute();
        $user = $req->fetch();

        if (!$user) {
            $message = 'Aucun compte trouvé avec cette adresse email';
        } else {
            // Generate a unique token for password reset
            $token = token_random_string();

            // Save the token in the database for future verification
            $updateToken = $bdd->prepare('UPDATE membres.table_membres SET token = :token WHERE id = :id');
            $updateToken->bindValue(':token', $token);
            $updateToken->bindValue(':id', $user['id']);
            $updateToken->execute();

            // Compose the reset link
            $resetLink = "";

            // Compose the email message
            $subject = 'Réinitialisation de mot de passe';
            $body = "Bonjour {$user['username']},\n\n";
            $body .= "Vous avez demandé une réinitialisation de mot de passe. \n";
            $body .= 'Cliquez sur le lien suivant pour choisir un nouveau mot de passe: <a href="http://localhost/espacemembre/components/reset_password.php?token=$token">Réinitialiser le mot de passe</a>' . " \n\n";
            $body = mb_convert_encoding($body, to_encoding: 'UTF-8');

            $body .= "Si vous n'avez pas demandé cette réinitialisation, veuillez ignorer ce message.\n\n";
            $body .= "Cordialement,\nVotre site";

            // Send the email
            $mail = new PHPMailer(true);
            // On active l'authentification SMTP avec le nom d'utilisateur et le mot de passe du compte Gmail
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'disquesdurs1@gmail.com';
            $mail->Password = 'rlrpufwrzpabxzyp';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Configurer l'émetteur et le destinataire
            // On définit l'adresse d'expédition du mail et le nom de l'expéditeur
            $mail->setFrom(address: 'disquesdurs1@gmail.com', name: 'Jiordi Kengne');
            $mail->addAddress($_POST['email'], $user['username']);
            $mail->Subject = $subject;
            $mail->Body = $body;

            try {
                $mail->send();
                $message = 'Un lien de réinitialisation a été envoyé à votre adresse email.';
            } catch (Exception $e) {
                echo 'Erreur lors de l\'envoi du mail: ' . $mail->ErrorInfo;
            }
        }
    }
}
