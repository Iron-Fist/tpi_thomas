<?php
session_start();
require './librairie/librairie.php';
require './librairie/librairie_concours.php';
require './librairie/librairie_membres.php';

$erreur = false;
$message_erreur = "";


if (isset($_REQUEST['id_membre_modification'])) {
    $_SESSION['membre'] = ancien_membre_temporaire($_REQUEST['id_membre_modification']);
} else if (!isset($_SESSION['membre'])) {
    $_SESSION['membre'] = nouveau_membre_temporaire();
}
$membre = $_SESSION['membre'];


if (isset($_REQUEST['valider'])) {

    $membre['num_licence'] = filter_input(INPUT_POST, 'num_licence', FILTER_SANITIZE_STRING);
    $membre['nom'] = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
    $membre['prenom'] = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
    $membre['date_naissance'] = filter_input(INPUT_POST, 'date_naissance', FILTER_SANITIZE_STRING);
    $mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_STRING);
    $mdp_verif = filter_input(INPUT_POST, 'mdp_verif', FILTER_SANITIZE_STRING);


    if ($membre['id_membre'] != -1) {
        $message_erreur = modification_membre_valide($membre, $mdp, $mdp_verif);
        
    } else {
        $message_erreur = creation_membre_valide($membre, $mdp, $mdp_verif);
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Compte</title>
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
        <link href="bootstrap/css/tuto.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="   crossorigin="anonymous"></script>
        <script src="./bootstrap/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <nav id="navigation" class="navbar navbar-default">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div class="navbar-header">
                        <a class="navbar-brand">Titre</a>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li>
                                <a href="index.php">Accueil <span class="glyphicon glyphicon-home"></span></a>
                            </li>
                            <li>
                                <a href="a-propos.php">A propos <span class="glyphicon glyphicon-book"></span></a>
                            </li>
                            <li>
                                <?php
                                if (isset($_SESSION['membre_connecte']))
                                    echo '<a href="mon-compte.php">Mon compte <span class="glyphicon glyphicon-user"></span></a>';
                                ?>
                            </li>
                            <li>
                                <?php
                                if (isset($_SESSION['membre_connecte'])) {
                                    if ($_SESSION['membre_connecte']['est_admin'])
                                        echo '<a href="administration.php">Administration <span class="glyphicon glyphicon-wrench"></span></a>';
                                }
                                ?>
                            </li>
                            <li>
                                <?php
                                if (isset($_SESSION['membre_connecte']))
                                    echo '<a href="deconnexion.php">Deconnexion <span class="glyphicon glyphicon-log-out"></span></a>';
                                else
                                    echo '<a href="connexion.php">Connexion <span class="glyphicon glyphicon-log-in"></span></a>';
                                ?>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="row">
                <section class="col-sm-12 table-responsive">
                    <form action="#" method="post">
                        <legend>Création d'un <?php echo $juste = (isset($_REQUEST['id-membre-modification'])) ? " concours : " : " compte membre : "; ?></legend>

                        <?php
                        if ($message_erreur != "") {
                            echo '<div class="alert alert-danger" role="alert">
                                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                        <span class="sr-only">Error:</span>' . $message_erreur . '</div>';
                        }
                        ?>

                        <div class="form-group-sm">
                            <p>
                                <label for="num_licence">Numéro de licence : </label>
                                <input type="number" class="form-control" id="num_licence" name="num_licence" value="<?php echo $membre['num_licence'] ?>" placeholder="Format : 5 chiffres">
                            </p>
                        </div>
                        <div class="form-group-sm">
                            <p>
                                <label for="nom">Nom : </label>
                                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $membre['nom'] ?>">
                            </p>
                        </div>
                        <div class="form-group-sm">
                            <p>
                                <label for="prenom">Prénom : </label>
                                <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo $membre['prenom'] ?>">
                            </p>
                        </div>
                        <div class="form-group-sm">
                            <p>
                                <label for="date_naissance">Date de naissance : </label>
                                <input type="date" class="form-control" id="date_concours" name="date_naissance" value="<?php echo $membre['date_naissance'] ?>">
                            </p>
                        </div>
                        <div class="form-group-sm">
                            <p>
                                <label for="mdp">Mot de passe : </label>
                                <input type="password" class="form-control" id="mdp" name="mdp">
                            </p>
                        </div>
                        <div class="form-group-sm">
                            <p>
                                <label for="mdp_verif">Vérification du mot de passe : </label>
                                <input type="password" class="form-control" id="mdp_verif" name="mdp_verif">
                            </p>
                        </div>


                        <input type="submit" class="btn btn-default" name="valider" value="<?php echo $juste = (isset($_REQUEST['id-membre-modification'])) ? "Modifier" : "Créer"; ?>">
                        <input type="submit" class="btn btn-default" name="annuler" value="Annuler">

                    </form>
                </section>
            </div>
            <div class="row">
                <footer class="col-sm-12">
                    Pied de page
                </footer>
            </div>
        </div>
    </body>
</html>
