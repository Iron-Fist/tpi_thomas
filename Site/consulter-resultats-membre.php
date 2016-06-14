<?php
/******************************************************************************/
/* Auteur       : Thomas Carreira
/* Date         : 15.06.2016
/* Version      : 1.0
/* Page         : consulter-resultats-membre.php
/* Description  : Page permettant à l'administrateur de consulter tout 
/*                les concours auxqules un utilisateur à pu s'inscrire
/*                ainsi que les résultats qu'il aurait pu obtenir.
/******************************************************************************/

session_start();
require './librairie/librairie.php';
require './librairie/librairie_concours.php';
require './librairie/librairie_membres.php';

if (!isset($_REQUEST['id_membre']))
    header('Location: administration-membres.php');
else
    $membre = charger_donnees_membre($_REQUEST['id_membre']);
?>
<!DOCTYPE html>
<html>
    <?php debut_de_page('Consulter résultat d\'un membre - Arc club Jussy') ?>
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
        <legend>Liste des concours du membre <?php echo $membre['prenom'] . " " . $membre['nom'] ?> :</legend>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                    <tr>
                        <th>
                            Intitule
                        </th>
                        <th>
                            Lieu
                        </th>
                        <th>
                            Date du concours
                        </th>
                        <th>
                            Score obtenu
                        </th>
                    <tr>
                </thead>
                <tbody>
                    <?php echo tableau_tout_concours_membre_participe($_REQUEST['id_membre']) ?>
                </tbody>
            </table>
        </div>
        <a type="button" class="btn btn-default" href="administration-membres.php">Retour</a>
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
