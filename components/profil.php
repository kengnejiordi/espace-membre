<?php
session_start();
$profil = true;
require '../include/header.php';

if (isset($_SESSION['id'])) {
    ?>

    <main class="px-3">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-body">
                            <h3 class="text-center mb-4 text-info">Profil</h3>
                            <table class="table">
                                <tr>
                                    <th scope="row">Nom d'utilisateur</th>
                                    <td><?= $_SESSION['username'] ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Adresse email</th>
                                    <td><?= $_SESSION['email'] ?></td>
                                </tr>
                                <!-- Ajoutez d'autres informations du profil si nécessaire -->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php
}
include_once '../include/footer.html';
?>
