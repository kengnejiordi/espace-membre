<?php
require 'Mail_reset.php';
/*require '../include/header.php';

use PHPMailer\PHPMailer\PHPMailer as PHPMailerAlias;

require '../vendor/autoload.php';

error_reporting(error_level: E_ALL);
ini_set(option: 'display_errors', value: 1);

if (isset($_POST['reset_password'])) {
    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $message = "Veuillez entrer une adresse email valide";
    } else {
        require '../include/start_bdd.php';

        $req = $bdd->prepare(query: 'SELECT * FROM membres.table_membres WHERE email=:email');
        $req->bindValue(':email', $_POST['email']);
        $req->execute();

        $result = $req->fetch();
        $nombre = $req->rowCount();

        if ($nombre != 1) {
            $message = "L'adresse email saisie ne correspond à aucun utilisateur de notre espace membre";
        } else {
            if ($result['validation'] != 1) {
                $token = generateRandomToken();
                $update = $bdd->prepare('UPDATE membres.table_membres SET token=:token WHERE email=:email');
                $update->bindValue(':token', $token);
                $update->bindValue(':email', $_POST['email']);
                $update->execute();

                // Configuration de PHPMailer
                $mail = new PHPMailerAlias(true);
                // Configurer le serveur SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'disquesdurs1@gmail.com';
                $mail->Password = 'rlrpufwrzpabxzyp';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Configurer l'émetteur et le destinataire
                $mail->setFrom('disquesdurs1@gmail.com', 'Jiordi Kengne');
                $mail->addAddress($_POST['email']);

                // Contenu du mail
                $mail->isHTML();
                $mail->Subject = 'Réinitialisation du mot de passe';
                $mail->Body = 'Cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe :
                <a href="http://localhost/espacemembre/components/reset_password.php?email=' . $_POST['email'] . '&token=' . $token . '">Réinitialiser le mot de passe</a>';

                // Envoi du mail
                if ($mail->send()) {
                    echo 'Nous vous avons envoyé par courrier des instructions pour réinitialiser votre mot de passe.
                    Vous devriez bientôt les recevoir.';
                } else {
                    echo 'Mail non envoyé';
                    echo 'Erreurs :' . $mail->ErrorInfo;
                }
            } else {
                $message = 'Votre compte est déjà validé. Utilisez la page de connexion.';
            }
        }
    }
}

function generateRandomToken($length = 20): string
{
    $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $token = "";
    for ($i = 0; $i < $length; $i++) {
        $token .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $token;
}

*/ ?>
    <!-- ... Votre code HTML ... -->


    <body>

    <div id="login" class="col-md-12 rounded">
        <h3 class="text-center text-white pt-5">Réinitialisation du mot de passe</h3>
        <h6 class="text-center text-white pt-5">Merci d'entrer votre adresse email ci-dessous, nous vous enverrons un
            des descriptions pour réinitialiser votre mot de passe.</h6>

        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <!-- Reste du code HTML -->
                            <main class="bg-body">
                                <?php
                                if (isset($message)) {
                                    echo '<div class="alert alert-success" role="alert">' . $message . '</div>';
                                    unset($message);
                                }
                                ?>
                                <form id="reset-form" class="form form-check" action="" method="post">
                                    <label for="email" class="text-truncate">Entrez votre adresse email :</label><br>
                                    <input type="email" name="email" id="email" class="form-control"
                                           placeholder="Exemple: gilbertnorbert@nom-de-domaine.com">
                                    <div class="form-group mt-5">
                                        <input type="submit" name="reset_password" class="btn btn-info btn-md"
                                               value="Réinitialiser le mot de passe">
                                        <a href="http://localhost/espacemembre/components/connexion.php"
                                           class="btn btn-info btn-md">Se connecter</a>
                                    </div>
                                </form>
                            </main>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>

    <!-- Reste du code HTML -->
<?php include '../include/footer.html';