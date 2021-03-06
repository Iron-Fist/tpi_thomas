<?php
/******************************************************************************/
/* Auteur       : Thomas Carreira
/* Date         : 15.06.2016
/* Version      : 1.0
/* Page         : administration-membres.php
/* Description  : Page permettant à l'administrateur de valider ou de rejeter
 *                les nouveaux membres ainsi que de modifier, supprimer et 
 *                consuler la totalité des concours auxquels un utilisateur
 *                c'est inscrit.
 */
/******************************************************************************/

session_start();
require './librairie/librairie.php';
require './librairie/librairie_concours.php';
require './librairie/librairie_membres.php';

if (!isset($_SESSION['membre_connecte']['est_admin']) || $_SESSION['membre_connecte']['est_admin'] == 0) {
    header('Location: mon-compte.php');
}

if (isset($_REQUEST['administration_concours'])) {
    header('Location: administration.php');
}
?>
<!DOCTYPE html>
<html>
    <?php debut_de_page('Administration - Arc club Jussy') ?>
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
        <li class="active">
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
            <legend>Administration des membres</legend>

            <input type="submit" class="btn btn-default" name="administration_concours" value="Administration concours">

            <h5>Liste des membres en attente de validation :</h5>
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
                                Date de naissance
                            </th>
                            <th>
                                Validation
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php tableau_membre_non_valide() ?>
                    </tbody>
                </table>
            </div>
            <h5>Liste des membres validé :</h5>
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
                                Date de naissance
                            </th>
                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php tableau_membre_valide() ?>
                    </tbody>
                </table>
            </div>
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
