<?php session_start();
require '../include/header.php';

?>
    <title>Réinitialisation</title>
    <body>
<?php
if ($_GET) {
    if (isset($_GET['email'])) {
        $email = $_GET['email'];
    }
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
    }
    if (!empty($email) && !empty($token)) {
        require_once '../include/start_bdd.php';
        $req = $bdd->prepare('select * from membres.table_membres where email=:email and token=:token');
        $req->bindValue(':email', $email);
        $req->bindValue(':token', $token);

        $req->execute();
        $nombre = $req->rowCount();

        if ($nombre != 1) {
            header('location:' . 'http://localhost/espacemembre/components/connexion.php');
        } else {
            if (isset($_POST['new_password'])) {
                if (empty($_POST['password']) || $_POST['password'] != $_POST['password2']) {
                    $message = "Rentrer un mot de passe valide";
                } else {
                    $password = password_hash($_POST['password'], algo: PASSWORD_DEFAULT);
                    $req = $bdd->prepare(query: 'update membres.table_membres set password=:password where email=:email');
                    $req->bindValue(param: ':email', value: $email);
                    $req->bindValue(param: 'password', value: $password);

                    $result = $req->execute();

                    if ($result) {
                        echo "<script type='text/javascript'>
            var successMessage = document.createElement('div');
            successMessage.innerHTML = '<div class=\"welcome-message\">Bonjour, " . htmlspecialchars($_SESSION['username']) . ". votre mot de passe a été réinitialisé avec succès.</div>';
            successMessage.className = 'centered-message';
            successMessage.className = 'centered-message-overlay';
            document.body.appendChild(successMessage);

            // Rediriger vers la page d'accueil après un délai de 3 secondes (3000 millisecondes)
            setTimeout(function() {
                window.location.href = 'http://localhost/espacemembre/';
            }, 3000);
          </script>";
                    }

                }


            }
        }
    }


} else {
    header(header: "Location:" . "http://localhost/espacemembre/index.php");
}


?>

    <div id="login" class="col-md-12 rounded ">
        <h3 class="text-center text-danger pt-5">Nouveau mot de passe </h3>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-lg">
                        <div class="card-body">

                            <?php
                            if (isset($message)) {
                                echo '<div class="alert alert-success" role="alert">' . $message . '</div>';
                                unset($message);
                            }
                            ?>

                            <form id="login-form" class="form form-check" action="" method="post">

                                <div class="mb-3">
                                    <label for="password" class="form-label text-info">Nouveau mot de passe :</label>
                                    <input type="password" name="password" id="password" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="password2" class="form-label text-info">Confirmation du nouveau mot de
                                        passe
                                        :</label>
                                    <input type="password" name="password2" id="password2" class="form-control">
                                </div>
                                <div class="form-group mt-4">
                                    <input type="submit" name="new_password" class="btn btn-info btn-md"
                                           value="Envoyer">

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require '../include/footer.html';


