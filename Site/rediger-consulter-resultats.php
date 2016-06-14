<?php
/******************************************************************************/
/* Auteur       : Thomas Carreira
/* Date         : 15.06.2016
/* Version      : 1.0
/* Page         : rediger-consulter-resultats.php
/* Description  : Page qui affiche la liste des participants d'un concours
/*                ainsi que leurs résultats.
/******************************************************************************/

session_start();
require './librairie/librairie.php';
require './librairie/librairie_concours.php';
require './librairie/librairie_membres.php';

$est_connecte = false;
$date_jour = date('Y-m-d');

if (!isset($_REQUEST['id_concours_resultats']) && !isset($_REQUEST['id_concours_consulte'])) {
    header('Location: administration.php');
}

if (isset($_REQUEST['id_concours_resultats'])) {
    $concours = charger_donnees_concours($_REQUEST['id_concours_resultats']);
} else if (isset($_REQUEST['id_concours_consulte'])) {
    $concours = charger_donnees_concours($_REQUEST['id_concours_consulte']);
}


if (isset($_REQUEST['remise'])) {
    foreach ($_REQUEST as $key => $value) {

        if (preg_match("#(\d+)_(score)#", $key, $matches)) {
            $id = $matches[1];
            mise_a_jour_score($id, $value, $_REQUEST['id_concours_resultats']);
        }
    }

    header('Location: administration.php');
}

if (isset($_REQUEST['annuler'])) {
    if (isset($_REQUEST['id_concours_consulte']))
        header('Location: mon-compte.php');
    else
        header('Location: administration.php');
}
?>
<!DOCTYPE html>
<html>
    <?php debut_de_page('Mon compte - Arc club Jussy') ?>
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
            <legend>Résultats du concours <?php echo $concours['intitule'] ?> : </legend>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th>
                                Numéro de licence
                            </th>
                            <th>
                                Nom
                            </th>
                            <th>
                                Prénom
                            </th>
                            <th>
                                Score
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($_REQUEST['id_concours_resultats']))
                            liste_participant($_REQUEST['id_concours_resultats']);
                        else
                            consulter_concours($_REQUEST['id_concours_consulte']);
                        ?>
                    </tbody>
                </table>
            </div>
            <?php
            if (isset($_REQUEST['id_concours_resultats']))
                echo '<input type="submit" class="btn btn-default" name="remise" value="Rendre les résultats">';
            ?>
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
