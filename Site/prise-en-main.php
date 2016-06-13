<?php
session_start();
require './librairie/librairie.php';
require './librairie/librairie_concours.php';
require './librairie/librairie_membres.php';
?>
<!DOCTYPE html>
<html>
    <?php debut_de_page('Arc club Jussy') ?>
    <ul class="nav navbar-nav">
        <li class="active">
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
        <legend>Explication des fonctionnalités disponible pour l'utilisateur</legend>

        <h4>Comment créer un compte membre :</h4>
        <p>
            Pour vous créer un compte, commencez par vous rendre sur la page de <a href="connexion.php"><b>Connexion <span class="glyphicon glyphicon-log-in"></span></b></a><br>
            Puis cliquez sur le bouton <a href="creer-modifier-membres.php"><b>Inscription</b></a> pour vous rendre sur la page d'inscription.<br>
            Une fois le formulaire d'inscription remplie cliqué sur le bonton <b>S'inscrire</b>.<br>
        </p>
        <p>
            Maintenant, vous n'avez plus cas attendre que l'administrateur valide votre compte.
        </p>

        <h4>Comment ce connecté :</h4>
        <p>
            Pour vous connecter il vous suffie de vous rendre sur la page de <a href="connexion.php"><b>Connexion <span class="glyphicon glyphicon-log-in"></span></b></a><br>
            et de rentrer vos information personnel telle que votre <b>Numéro de licence</b> et votre <b>Mot de passe</b>.<br>
            Si votre compte n'a pas encore été validé vous serez tenu au courant.
        </p>

        <h4>Comment s'inscrire à un concours :</h4>
        <p>
            Pour vous inscrire à un concours il vous faut tout d'abord vous connecter via la page <a href="connexion.php"><b>Connexion <span class="glyphicon glyphicon-log-in"></span></b></a>.<br>
            Une fois cette étape accomplie, rendez-vous sur la page <a href="connexion.php"><b>Accueil <span class="glyphicon glyphicon-home"></span></b></a>.<br>
            Puis comme vous pouvez le constater vous avez une liste détaillé de la totalité des futurs concours de tir à l'arc<br>
            et à droite de cette liste il y'a une colonne <b>Inscription</b>.<br>
            Dans cette colonne il y a 4 cas d'affichage différent :
        </p>
        <ol>
            <li>Un lien <b>Insription</b> qui vous permet de vous inscrire au concours.</li>
            <li>Un lien <b>Désinscription</b> qui vous permet de vous désinscrire du concours si vous y étiez déjà inscrit.</li>
            <li>Un message qui vous indique qu'il n'y a plus de place.</li>
            <li>Un autre message qui lui vous indique que la date limite d'inscription à été dépassé et que donc par conséquant les inscriptions sont fermées.</li>    
        </ol>


        <h4>Comment se désinscrire d'un concours :</h4>
        <p>
            Pour vous désinscrire vous devez pour commencer vous connecter via la page <a href="connexion.php"><b>Connexion <span class="glyphicon glyphicon-log-in"></span></b></a>.<br>
            Une fois connecté vous pouvez soit vous rendre sur la page <a href="mon-compte.php"><b>Mon compte <span class="glyphicon glyphicon-user"></span></b></a> et cliquer sur le lien <b>Désinscription</b> à droite<br>
            du tableau du haut qui correspond à la liste des futurs concours auxquels vous êtes inscrit.<br>
            Soit comme deuxième option, vous rendre sur la page <a href="connexion.php"><b>Accueil <span class="glyphicon glyphicon-home"></span></b></a> et cliquer sur le lien <b>Désinscription</b> à droite du tableau<br>
            correspondant a la liste des futurs concours.
        </p>

        <h4>Comment consulter ses résultats :</h4>
        <p>
            Pour consulter vos résultats, commencé par vous connecté via la page <a href="connexion.php"><b>Connexion <span class="glyphicon glyphicon-log-in"></span></b></a>.<br>
            Puis rendez-vous sur la page <a href="mon-compte.php"><b>Mon compte <span class="glyphicon glyphicon-user"></span></b></a>.<br>
            Comme vous pouvez le constater il y a deux tableau, un qui représente la liste des futurs concours auxquels vous êtes inscrit<br>
            et un peu plus bas se trouve la liste des concours passé auxquels vous avez prit part.<br>
            Pour ce tableau il y a deux cas de figure :
        </p>
        <ol>
            <li>Un lien <b>Consulter</b> qui vous permet de consulter vos résultats.</li>
            <li>Un message qui vous indique que l'administrateur n'a pas encore remis les résultats.</li>
        </ol>


        <legend>Explication des fonctionnalités diponible uniquement pour l'administrateur</legend>
        <p>
            Pour cette serie d'explication sur les fonctionnalités diponible uniquement pour l'administrateur,<br>
            Nous allons partir du principe que vous vous êtes déjà connecté via la page <a href="connexion.php"><b>Connexion <span class="glyphicon glyphicon-log-in"></span></b></a><br>
            et que vous êtes en possession d'un compte administrateur.
        </p>
        <p>
            Dans le cas contraire vous serez dans l'incapacité de reproduire les exemples<br>
            et de suivre les explications.
        </p>
        <p>
            Pour commencé, rendez-vous sur la page <b>Administration</b> en cliquant sur le lien <a href="administration.php"><b>Administration <span class="glyphicon glyphicon-wrench"></span></b></a>.<br>
            Maintenant que vous êtes sur la page <b>Administration</b> je vais pouvoir vous expliquer comment elle est constitué.<br>
        </p>
        <p>
            Cette page est donc séparé en deux partie une partie <b>Administration concours</b> et une partie <b>Administration membres</b>.<br>
            En cliquant sur le lien <a href="administration.php"><b>Administration <span class="glyphicon glyphicon-wrench"></span></b></a> vous vous êtes retrouvé sur la page <b>Administration concours</b>.<br>
            Maintenant si vous voulez vous rendre sur la page <b>Administration membre</b>,<br>
            il vous suffit de cliquer sur le bouton <b>Administration membre</b> en haut de la page.<br>
            Une fois sur la page <b>Administration membre</b> il vous suffit de cliquer sur le même bouton en haut de la page mais cependant,<br>
            vous pourrez remarqué qu'il est cette fois nommé <b>Administration concours</b> car il vous enverra  cette fois sur la page <b>Administration concours</b>.<br>
        </p>
        <p>
            Maintenant que vous savez comment passer d'une page à l'autre nous allons pouvoir commencer la suite des explication.
        </p>

        <h4>Comment valider ou rejeter un nouveau membre qui vient de s'inscrire :</h4>
        <p>
            Pour valider un nouveau membre, allez sous la page <a href="administration-membres.php"><b>Administration membres <span class="glyphicon glyphicon-wrench"></span></b></a><br>
            puis concentré vous sur le tableau du haut qui représente la totaliée des comptes membres en attente de validation.<br>
            Dans la colonne validation vous pouvez observer deux symboles, un <span class="glyphicon glyphicon-ok"></span> et une <span class="glyphicon glyphicon-remove"></span>.<br>
            Pour valider un membre il vous suffit de cliquer sur le <span class="glyphicon glyphicon-ok"></span><br>
            et si vous voulez le rejeter cliqué sur la <span class="glyphicon glyphicon-remove"></span>.
        </p>

        <h4>Comment modifier ou supprimer un compte membre :</h4>
        <p>
            Pour modifier un compte membre rendez-vous sur la page <a href="administration-membres.php"><b>Administration membres <span class="glyphicon glyphicon-wrench"></span></b></a><br>
            et cette fois ci concentrez vous sur le tableau du bas qui représente la liste de tout les comptes membres déjà validé.<br>
            Dans la colonne modification vous pouvez observer deux symboles, une <span class="glyphicon glyphicon-wrench"></span> et une <span class="glyphicon glyphicon-trash"></span>.<br>
            Pour modifier un membre cliqué sur la <span class="glyphicon glyphicon-wrench"></span><br>
            autrement si vous souhaiter supprimer le membre cliqué sur la <span class="glyphicon glyphicon-trash"></span>.<br>
            Si il n'y a pas de poubelle cela veut dire que le membre c'est déjà inscrit dans un concours et que par conséquent vous ne pouvez plus le supprimer.
        </p>

        <h4>Comment créer un concours :</h4>
        <p>
            Pour créer un concours allez sous la page <a href="administration.php"><b>Administration concours <span class="glyphicon glyphicon-wrench"></span></b></a><br>
            puis, cliqué sur le bouton <b>Créer un concours</b> tout en bas de la page.
            Cette action va vous envoyé sur une page en présence d'un formulaire qui lui vous permettra de créer votre concours.<br>
            Une fois tout les champs remplies cliqué sur le bouton <b>Créer</b> tout en bas de la page.
        </p>

        <h4>Comment modifier ou supprimer un concours :</h4>
        <p>
            Pour modifier ou supprimer un concours commencé par vous rendre sur la page <a href="administration.php"><b>Administration concours <span class="glyphicon glyphicon-wrench"></span></b></a>.<br>
            Il vous faut tout d'abord savoir que vous ne pouvez supprimer ou modifier uniquement les concours qui n'ont pas encore eu lieu.<br>
            Dans le tableau du haut vous pouvez observer deux sybole une <span class="glyphicon glyphicon-wrench"></span> pour modifier le concours<br>
            et une <span class="glyphicon glyphicon-trash"></span> pour supprimer un concours.
        </p>
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