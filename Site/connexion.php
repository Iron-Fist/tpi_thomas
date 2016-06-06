<?php
session_start();
require './librairie/librairie.php';
require './librairie/librairie_concours.php';
require './librairie/librairie_membres.php';

if (!isset($_SESSION['num_licence'])) {
    $_SESSION['num_licence'] = "";
}

if(isset($_REQUEST['inscription'])){
    header('Location: creer-modifier-membres.php');
}

$erreur = false;
$erreur_valide = false;

if (isset($_REQUEST['connexion'])) {

    $_SESSION['num_licence'] = filter_input(INPUT_POST, 'num_licence', FILTER_SANITIZE_STRING);
    $mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_STRING);

    $_SESSION['membre_connecte'] = connexion($_SESSION['num_licence'], $mdp);

    if ($_SESSION['membre_connecte'] !== false) {
        if ($_SESSION['membre_connecte']['est_valide']) {
            if ($_SESSION['membre_connecte']['est_admin']) {
                header('Location: administration.php');
            } else {
                header('Location: index.php');
            }
        } else {
            session_destroy();
            $_SESSION = array();
            $_SESSION['num_licence'] = filter_input(INPUT_POST, 'num_licence', FILTER_SANITIZE_STRING);
            $erreur_valide = true;
        }
    } else {
        session_destroy();
        $_SESSION = array();
        $_SESSION['num_licence'] = filter_input(INPUT_POST, 'num_licence', FILTER_SANITIZE_STRING);
        $erreur = true;
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Accueil</title>
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
                        <a class="navbar-brand">Arc club Jussy</a>
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
                            <li class="active">
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
                        <legend>Connexion</legend>
                        <?php
                        if ($erreur) {
                            echo '<div class="alert alert-danger" role="alert">
                                        <span class="glyphicon glyphicon-exclamation-sign"></span>
                                        <span class="sr-only">Error:</span>
                                        Les informations qui ont été saisies sont incorrects.
                                    </div>';
                        } elseif ($erreur_valide) {
                            echo '<div class="alert alert-danger" role="alert">
                                        <span class="glyphicon glyphicon-exclamation-sign"></span>
                                        <span class="sr-only">Error:</span>
                                        Votre compte n\'a pas encore été validé par l\'administrateur.
                                    </div>';
                        }
                        ?>

                        <label for="num_licence">Numéro de licence : </label> 
                        <div class="input-group">
                            <input type="text" class="form-control" id="num_licence" name="num_licence" value="<?php echo $_SESSION['num_licence'] ?>" placeholder="Format : 5 chiffres">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-tag"></span></span>
                        </div>

                        <label for="mdp">Mot de passe : </label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="mdp" name="mdp">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                            
                        </div>
                        <br>
                        <input type="submit" class="btn btn-default" name="connexion" value="Connexion">
                        <input type="submit" class="btn btn-default" name="inscription" value="Inscription">

                        <hr>
                    </form>
                </section>
            </div>
            <div class="row">
                <footer class="col-sm-12">
                    &copy; Thomas Carreira
                </footer>
            </div>
        </div>
    </body>
</html>
