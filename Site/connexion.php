<?php
/******************************************************************************/
/* Auteur       : Thomas Carreira
/* Date         : 15.06.2016
/* Version      : 1.0
/* Page         : connexion.php
/* Description  : Formulaire de connexion avec la possibilité d'accéder 
/*                à la page de création de compte.
/******************************************************************************/

session_start();
require './librairie/librairie.php';
require './librairie/librairie_concours.php';
require './librairie/librairie_membres.php';

if (isset($_SESSION['membre_connecte'])) {
    header('Location: administration.php');
}

if (!isset($_SESSION['num_licence'])) {
    $_SESSION['num_licence'] = "";
}

if (isset($_REQUEST['inscription'])) {
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
                header('Location: mon-compte.php');
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
    <?php debut_de_page('Connexion - Arc club Jussy') ?>
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
    <ul class="nav navbar-nav navbar-right">
        <li>
            <a href="aide.php"><b>?</b></a>
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
