<?php
/******************************************************************************/
/* Auteur       : Thomas Carreira
/* Date         : 15.06.2016
/* Version      : 1.0
/* Page         : creer-modifier-membres.php
/* Description  : Formulaire qui permet au membre de se créer un compte ou à 
/*                l'administrateur de modifier un compte membre.
/******************************************************************************/

session_start();
require './librairie/librairie.php';
require './librairie/librairie_concours.php';
require './librairie/librairie_membres.php';

$message_erreur = "";

if (isset($_REQUEST['id_membre_modification']))
    $membre = charger_donnees_membre($_REQUEST['id_membre_modification']);
else
    $membre = charger_nouveau_membre();



if (isset($_REQUEST['valider'])) {

    $membre['num_licence'] = filter_input(INPUT_POST, 'num_licence', FILTER_SANITIZE_STRING);
    $membre['nom'] = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
    $membre['prenom'] = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
    $membre['date_naissance'] = filter_input(INPUT_POST, 'date_naissance', FILTER_SANITIZE_STRING);
    $mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_STRING);
    $mdp_verif = filter_input(INPUT_POST, 'mdp_verif', FILTER_SANITIZE_STRING);


    if ($membre['id_membre'] != -1) {
        $cree_nouveau_membre = false;
        $message_erreur = validation_creation_modification_membre($membre, $mdp, $mdp_verif, $cree_nouveau_membre);
    } else {
        $cree_nouveau_membre = true;
        $message_erreur = validation_creation_modification_membre($membre, $mdp, $mdp_verif, $cree_nouveau_membre);
    }
}

if (isset($_REQUEST['annuler'])) {
    if (isset($_REQUEST['id_membre_modification']))
        header('Location: administration-membres.php');
    else
        header('Location: connexion.php');
}
?>
<!DOCTYPE html>
<html>
    <?php debut_de_page(isset($_REQUEST['id_membre_modification']) ? "Modifier un membre - Arc club Jussy" : "Créer un membre - Arc club Jussy") ?>
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
            <legend><?php echo isset($_REQUEST['id_membre_modification']) ? "Modification" : "Création"; ?>  d'un compte membre : </legend>

            <?php
            if ($message_erreur != "") {
                echo '<div class="alert alert-danger" role="alert">
                                        <span class="glyphicon glyphicon-exclamation-sign"></span>
                                        <span class="sr-only">Error:</span>' . $message_erreur . '</div>';
            }
            ?>

            <label for="num_licence">Numéro de licence : </label>
            <div class="input-group">
                <input type="number" class="form-control" id="num_licence" name="num_licence" value="<?php echo $membre['num_licence'] ?>" placeholder="Format : 5 chiffres">
                <span class="input-group-addon"><span class="glyphicon glyphicon-tag"></span></span>
            </div>

            <label for="nom">Nom : </label>
            <div class="input-group">
                <input type="text" class="form-control" id="nom" name="nom" pattern="[a-zA-Zàäéèöü]+" value="<?php echo $membre['nom'] ?>">
                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
            </div>

            <label for="prenom">Prénom : </label>
            <div class="input-group">
                <input type="text" class="form-control" id="prenom" name="prenom" pattern="[a-zA-Zàäéèöü]+" value="<?php echo $membre['prenom'] ?>">
                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
            </div>

            <label for="date_naissance">Date de naissance : </label>
            <div class="input-group">
                <input type="date" class="form-control" id="date_concours" name="date_naissance" value="<?php echo $membre['date_naissance'] ?>">
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>

            <label for="mdp">Mot de passe : </label>
            <div class="input-group">
                <input type="password" class="form-control" id="mdp" name="mdp">
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
            </div>

            <label for="mdp_verif">Vérification du mot de passe : </label>
            <div class="input-group">
                <input type="password" class="form-control" id="mdp_verif" name="mdp_verif">
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
            </div>

            <br>
            <input type="submit" class="btn btn-default" name="valider" value="<?php echo isset($_REQUEST['id_membre_modification']) ? "Modifier" : "S'inscrire"; ?>">
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
