<?php
global $bdd;

use PHPMailer\PHPMailer\PHPMailer;

require '../vendor/autoload.php';

if (isset($_POST['inscription'])) {
    if (empty($_POST['username']) || !preg_match('/[a-zA-Z0-9]+/', $_POST['username'])) {
        $message = 'Votre username doit être une chaîne de caractères (alphanumérique)';
    } elseif (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $message = 'Votre email n\'est pas valide';
    } elseif (empty($_POST['password']) || $_POST['password'] != $_POST['password2']) {
        $message = 'Votre mot de passe doit être renseigné et être identique';
    } else {
        require_once '../include/start_bdd.php';

        $req = $bdd->prepare('select id from membres.table_membres where username = :username');
        $req->bindValue(':username', $_POST['username']);
        $req->execute();
        $result = $req->fetch();

        $req1 = $bdd->prepare('select id from membres.table_membres where email = :email');
        $req1->bindValue(':email', $_POST['email']);
        $req1->execute();
        $result1 = $req1->fetch();

        if ($result) {
            $message = 'Ce username que vous avez choisi est déjà pris';
        } elseif ($result1) {
            $message = 'Un compte existe déjà avec l\'adresse email que vous avez choisie';
        } else {
            function token_random_string($length = 20): string
            {
                $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                $token = "";
                for ($i = 0; $i < $length; $i++) {
                    $token .= $characters[rand(0, strlen($characters) - 1)];
                }
                return $token;
            }

            $token = token_random_string();

            // Configuration de PHPMailer
            $mail = new PHPMailer(true);

            // Configurer le serveur SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'disquesdurs1@gmail.com';
            $mail->Password = 'rlrpufwrzpabxzyp';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Configurer l'émetteur et le destinataire
            // On définit l'adresse d'expédition du mail et le nom de l'expéditeur
            $mail->setFrom('disquesdurs1@gmail.com', 'Jiordi Kengne');
            $mail->addAddress($_POST['email']);

            // Contenu du mail
            $mail->isHTML();
            $mail->Subject = 'Confirmation d\'email';
            $mail->Body = 'Merci de vous être inscrit! Cliquez sur le lien ci-dessous pour valider votre inscription :
<a href="http://localhost/espacemembre/components/verify_email.php?email=' . $_POST['email'] . '&token=' . $token . '">Valider l\'inscription</a>';


            // Envoi du mail
            if (!$mail->send()) {
                echo 'Mail non envoyé';
                echo 'Erreurs :' . $mail->ErrorInfo;
            } else {
                // Si le mail a été envoyé avec succès, enregistrez les informations dans la base de données
                // Assurez-vous d'ajouter le code pour enregistrer les données dans la base de données ici
                echo 'Nous vous avons envoyé par courrier des instructions
                                 pour confirmer votre adresse e-mail que vous avez fourni.
                                 Vous devriez bientôt les recevoir';
            }
        }
    }
}
?>
