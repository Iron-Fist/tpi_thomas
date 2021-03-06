<?php
/******************************************************************************/
/* Auteur       : Thomas Carreira
/* Date         : 15.06.2016
/* Version      : 1.0
/* Page         : creer-modifier-concours.php
/* Description  : Formulaire permettant à l'administrateur de creer ou de
 *                modifier un concours.
/******************************************************************************/

session_start();
require './librairie/librairie.php';
require './librairie/librairie_concours.php';
require './librairie/librairie_membres.php';

$message_erreur = "";

if (!isset($_SESSION['membre_connecte']['est_admin']) || $_SESSION['membre_connecte']['est_admin'] == 0) {
    header('Location: mon-compte.php');
}

if (isset($_REQUEST['id_concours_modification']))
    $concours = charger_donnees_concours($_REQUEST['id_concours_modification']);
else
    $concours = charger_nouveau_concours();



if (isset($_REQUEST['valider'])) {
    $date = date_create($_REQUEST['date_concours']);
    date_sub($date, date_interval_create_from_date_string('7 days'));

    $date_limite_inscription = date_format($date, 'Y-m-d');
    $concours['intitule'] = filter_input(INPUT_POST, 'intitule', FILTER_SANITIZE_STRING);
    $concours['lieu'] = filter_input(INPUT_POST, 'lieu', FILTER_SANITIZE_STRING);
    $concours['nb_places'] = filter_input(INPUT_POST, 'nb_places', FILTER_SANITIZE_NUMBER_INT);
    $concours['date_concours'] = filter_input(INPUT_POST, 'date_concours', FILTER_SANITIZE_STRING);

    if ($concours['id_concours'] != -1) {
        $modification = true;
        $message_erreur = validation_creation_modification_concours($concours, $date_limite_inscription, $modification);
    } else {
        $modification = false;
        $message_erreur = validation_creation_modification_concours($concours, $date_limite_inscription, $modification);
    }
}

if (isset($_REQUEST['annuler'])) {
    header('Location: administration.php');
}
?>
<!DOCTYPE html>
<html>
    <?php debut_de_page(isset($_REQUEST['id_concours_modification']) ? "Modifier un concours - Arc club Jussy" : "Créer un concours - Arc club Jussy") ?>
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
            <legend><?php echo isset($_REQUEST['id_concours_modification']) ? "Modification" : "Création"; ?> d'un concours : </legend>

            <?php
            if ($message_erreur != "") {
                echo '<div class="alert alert-danger" role="alert">
                                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                        <span class="sr-only">Error:</span>' . $message_erreur . '</div>';
            }
            ?>

            <div class="form-group-sm">
                <p>
                    <label for="intitule">Intitulé : </label>
                    <input type="text" class="form-control" id="intitule" name="intitule" value="<?php echo $concours['intitule'] ?>">
                </p>
            </div>
            <div class="form-group-sm">
                <p>
                    <label for="lieu">Lieu : </label>
                    <input type="text" class="form-control" id="lieu" name="lieu" value="<?php echo $concours['lieu'] ?>">
                </p>
            </div>
            <div class="form-group-sm">
                <p>
                    <label for="nb_places">Nombre de places : </label>
                    <input type="number" class="form-control" id="nb_places" name="nb_places" max="10000" value="<?php echo $concours['nb_places'] ?>">
                </p>
            </div>
            <div class="form-group-sm">
                <p>
                    <label for="date_concours">Date : </label>
                    <br><i>La date doit être avancé d'au moins 8 jours</i>
                    <input type="date" class="form-control" id="date_concours" name="date_concours" value="<?php echo $concours['date_concours'] ?>">
                </p>
            </div>

            <input type="submit" class="btn btn-default" name="valider" value="<?php echo isset($_REQUEST['id_concours_modification']) ? "Modifier" : "Créer"; ?>">
            <input type="submit" class="btn btn-default" name="annuler" value="Annuler">

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
