<?php
// Inclure le fichier d'en-tête
require '../include/header.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Inclure le fichier de configuration de la base de données
require_once '../include/start_bdd.php';

// Vérifier si des paramètres GET ont été transmis
if ($_GET) {
    // Initialiser la variable $message pour éviter des erreurs si elle n'est pas définie plus tard.
    $message = '';

    // Vérifier si les clés 'email' et 'token' existent dans les paramètres GET
    if (isset($_GET['email']) && isset($_GET['token'])) {
        $email = $_GET['email'];
        $token = $_GET['token'];
    }

    // Vérifier si les variables $email et $token ne sont pas vides
    if (!empty($email) && !empty($token)) {
        // Préparer la requête SQL pour récupérer l'utilisateur en fonction de l'email et du token
        $requete = $bdd->prepare('SELECT * FROM membres.table_membres WHERE email=:email AND token=:token');
        $requete->bindValue(':email', $email);
        $requete->bindValue(':token', $token);

        // Exécuter la requête
        $requete->execute();

        // Obtenir le nombre de lignes résultantes
        $nombre = $requete->rowCount();

        // Vérifier si un utilisateur correspondant a été trouvé
        if ($nombre == 1) {
            // Préparer la requête SQL pour mettre à jour le statut de validation de l'utilisateur
            $update = $bdd->prepare('UPDATE membres.table_membres SET validation=:validation, token=:token WHERE email=:email');

            // Définir les valeurs des paramètres de la requête de mise à jour
            $update->bindValue(':validation', 1);
            $update->bindValue(':token', 'valide'); // Vous pouvez définir le token à null s'il n'est plus nécessaire après la validation.
            $update->bindValue(':email', $email);

            // Exécuter la requête de mise à jour
            $resultUpdate = $update->execute();

            // Vérifier si la mise à jour a réussi
            if ($resultUpdate) {
                echo "<script>
            var successMessage = document.createElement('div');
            successMessage.innerHTML = '<div class=\"welcome-message\">Bonjour, " . htmlspecialchars($_SESSION['username']) . ". votre compte a bien été approuvé dans notre espace membre.</div>';
            successMessage.className = 'centered-message';
            successMessage.className = 'centered-message-overlay';
            document.body.appendChild(successMessage);

            // Rediriger vers la page d'accueil après un délai de 3 secondes (3000 millisecondes)
            setTimeout(function() {
                window.location.href = 'http://localhost/espacemembre/';
            }, 3000);
          </script>";
            } else {
                $message = 'Une erreur esrt survenue lors de la mise à jour de la base de données';
            }
        } else {
            $message = 'La validation a echoué. vérifiez votre lien de confirmation. ';
        }
    } else {
        $message = 'Les paramètres email et token ne sont pas valides.';
    }
} else {
    $message = 'Les paramètres email et token sont manquants. ';
}

// Afficher le message s'il existe
if (isset($message)) echo $message;

?>
