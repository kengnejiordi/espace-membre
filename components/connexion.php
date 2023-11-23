<?php
global $bdd;
$connexion = true;
session_start();
ob_start();  // Ajoutez cette ligne

include_once '../include/header.php';

if (isset($_POST['connexion'])) {
    // ...

    $email = $_POST['email'];
    $password = $_POST['password'];

    require_once '../include/start_bdd.php';
    $requete = $bdd->prepare('select * from membres.table_membres where email=:email');

    $requete->execute(array('email' => $email));
    $result = $requete->fetch();

    if (!$result) {
        $message = "Veuillez entrer une adresse email valide";
    } else {
        $passwordIsOk = password_verify($password, $result['password']);

        if ($passwordIsOk) {


            $_SESSION['id'] = $result['id'];
            $_SESSION['username'] = $result['username'];
            $_SESSION['email'] = $email;

            if (isset($_POST['sesouvenir'])) {
                setcookie('email', $_POST['email']);
                setcookie('password', $_POST['password']);
            } else {
                if (isset($_COOKIE['email'])) {
                    setcookie($_COOKIE['email'], "");
                }
                if (isset($_COOKIE['password'])) {
                    setcookie($_COOKIE['password'], "");
                }
            }
// Message de bienvenue personnalisé avec du CSS
            echo "<script>
            var successMessage = document.createElement('div');
            successMessage.innerHTML = '<div class=\"welcome-message\">Bonjour, " . htmlspecialchars($_SESSION['username']) . ".</div>';
            successMessage.className = 'centered-message';
            successMessage.className = 'centered-message-overlay';
            document.body.appendChild(successMessage);

            // Rediriger vers la page d'accueil après un délai de 3 secondes (3000 millisecondes)
            setTimeout(function() {
                window.location.href = 'http://localhost/espacemembre/';
            }, 3000);
          </script>";

            exit();
        } else {
            $message = "Veuillez entrer un mot de passe valide !";
        }
    }
}

?>

    <main class="bg-body">
        <div id="login" class="col-md-12 rounded p-5 ">
            <h3 class="text-center text-success pt-5">Connexion</h3>
            <div class="container m-0 p-0">
                <div id="login-row" class="row justify-content-center align-items-center">
                    <div id="login-column" class="col-md-6">
                        <div id="login-box" class="col-md-12">

                            <?php
                            if (isset($message)) echo $message; ?>

                            <form id="login-form" class="form form-check m-0 p-0" action="" method="post">
                                <div class="form-group">
                                    <label for="email" class="text-truncate d-flex">Adresse mail :</label>
                                    <input type="email" name="email" id="email" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="password" class="text-truncate d-flex">Mot de passe :</label>
                                    <input type="password" name="password" id="password" class="form-control">
                                </div>
                                <div class="form-check form-switch d-flex mt-3 mb-3">
                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked"
                                           name="sesouvenir">
                                    <label class="form-check-label d-flex text-black" for="sesouvenir">Se
                                        souvenir
                                        de moi</label>
                                </div>
                                <div class="form-group mt-3 mb-5 text-center">
                                    <input type="submit" name="connexion" class="btn btn-info btn-md"
                                           value="Se connecter">
                                    <a href="http://localhost/espacemembre/components/password_forget.php"
                                       class="ml-2"> Mot de passe oublié</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


<?php include '../include/footer.html';
