<?php
/******************************************************************************/
/* Auteur       : Thomas Carreira
/* Date         : 15.06.2016
/* Version      : 1.0
/* Page         : librairie_concours.php
/* Description  : Page regroupant toutes les fonctions liées à la table
/*                t_concours ainsi que t_inscrits.
/******************************************************************************/

/**
 * Création d'un concours
 * @staticvar var $query
 * @param string $intitule
 * @param string $lieu
 * @param int $nb_places
 * @param date $date_concours
 * @param date $date_limite_inscription
 */
function creer_concours($intitule, $lieu, $nb_places, $date_concours, $date_limite_inscription) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("INSERT INTO t_concours(intitule, lieu, nb_places, date_concours, date_limite_inscription) 
    VALUES (:intitule, :lieu, :nb_places, :date_concours, :date_limite_inscription)");
    }

    $query->execute([
        "intitule" => $intitule,
        "lieu" => $lieu,
        "nb_places" => $nb_places,
        "date_concours" => $date_concours,
        "date_limite_inscription" => $date_limite_inscription
    ]);
}

/**
 * Modification d'un concours
 * @param int $id_concours
 * @param string $intitule
 * @param string $lieu
 * @param int $nb_places
 * @param date $date_concours
 * @param date $date_limite_inscription
 */
function modifier_concours($id_concours, $intitule, $lieu, $nb_places, $date_concours, $date_limite_inscription) {
    $query = connectDB()->prepare("UPDATE `t_concours`
                                   SET `intitule`=:intitule, `lieu`=:lieu, `nb_places`=:nb_places, `date_concours`=:date_concours, `date_limite_inscription`=:date_limite_inscription
                                   WHERE `id_concours`=:id_concours");
    $data = array(
        "id_concours" => $id_concours,
        "intitule" => $intitule,
        "lieu" => $lieu,
        "nb_places" => $nb_places,
        "date_concours" => $date_concours,
        "date_limite_inscription" => $date_limite_inscription
    );



    $query->execute($data);
}

/**
 * Suppression du concours de la table t_concours ayant comme id_concours : $id_concours
 * @param int $id_concours
 * @return array $data
 */
function supprimer_concours($id_concours) {
    $query = connectDB()->prepare("DELETE FROM t_concours WHERE id_concours = ?");
    $query->execute([$id_concours]);
}

/**
 * Suppression de la totalité des enregistrements de la table t_inscrits ayant comme id_concours : $id_concours
 * @param int $id_concours
 */
function desinscrire_membres_du_concours_supprimer($id_concours) {
    $query = connectDB()->prepare("DELETE FROM t_inscrits WHERE id_concours = ?");
    $query->execute([$id_concours]);
}

/**
 * Ajoute dans la table t_inscrits un nouvel enregistrement ayant comme id_concours : $id_concours et id_membre : $id_membre
 * @staticvar var $query
 * @param int $id_concours
 * @param int $id_membre
 */
function inscription_concours($id_concours, $id_membre) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("INSERT INTO t_inscrits(id_concours, id_membre) 
    VALUES (:id_concours, :id_membre)");
    }

    $query->execute([
        "id_concours" => $id_concours,
        "id_membre" => $id_membre
    ]);
}

/**
 * Supprime un enregistrement de la table t_inscrits qui a comme id_concours : $id_concours et id_membre : $id_membre
 * @staticvar var $query
 * @param int $id_concours
 * @param int $id_membre
 */
function desinscription_concours($id_concours, $id_membre) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("DELETE FROM `t_inscrits` WHERE id_concours = ? AND id_membre = ?");
    }

    $query->execute([$id_concours, $id_membre]);
}

/**
 * Création d'un tableau qui nous servira pour créer un concours
 * @return array
 */
function charger_nouveau_concours() {
    return array(
        "id_concours" => -1,
        "intitule" => "",
        "lieu" => "",
        "nb_places" => 0,
        "date_concours" => "",
        "date_limite_inscription" => ""
    );
}

/**
 * Séléction d'un concours (déjà crée) à partir de son id
 * @param int $id_concours
 * @return array $data
 */
function charger_donnees_concours($id_concours) {
    $query = connectDB()->prepare("SELECT * FROM `t_concours` WHERE id_concours = ?");
    $query->execute([$id_concours]);
    $data = $query->fetch(PDO::FETCH_ASSOC);
    return $data;
}

/**
 * Vérifie si l'intitule est déjà utilisé par un autre concours
 * @staticvar var $query
 * @param string $intitule
 * @return boolean
 */
function concours_existe($intitule) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT * FROM `t_concours` WHERE intitule = ?");
    }
    $query->execute([$intitule]);

    $data = $query->fetch(PDO::FETCH_ASSOC);

    if ($data === false)
        return false;
    else
        return true;
}

/**
 * Vérifie si la date est valide et si la date limite d'inscription n'est pas déjà dépassé
 * @param date $date_concours
 * @param date $date_limite_inscription
 * @return boolean
 */
function date_concours_valide($date_concours, $date_limite_inscription) {
    if (preg_match("#^([0-9]{4})-([0-9]{2})-([0-9]{2})$#", $date_concours, $matches)) {

        if (checkdate($matches[2], $matches[3], $matches[1])) {
            if ($date_limite_inscription > date('Y-m-d')) {
                return true;
            }
        }
    }
    return false;
}

/**
 * Vérifie si il existe un enregistrement de la table t_inscrit
 * qui aurait comme id_concours : $id_concours et id_membre : $id_membre
 * @staticvar var $query
 * @param int $id_concours
 * @param int $id_membre
 * @return boolean
 */
function est_inscrit($id_concours, $id_membre) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT * FROM `t_inscrits` WHERE id_concours = ? AND id_membre = ?");
    }
    $query->execute([$id_concours, $id_membre]);

    if ($query->rowCount() > 0)
        return true;
    else
        return false;
}

/**
 * Compte le nombre d'enregistrement de la table t_inscrits
 * qui ont comme id_concours : $id_concours
 * @staticvar var $query
 * @param int $id_concours
 * @return int
 */
function nb_inscrits($id_concours) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT COUNT(id_membre) AS nb_inscrits FROM `t_inscrits` WHERE id_concours = ?");
    }
    $query->execute([$id_concours]);

    return $query->fetch(PDO::FETCH_ASSOC)['nb_inscrits'];
}

/**
 * Fonction qui sert à la validation du formulaire de création d'un concours.
 * @param array $concours
 * @param date $date_limite_inscription
 * @param boolean $modification
 * @return string
 */
function validation_creation_modification_concours($concours, $date_limite_inscription, $modification) {
    $message_erreur = "";

    if ($concours['intitule'] != "") {
        if ($modification === false) {
            if (concours_existe($concours['intitule']) === true) {
                return $message_erreur = "L'<b>Intitule</b> est déjà utilisé.";
                exit();
            }
        }

        if ($concours['lieu'] != "") {
            if ($concours['nb_places'] > 0 && $concours['nb_places'] != "") {
                if (date_concours_valide($concours['date_concours'], $date_limite_inscription)) {
                    if ($modification)
                        modifier_concours($concours['id_concours'], $concours['intitule'], $concours['lieu'], $concours['nb_places'], $concours['date_concours'], $date_limite_inscription);
                    else
                        creer_concours($concours['intitule'], $concours['lieu'], $concours['nb_places'], $concours['date_concours'], $date_limite_inscription);
                    header('Location: administration.php');
                } else {
                    $message_erreur = "La <b>Date</b> n'est pas valide.";
                }
            } else {
                $message_erreur = "Le champ <b>Nombre de places</b> n'est pas composé d'un nombre suffisament élevé.";
            }
        } else {
            $message_erreur = "Le champ <b>Lieu</b> est vide.";
        }
    } else {
        $message_erreur = "Le champ <b>Intitule</b> est vide.";
    }

    return $message_erreur;
}

/**
 * Créé un tableau de tout les concours de la table t_concours
 * qui on une date_concours plus grande que la date du jour.
 * Avec en plus un lien pour modifier ou supprimer le concours.
 * @staticvar var $query
 */
function tableau_futur_concours() {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT * FROM `t_concours` WHERE `date_concours` > ? ORDER BY date_concours ASC");
    }
    $query->execute([date('Y-m-d')]);

    if ($query->rowCount() == 0) {
        echo '<tr>';
        echo '<td colspan="6" class="text-center">Il n\'y a aucun futurs concours.</td>';
        echo '</tr>';
    } else {
        while (($data = $query->fetch(PDO::FETCH_ASSOC)) !== false) {
            echo '<tr>';
            echo '<td>' . $data['intitule'] . '</td>';
            echo '<td>' . $data['lieu'] . '</td>';
            echo '<td>' . ($data['nb_places'] - nb_inscrits($data['id_concours'])) . " / " . $data['nb_places'] . '</td>';
            echo '<td>' . date_format(date_create($data['date_concours']), "d M Y") . '</td>';
            echo '<td>' . date_format(date_create($data['date_limite_inscription']), "d M Y") . '</td>';
            echo '<td><a href="creer-modifier-concours.php?id_concours_modification=' . $data["id_concours"] . '"><span class="glyphicon glyphicon-pencil"></span></a>'
            . " " . '<a href="#" data-toggle="modal" data-target="#' . $data['id_concours'] . '"><span class="glyphicon glyphicon-trash"></span></a>';
            echo '</tr>';
            creer_modale($data['id_concours'], 'Suppression de concours', 'Voulez-vous vraiment supprimer ' . $data['intitule'] . ' ?', '<a type="button" class="btn btn-primary" href="suppression-validation-inscription.php?id_concours_suppression=' . $data["id_concours"] . '">Oui</a>');
        }
    }
}

/**
 * Créé un tableau de tout les concours de la table t_concours
 * qui on une date_concours plus grande que la date du jour.
 * Avec en plus un lien pour s'inscrire ou se désinscrire d'un concours.
 * @staticvar var $query
 * @param int $id_membre
 */
function tableau_futur_concours_inscription($id_membre) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT * FROM `t_concours` WHERE `date_concours` >= ? ORDER BY date_concours ASC");
    }
    $query->execute([date('Y-m-d')]);

    if ($query->rowCount() == 0) {
        echo '<tr>';
        echo '<td colspan="5" class="text-center">Il n\'y a aucun futurs concours.</td>';
        echo '</tr>';
    } else {
        while (($data = $query->fetch(PDO::FETCH_ASSOC)) !== false) {
            echo '<tr>';
            echo '<td>' . $data['intitule'] . '</td>';
            echo '<td>' . $data['lieu'] . '</td>';
            echo '<td>' . ($data['nb_places'] - nb_inscrits($data['id_concours'])) . " / " . $data['nb_places'] . '</td>';
            echo '<td>' . date_format(date_create($data['date_concours']), "d M Y") . '</td>';
            echo '<td>' . date_format(date_create($data['date_limite_inscription']), "d M Y") . '</td>';
            if ($id_membre != -1) {
                if ($data['date_limite_inscription'] >= date('Y-m-d')) {
                    if (est_inscrit($data['id_concours'], $id_membre)) {
                        echo '<td><a href="#" data-toggle="modal" data-target="#' . $data['id_concours'] . '">Désinscription</a></td>';
                    } else if (($data['nb_places'] - nb_inscrits($data['id_concours'])) > 0) {
                        echo '<td><a href="suppression-validation-inscription.php?id_concours_inscription=' . $data["id_concours"] . '">Inscription</a></td>';
                    } else {
                        echo '<td>Plus de places</td>';
                    }
                } else {
                    echo '<td>Inscription fermée</td>';
                }
            }
            echo '</tr>';

            creer_modale($data['id_concours'], 'Désinscription d\'un concours', 'Voulez-vous vraiment vous désinscrire du conours ' . $data['intitule'] . ' ?', '<a type="button" class="btn btn-primary" href="suppression-validation-inscription.php?id_concours_desinscription=' . $data["id_concours"] . '">Oui</a>');
        }
    }
}

/**
 * Créé un tableau de tout les concours auxquels le membre connecté s'est inscrit
 * et qui n'ont pas encore eu lieu.
 * @staticvar var $query
 * @param int $id_membre
 */
function tableau_futur_concours_inscrits($id_membre) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT c.id_concours, intitule, lieu, nb_places, date_concours, date_limite_inscription FROM t_concours as c, t_inscrits as i WHERE c.id_concours = i.id_concours AND i.id_membre = ? AND c.date_concours >= ? ORDER BY date_concours ASC");
    }

    $query->execute([$id_membre, date('Y-m-d')]);

    if ($query->rowCount() == 0) {
        echo '<tr>';
        echo '<td colspan="6" class="text-center">Vous n\'êtes inscrit à aucun concours.</td>';
        echo '</tr>';
    } else {
        while (($data = $query->fetch(PDO::FETCH_ASSOC)) !== false) {

            echo '<tr>';
            echo '<td>' . $data['intitule'] . '</td>';
            echo '<td>' . $data['lieu'] . '</td>';
            echo '<td>' . ($data['nb_places'] - nb_inscrits($data['id_concours'])) . " / " . $data['nb_places'] . '</td>';
            echo '<td>' . date_format(date_create($data['date_concours']), "d M Y") . '</td>';
            echo '<td>' . date_format(date_create($data['date_limite_inscription']), "d M Y") . '</td>';
            echo '<td><a href="#" data-toggle="modal" data-target="#' . $data['id_concours'] . '">Désinscription</a></td>';
            echo '</tr>';
            creer_modale($data['id_concours'], 'Désinscription d\'un concours', 'Voulez-vous vraiment vous désinscrire du conours ' . $data['intitule'] . ' ?', '<a type="button" class="btn btn-primary" href="suppression-validation-inscription.php?id_concours_desinscription=' . $data["id_concours"] . '">Oui</a>');
        }
    }
}

/**
 * Créé un tableau de tout les concours auxquels le membre connecté s'est inscrit
 * et qui ont déjà eu lieu.
 * Ce tableau permet au utilisateur d'accéder à la consultation de leurs score.
 * @staticvar var $query
 * @param int $id_membre
 */
function tableau_concours_passe_inscrits($id_membre) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT c.id_concours, intitule, lieu, nb_places, date_concours, date_limite_inscription FROM t_concours as c, t_inscrits as i WHERE c.id_concours = i.id_concours AND i.id_membre = ? AND date_concours <= ? ORDER BY date_concours DESC");
    }

    $query->execute([$id_membre, date('Y-m-d')]);

    if ($query->rowCount() == 0) {
        echo '<tr>';
        echo '<td colspan="6" class="text-center">Vous n\'avez participez à aucun concours.</td>';
        echo '</tr>';
    } else {
        while (($data = $query->fetch(PDO::FETCH_ASSOC)) !== false) {

            echo '<tr>';
            echo '<td>' . $data['intitule'] . '</td>';
            echo '<td>' . $data['lieu'] . '</td>';
            echo '<td>' . date_format(date_create($data['date_concours']), "d M Y") . '</td>';
            if (resultat_remis($data['id_concours']))
                echo '<td><a href="rediger-consulter-resultats.php?id_concours_consulte=' . $data["id_concours"] . '">Consulter</a></td>';
            else
                echo '<td>Pas encore remis</td>';
            echo '</tr>';
        }
    }
}

/**
 * Créé un tableau de tout les concours passé qui ont eu des inscriptions 
 * et dont le champs score des participant = -1
 * Ce tableau permet aussi à l'administrateur d'accéder à la page de remise des résultats.
 * @staticvar var $query
 */
function tableau_remise_resultats_concours() {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT DISTINCT intitule, lieu, date_concours, c.id_concours FROM t_concours c, t_inscrits i WHERE c.id_concours = i.id_concours AND date_concours < ? AND score = -1 ORDER BY date_concours ASC");
    }
    $query->execute([date('Y-m-d')]);

    if ($query->rowCount() == 0) {
        echo '<tr>';
        echo '<td colspan="5" class="text-center">Il n\'y a aucun concours en attente de résultat.</td>';
        echo '</tr>';
    } else {
        while (($data = $query->fetch(PDO::FETCH_ASSOC)) !== false) {
            echo '<tr>';
            echo '<td>' . $data['intitule'] . '</td>';
            echo '<td>' . $data['lieu'] . '</td>';
            echo '<td>' . date_format(date_create($data['date_concours']), "d M Y") . '</td>';
            echo '<td><a href="rediger-consulter-resultats.php?id_concours_resultats=' . $data["id_concours"] . '">Remettre</a></td>';
            echo '</tr>';
        }
    }
}

/**
 * Créé un tableau de tout les concours qui ont déjà reçu leurs résultats
 * et qui peuvent donc désormais être modifié.
 * @staticvar var $query
 */
function tableau_modifier_resultats_concours() {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT DISTINCT intitule, lieu, date_concours, c.id_concours FROM t_concours c, t_inscrits i WHERE c.id_concours = i.id_concours AND date_concours < ? AND score <> -1 ORDER BY date_concours DESC");
    }
    $query->execute([date('Y-m-d')]);

    if ($query->rowCount() == 0) {
        echo '<tr>';
        echo '<td colspan="5" class="text-center">Il n\'y a aucun concours en attente de résultat.</td>';
        echo '</tr>';
    } else {
        while (($data = $query->fetch(PDO::FETCH_ASSOC)) !== false) {
            echo '<tr>';
            echo '<td>' . $data['intitule'] . '</td>';
            echo '<td>' . $data['lieu'] . '</td>';
            echo '<td>' . date_format(date_create($data['date_concours']), "d M Y") . '</td>';
            echo '<td><a href="rediger-consulter-resultats.php?id_concours_resultats=' . $data["id_concours"] . '">Modifier</a></td>';
            echo '</tr>';
        }
    }
}

/**
 * Créer un tableau de tout les concours auxquels un membre a participé
 * @staticvar var $query
 * @param int $id_membre
 */
function tableau_tout_concours_membre_participe($id_membre) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT c.id_concours, intitule, lieu, date_concours, score FROM t_concours as c, t_inscrits as i WHERE c.id_concours = i.id_concours AND i.id_membre = ? ORDER BY date_concours DESC");
    }

    $query->execute([$id_membre]);

    if ($query->rowCount() == 0) {
        echo '<tr>';
        echo '<td colspan="6" class="text-center">Le membre ne c\'est inscrit à aucun concours.</td>';
        echo '</tr>';
    } else {
        while (($data = $query->fetch(PDO::FETCH_ASSOC)) !== false) {

            echo '<tr>';
            echo '<td>' . $data['intitule'] . '</td>';
            echo '<td>' . $data['lieu'] . '</td>';
            echo '<td>' . date_format(date_create($data['date_concours']), "d M Y") . '</td>';
            if ($data['score'] == -1)
                echo '<td>Pas encore remis</td>';
            else
                echo '<td>' . $data['score'] . '</td>';
            echo '</tr>';
        }
    }
}

/**
 * Liste les participant d'un concours pour permettre à l'administrateur
 * de rendre ou de modifier les résultats.
 * @staticvar var $query
 * @param int $id_concours
 */
function liste_participant($id_concours) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT i.id_membre, score, num_licence, nom, prenom, date_naissance FROM t_inscrits i, t_membres m WHERE i.id_membre = m.id_membre AND id_concours = ? ORDER BY date_naissance DESC");
    }

    $query->execute([$id_concours]);

    while (($data = $query->fetch(PDO::FETCH_ASSOC)) !== false) {
        echo '<tr>';
        echo '<td>' . $data['num_licence'] . '</td>';
        echo '<td>' . $data['nom'] . '</td>';
        echo '<td>' . $data['prenom'] . '</td>';
        if ($data['score'] != -1)
            echo '<td><input type="number" name="' . $data['id_membre'] . '_score" value="' . $data['score'] . '" min="0" max="600"></td>';
        else
            echo '<td><input type="number" name="' . $data['id_membre'] . '_score" min="0" max="600"></td>';
        echo '</tr>';
    }
}

/**
 * Charge la liste de tout les participant d'un concours ainsi que leur score
 * @staticvar var $query
 * @param int $id_concours
 */
function consulter_concours($id_concours) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT i.id_membre, score, num_licence, nom, prenom, date_naissance FROM t_inscrits i, t_membres m WHERE i.id_membre = m.id_membre AND id_concours = ? ORDER BY score DESC");
    }

    $query->execute([$id_concours]);

    while (($data = $query->fetch(PDO::FETCH_ASSOC)) !== false) {
        echo '<tr>';
        echo '<td>' . $data['num_licence'] . '</td>';
        echo '<td>' . $data['nom'] . '</td>';
        echo '<td>' . $data['prenom'] . '</td>';
        echo '<td>' . $data['score'] . '</td>';
        echo '</tr>';
    }
}

/**
 * Met à jour le score($score) des membre inscrit($id_membre)
 * à un concours($id_concours).
 * @param int $id_membre
 * @param int $score
 * @param int $id_concours
 */
function mise_a_jour_score($id_membre, $score, $id_concours) {
    $query = connectDB()->prepare("UPDATE t_inscrits SET score=:score WHERE id_membre=:id_membre AND id_concours=:id_concours");

    $data = array(
        "score" => $score,
        "id_membre" => $id_membre,
        "id_concours" => $id_concours
    );

    $query->execute($data);
}

/**
 * Vérifie si les résultats d'un concours($id_concours) ont déjà été remis.
 * @staticvar var $query
 * @param int $id_concours
 * @return boolean
 */
function resultat_remis($id_concours) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT * FROM `t_inscrits` WHERE id_concours = ? AND score <> -1");
    }
    $query->execute([$id_concours]);

    $data = $query->fetch(PDO::FETCH_ASSOC);

    if ($data === false)
        return false;
    else
        return true;
}
