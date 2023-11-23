<?php
$inscription = true;
require '../include/header.php';
require '../components/Mail.php'
?>

<?php

if (isset($_POST['inscription'])) {

    if (empty($_POST['username']) || !preg_match('/[a-zA-Z0-9]+/', $_POST['username'])) {
        $message = 'Votre username doit être une chaine de caractère (alphanumérique)';
    } elseif (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $message = 'Votre email n\'est pas valide';
    } elseif (empty($_POST['password']) || $_POST['password'] != $_POST['password2']) {
        $message = 'Votre mot de passe doit être renseigné et être identique';
    } else {
        require_once '../include/start_bdd.php';

        $req = $bdd->prepare('select id from membres.table_membres where username = :username'); // :username est un marqueur nominatif
        $req->bindValue(':username', $_POST['username']);
        $req->execute();
        $result = $req->fetch();

        $req1 = $bdd->prepare('select id from membres.table_membres where email = :email'); // :email est un marqueur nominatif
        $req1->bindValue(':email', $_POST['email']);
        $req1->execute();
        $result1 = $req1->fetch();

        if ($result) {
            $message = 'Ce username, que vous avez choisi est déjà pris';
        } elseif ($result1) {
            $message = 'Un compte existe déjà avec l\'adresse email que vous avez choisie';
        } else {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $requete = $bdd->prepare('insert into membres.table_membres(username, email, password, token, validation) VALUES(:username, :email, :password, :token, :validation)');

            $requete->bindValue(':username', $_POST['username']);
            $requete->bindValue(':email', $_POST['email']);
            $requete->bindValue(':password', $password);
            $requete->bindValue(':token', $token);
            $requete->bindValue(':validation', 0);

            $requete->execute();
            $message = 'Vous êtes bien inscrits, consultez votre boite mail pour valider votre inscription.';
        }
    }
}
?>

    <body>

<div id="login" class="col-md-12 rounded ">
    <h3 class="text-center text-success pt-5">Inscription</h3>
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
                                <label for="username" class="form-label text-info">Username :</label>
                                <input type="text" name="username" id="username" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label text-info">Adresse Email :</label>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label text-info">Mot de passe :</label>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="password2" class="form-label text-info">Confirmation du mot de passe
                                    :</label>
                                <input type="password" name="password2" id="password2" class="form-control">
                            </div>
                            <div class="form-group mt-4">
                                <input type="submit" name="inscription" class="btn btn-info btn-md"
                                       value="S'inscrire">
                                <a href="http://localhost/espacemembre/components/connexion.php"
                                   class="btn btn-secondary btn-md">Se connecter</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<?php include '../include/footer.html';

